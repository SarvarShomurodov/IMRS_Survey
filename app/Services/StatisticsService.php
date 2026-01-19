<?php
// app/Services/StatisticsService.php

namespace App\Services;

use App\Models\SurveyResponse;
use App\Models\ResponseMissingSkill;
use App\Models\ResponseFutureDemandSkill;
use App\Models\Region;
use App\Models\District;
use App\Models\ActivityType;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class StatisticsService
{
    private const CACHE_TTL = 900; // 15 daqiqa

    /**
     * Viloyatlar bo'yicha so'rovnomalar statistikasi (optimized)
     */
    public function getResponsesByRegion($filters = []): Collection
    {
        $cacheKey = 'stats_by_region:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $allRegions = Region::select('id', 'name_uz as region_name')->orderBy('name_uz')->get()->keyBy('id');

            $responseStats = SurveyResponse::query()
                ->select(['region_id', DB::raw('COUNT(*) as count')])
                ->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))
                ->groupBy('region_id')
                ->get()
                ->keyBy('region_id');

            return $allRegions
                ->map(function ($region) use ($responseStats) {
                    return (object) [
                        'region_id' => $region->id,
                        'region_name' => $region->region_name,
                        'count' => $responseStats->get($region->id)?->count ?? 0,
                    ];
                })
                ->sortByDesc('count')
                ->values();
        });
    }

    /**
     * Tumanlar bo'yicha statistika (optimized)
     */
    public function getResponsesByDistrict(array $filters): Collection
    {
        if (empty($filters['region_id'])) {
            return collect();
        }

        $cacheKey = 'stats_by_district:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $allDistricts = District::select('id', 'name_uz as district_name')->where('region_id', $filters['region_id'])->orderBy('name_uz')->get()->keyBy('id');

            $responseStats = SurveyResponse::query()
                ->select(['district_id', DB::raw('COUNT(*) as count')])
                ->where('region_id', $filters['region_id'])
                ->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))
                ->groupBy('district_id')
                ->get()
                ->keyBy('district_id');

            return $allDistricts
                ->map(function ($district) use ($responseStats) {
                    return (object) [
                        'district_id' => $district->id,
                        'district_name' => $district->district_name,
                        'count' => $responseStats->get($district->id)?->count ?? 0,
                    ];
                })
                ->sortByDesc('count')
                ->values();
        });
    }

    /**
     * Faoliyat turlari bo'yicha statistika
     */
    public function getResponsesByActivityType($filters = [])
    {
        $cacheKey = 'stats_by_activity:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            return SurveyResponse::select(['activity_types.name_uz as activity_name', DB::raw('COUNT(*) as count')])
                ->join('activity_types', 'survey_responses.activity_type_id', '=', 'activity_types.id')
                ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
                ->groupBy('activity_types.id', 'activity_types.name_uz')
                ->orderBy('count', 'desc')
                ->get();
        });
    }

    /**
     * TOP yetishmayotgan kadrlar (batafsil ma'lumotlar bilan)
     */
    public function getTopMissingSkills($filters = [], $limit = 20)
    {
        $cacheKey = 'top_missing_skills:' . md5(serialize($filters) . $limit);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters, $limit) {
            return ResponseMissingSkill::select([
                'skills.id as skill_id',
                'skills.name as skill_name',
                'skills.group_code',
                'skills.worker_type',
                DB::raw('COUNT(DISTINCT response_missing_skills.survey_response_id) as responses_count'),
                DB::raw('SUM(COALESCE(response_missing_skills.required_count, 1)) as total_required'),

                // ЯНГИЛАНГАН таълим талаблари - reference array bilan mos
                DB::raw('SUM(CASE WHEN response_missing_skills.education_level = "ahmiyati_yok" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as edu_ahmiyati_yok'),
                DB::raw('SUM(CASE WHEN response_missing_skills.education_level = "orta" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as edu_orta'),
                DB::raw('SUM(CASE WHEN response_missing_skills.education_level = "umumiy_orta" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as edu_umumiy_orta'),
                DB::raw('SUM(CASE WHEN response_missing_skills.education_level = "oliy" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as edu_oliy'),
                DB::raw('SUM(CASE WHEN response_missing_skills.education_level = "phd" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as edu_phd'),

                // ЯНГИЛАНГАН тажриба талаблари - reference array bilan mos
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "0" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_0'),
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "0-1" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_0_1'),
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "1-2" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_1_2'),
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "3-5" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_3_5'),
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "6-9" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_6_9'),
                DB::raw('SUM(CASE WHEN response_missing_skills.experience_level = "10+" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as exp_10_plus'),

                // Жинс талаблари
                DB::raw('SUM(CASE WHEN response_missing_skills.gender_preference = "erkak" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as gender_male'),
                DB::raw('SUM(CASE WHEN response_missing_skills.gender_preference = "ayol" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as gender_female'),
                DB::raw('SUM(CASE WHEN response_missing_skills.gender_preference = "farq_qilmaydi" THEN COALESCE(response_missing_skills.required_count, 1) ELSE 0 END) as gender_any'),
            ])
                ->join('skills', 'response_missing_skills.skill_id', '=', 'skills.id')
                ->join('survey_responses', 'response_missing_skills.survey_response_id', '=', 'survey_responses.id')
                ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
                ->groupBy('skills.id', 'skills.name', 'skills.group_code', 'skills.worker_type')
                ->orderBy('total_required', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * TOP kelajakda talab oshadigan kadrlar (batafsil ma'lumotlar bilan)
     */
    public function getTopFutureDemandSkills($filters = [], $limit = 20)
    {
        $cacheKey = 'top_future_skills:' . md5(serialize($filters) . $limit);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters, $limit) {
            return ResponseFutureDemandSkill::select([
                'skills.id as skill_id',
                'skills.name as skill_name',
                'skills.group_code',
                'skills.worker_type',
                DB::raw('COUNT(DISTINCT response_future_demand_skills.survey_response_id) as responses_count'),
                DB::raw('SUM(COALESCE(response_future_demand_skills.expected_count, 1)) as total_expected'),

                // ЯНГИЛАНГАН таълим талаблари - reference array bilan mos
                DB::raw('SUM(CASE WHEN response_future_demand_skills.education_level = "ahmiyati_yok" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as edu_ahmiyati_yok'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.education_level = "orta" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as edu_orta'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.education_level = "umumiy_orta" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as edu_umumiy_orta'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.education_level = "oliy" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as edu_oliy'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.education_level = "phd" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as edu_phd'),

                // ЯНГИЛАНГАН тажриба талаблари - reference array bilan mos
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "0" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_0'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "0-1" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_0_1'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "1-2" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_1_2'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "3-5" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_3_5'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "6-9" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_6_9'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.experience_level = "10+" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as exp_10_plus'),

                // Жинс талаблари
                DB::raw('SUM(CASE WHEN response_future_demand_skills.gender_preference = "erkak" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as gender_male'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.gender_preference = "ayol" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as gender_female'),
                DB::raw('SUM(CASE WHEN response_future_demand_skills.gender_preference = "farq_qilmaydi" THEN COALESCE(response_future_demand_skills.expected_count, 1) ELSE 0 END) as gender_any'),
            ])
                ->join('skills', 'response_future_demand_skills.skill_id', '=', 'skills.id')
                ->join('survey_responses', 'response_future_demand_skills.survey_response_id', '=', 'survey_responses.id')
                ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
                ->groupBy('skills.id', 'skills.name', 'skills.group_code', 'skills.worker_type')
                ->orderBy('total_expected', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Bitta kasbning batafsil ma'lumotlari - YANGILANGAN VERSION
     */
    public function getDetailedSkillStatistics($skillId, $type = 'missing', $filters = [])
    {
        $cacheKey = "skill_details:{$skillId}:{$type}:" . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($skillId, $type, $filters) {
            $model = $type === 'missing' ? ResponseMissingSkill::class : ResponseFutureDemandSkill::class;
            $table = $type === 'missing' ? 'response_missing_skills' : 'response_future_demand_skills';

            return $model
                ::select([
                    'survey_responses.id as response_id',
                    'survey_responses.company_name',
                    'regions.name_uz as region_name',
                    'districts.name_uz as district_name',
                    'activity_types.name_uz as activity_type',
                    'survey_responses.employee_count',
                    'survey_responses.headcount_change',
                    'survey_responses.headcount_six_change', // YANGI maydon qo'shildi
                    "{$table}.education_level",
                    "{$table}.experience_level",
                    "{$table}.gender_preference",
                    'survey_responses.created_at',
                    'survey_responses.survey_period_year',
                    'survey_responses.survey_period_quarter',
                ])
                ->join('survey_responses', "{$table}.survey_response_id", '=', 'survey_responses.id')
                ->join('regions', 'survey_responses.region_id', '=', 'regions.id')
                ->join('districts', 'survey_responses.district_id', '=', 'districts.id')
                ->join('activity_types', 'survey_responses.activity_type_id', '=', 'activity_types.id')
                ->where("{$table}.skill_id", $skillId)
                ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
                ->orderBy('survey_responses.created_at', 'desc')
                ->get();
        });
    }

    /**
     * Viloyatlar bo'yicha kasblar statistikasi
     */
    public function getSkillsByRegions($skillId, $type = 'missing', $filters = [])
    {
        $cacheKey = "skill_by_regions:{$skillId}:{$type}:" . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($skillId, $type, $filters) {
            $model = $type === 'missing' ? ResponseMissingSkill::class : ResponseFutureDemandSkill::class;
            $table = $type === 'missing' ? 'response_missing_skills' : 'response_future_demand_skills';

            return $model
                ::select(['regions.name_uz as region_name', 'regions.id as region_id', DB::raw('COUNT(*) as count'), DB::raw('COUNT(DISTINCT survey_responses.id) as companies_count')])
                ->join('survey_responses', "{$table}.survey_response_id", '=', 'survey_responses.id')
                ->join('regions', 'survey_responses.region_id', '=', 'regions.id')
                ->where("{$table}.skill_id", $skillId)
                ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
                ->groupBy('regions.id', 'regions.name_uz')
                ->orderBy('count', 'desc')
                ->get();
        });
    }

    /**
     * Xodimlar soni o'zgarishi statistikasi
     */
    public function getHeadcountChangeStatistics($filters = [])
    {
        $cacheKey = 'headcount_changes:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $results = SurveyResponse::select('headcount_change', DB::raw('COUNT(*) as count'))->when($filters['region_id'], fn($q) => $q->where('region_id', $filters['region_id']))->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))->groupBy('headcount_change')->get();

            $labels = [
                'oshdi' => 'Ошди',
                'ozgarmadi' => 'Ўзгармади',
                'kamaydi' => 'Камайди',
            ];

            return $results->mapWithKeys(function ($item) use ($labels) {
                return [$labels[$item->headcount_change] ?? $item->headcount_change => $item->count];
            });
        });
    }

    /**
     * 6 oylik prognoz bo'yicha headcount statistikalari
     */
    public function getHeadcountSixChangeStatistics($filters = [])
    {
        $cacheKey = 'headcount_six_changes:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $results = SurveyResponse::select('headcount_six_change', DB::raw('COUNT(*) as count'))->when($filters['region_id'], fn($q) => $q->where('region_id', $filters['region_id']))->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))->whereNotNull('headcount_six_change')->groupBy('headcount_six_change')->get();

            $labels = [
                'oshdi' => 'Ошади',
                'ozgarmadi' => 'Ўзгармайди',
                'kamaydi' => 'Камаяди',
            ];

            return $results->mapWithKeys(function ($item) use ($labels) {
                return [$labels[$item->headcount_six_change] ?? $item->headcount_six_change => $item->count];
            });
        });
    }

    /**
     * Headcount trend tahlili
     */
    public function getHeadcountTrendsStatistics($filters = [])
    {
        $cacheKey = 'headcount_trends:' . md5(serialize($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $results = SurveyResponse::select('headcount_change', 'headcount_six_change', DB::raw('COUNT(*) as count'))->when($filters['region_id'], fn($q) => $q->where('region_id', $filters['region_id']))->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))->whereNotNull('headcount_change')->whereNotNull('headcount_six_change')->groupBy('headcount_change', 'headcount_six_change')->get();

            $trends = [];
            $total = $results->sum('count');

            foreach ($results as $result) {
                $trendLabel = $this->getTrendLabel($result->headcount_change, $result->headcount_six_change);

                if (!isset($trends[$trendLabel])) {
                    $trends[$trendLabel] = [
                        'label' => $trendLabel,
                        'count' => 0,
                        'percentage' => 0,
                        'color' => $this->getTrendColor($result->headcount_change, $result->headcount_six_change),
                    ];
                }

                $trends[$trendLabel]['count'] += $result->count;
            }

            // Percentage hisoblash
            foreach ($trends as &$trend) {
                $trend['percentage'] = $total > 0 ? round(($trend['count'] * 100) / $total, 2) : 0;
            }

            return collect($trends)->sortByDesc('count')->values();
        });
    }

    /**
     * Trend label olish
     */
    private function getTrendLabel($current, $forecast)
    {
        if ($current === 'oshdi' && $forecast === 'oshdi') {
            return 'Доимий ўсиш';
        } elseif ($current === 'kamaydi' && $forecast === 'kamaydi') {
            return 'Доимий камайиш';
        } elseif ($current === 'ozgarmadi' && $forecast === 'ozgarmadi') {
            return 'Барқарор ҳолат';
        } elseif ($current === 'oshdi' && $forecast === 'kamaydi') {
            return 'Вақтинчалик ўсиш';
        } elseif ($current === 'kamaydi' && $forecast === 'oshdi') {
            return 'Тикланиш кутилмоқда';
        } else {
            return 'Ўтиш давri';
        }
    }

    /**
     * Trend rangi olish
     */
    private function getTrendColor($current, $forecast)
    {
        if ($current === 'oshdi' && $forecast === 'oshdi') {
            return '#28a745'; // Yashil
        } elseif ($current === 'kamaydi' && $forecast === 'kamaydi') {
            return '#dc3545'; // Qizil
        } elseif ($current === 'ozgarmadi' && $forecast === 'ozgarmadi') {
            return '#17a2b8'; // Ko'k
        } elseif ($current === 'oshdi' && $forecast === 'kamaydi') {
            return '#ffc107'; // Sariq
        } elseif ($current === 'kamaydi' && $forecast === 'oshdi') {
            return '#007bff'; // Primary ko'k
        } else {
            return '#6c757d'; // Kulrang
        }
    }

    /**
     * Export uchun kasblar ro'yxati - YANGILANGAN VERSION
     */
    public function getSkillsForExport($type = 'missing', $filters = [])
    {
        $model = $type === 'missing' ? ResponseMissingSkill::class : ResponseFutureDemandSkill::class;
        $table = $type === 'missing' ? 'response_missing_skills' : 'response_future_demand_skills';

        return $model
            ::select([
                'skills.name as skill_name',
                'skills.group_code',
                'skills.worker_type',
                'regions.name_uz as region_name',
                'districts.name_uz as district_name',
                'activity_types.name_uz as activity_type',
                'survey_responses.company_name',
                'survey_responses.employee_count',
                'survey_responses.headcount_change',
                'survey_responses.headcount_six_change', // YANGI maydon qo'shildi
                "{$table}.education_level",
                "{$table}.experience_level",
                "{$table}.gender_preference",
                'survey_responses.created_at',
                'survey_responses.survey_period_year',
                'survey_responses.survey_period_quarter',
            ])
            ->join('skills', "{$table}.skill_id", '=', 'skills.id')
            ->join('survey_responses', "{$table}.survey_response_id", '=', 'survey_responses.id')
            ->join('regions', 'survey_responses.region_id', '=', 'regions.id')
            ->join('districts', 'survey_responses.district_id', '=', 'districts.id')
            ->join('activity_types', 'survey_responses.activity_type_id', '=', 'activity_types.id')
            ->when($filters['region_id'], fn($q) => $q->where('survey_responses.region_id', $filters['region_id']))
            ->when($filters['district_id'], fn($q) => $q->where('survey_responses.district_id', $filters['district_id']))
            ->when($filters['activity_type_id'], fn($q) => $q->where('survey_responses.activity_type_id', $filters['activity_type_id']))
            ->when($filters['year'], fn($q) => $q->where('survey_responses.survey_period_year', $filters['year']))
            ->when($filters['quarter'], fn($q) => $q->where('survey_responses.survey_period_quarter', $filters['quarter']))
            ->orderBy('skills.name')
            ->orderBy('survey_responses.created_at', 'desc')
            ->get();
    }

    /**
     * Cache tozalash
     */
    public function clearCache($pattern = null)
    {
        if ($pattern) {
            Cache::flush(); // Redis pattern support kerak bo'lsa
        } else {
            // Barcha statistika cache larini tozalash
            $patterns = ['stats_by_region:*', 'stats_by_district:*', 'stats_by_activity:*', 'top_missing_skills:*', 'top_future_skills:*', 'skill_details:*', 'skill_by_regions:*', 'headcount_changes:*', 'headcount_six_changes:*', 'headcount_trends:*'];

            foreach ($patterns as $pattern) {
                Cache::flush(); // Yoki pattern-based clearing
            }
        }
    }
}
