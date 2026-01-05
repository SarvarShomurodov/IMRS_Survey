<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Region;
use App\Models\District;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use App\Services\StatisticsService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

use App\Exports\DetailedSkillsExport;
use App\Exports\SurveyResponsesExport;
use App\Exports\SkillsStatisticsExport;

class AdminController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function dashboard(Request $request)
    {
        $filters = $this->getFilters($request);
        
        // Cache kaliti yaratish
        $cacheKey = $this->generateCacheKey('dashboard', $filters);
        
        // Redis bilan uzoqroq cache (yuqori traffic uchun)
        $dashboardData = Cache::remember($cacheKey, 1800, function () use ($filters) { // 30 daqiqa
            return $this->getDashboardData($filters);
        });

        // Filter options (1 soat cache)
        $filterOptions = Cache::remember('dashboard_filter_options', 7200, function () { // 2 soat
            return [
                'regions' => Region::select('id', 'name_uz')->orderBy('name_uz')->get(),
                'activityTypes' => ActivityType::select('id', 'name_uz')->orderBy('name_uz')->get(),
            ];
        });

        // Districts cache (1 soat)
        $districts = collect();
        if ($filters['region_id']) {
            $districtsCacheKey = "districts_region_{$filters['region_id']}";
            $districts = Cache::remember($districtsCacheKey, 3600, function () use ($filters) { // 1 soat
                return District::select('id', 'name_uz')
                    ->where('region_id', $filters['region_id'])
                    ->orderBy('name_uz')
                    ->get();
            });
        }

        return view('admin.dashboard', array_merge($dashboardData, [
            'filters' => $filters,
            'regions' => $filterOptions['regions'],
            'districts' => $districts,
            'activityTypes' => $filterOptions['activityTypes']
        ]));
    }

    private function getDashboardData($filters)
    {
        // Asosiy statistikalar
        $baseQuery = SurveyResponse::query()
            ->when($filters['region_id'], fn($q) => $q->where('region_id', $filters['region_id']))
            ->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))
            ->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))
            ->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))
            ->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']));

        $totalResponses = $baseQuery->count();
        $totalCompanies = $totalResponses;
        $totalEmployees = $baseQuery->sum('employee_count');

        // Service dan ma'lumotlarni olish
        $responsesByRegion = $this->statisticsService->getResponsesByRegion($filters);
        $responsesByDistrict = collect();
        if ($filters['region_id']) {
            $responsesByDistrict = $this->statisticsService->getResponsesByDistrict($filters);
        }

        $topMissingSkills = $this->statisticsService->getTopMissingSkills($filters, 10);
        $topFutureDemandSkills = $this->statisticsService->getTopFutureDemandSkills($filters, 10);
        $headcountChanges = $this->statisticsService->getHeadcountChangeStatistics($filters);
        // YANGI - 6 oylik prognoz statistikalari
        $headcountSixChanges = $this->statisticsService->getHeadcountSixChangeStatistics($filters);
        // YANGI - Trend tahlili
        $headcountTrends = $this->statisticsService->getHeadcountTrendsStatistics($filters);
        
        $responsesByActivity = $this->statisticsService->getResponsesByActivityType($filters);

        return compact(
            'totalResponses', 'totalCompanies', 'totalEmployees', 
            'responsesByRegion', 'responsesByDistrict', 'topMissingSkills', 
            'topFutureDemandSkills', 'headcountChanges', 'headcountSixChanges', 
            'headcountTrends', 'responsesByActivity'
        );
    }

    public function skillsStatistics(Request $request)
    {
        $filters = $this->getFilters($request);
        $type = $request->get('type', 'missing');
        $limit = min($request->get('limit', 50), 200);

        // Cache kaliti
        $cacheKey = $this->generateCacheKey("skills_statistics_{$type}_{$limit}", $filters);

        $skills = Cache::remember($cacheKey, 600, function () use ($filters, $type, $limit) {
            if ($type === 'missing') {
                return $this->statisticsService->getTopMissingSkills($filters, $limit);
            } else {
                return $this->statisticsService->getTopFutureDemandSkills($filters, $limit);
            }
        });

        // Filter options cache'dan olish
        $filterOptions = Cache::remember('skills_filter_options', 3600, function () {
            return [
                'regions' => Region::select('id', 'name_uz')->orderBy('name_uz')->get(),
                'activityTypes' => ActivityType::select('id', 'name_uz')->orderBy('name_uz')->get(),
            ];
        });

        $districts = collect();
        if ($filters['region_id']) {
            $districtsCacheKey = "districts_region_{$filters['region_id']}";
            $districts = Cache::remember($districtsCacheKey, 1800, function () use ($filters) {
                return District::select('id', 'name_uz')
                    ->where('region_id', $filters['region_id'])
                    ->orderBy('name_uz')
                    ->get();
            });
        }

        return view('admin.skills-statistics', [
            'skills' => $skills,
            'type' => $type,
            'filters' => $filters,
            'regions' => $filterOptions['regions'],
            'districts' => $districts,
            'activityTypes' => $filterOptions['activityTypes']
        ]);
    }

    public function skillDetail(Request $request, $skillId)
    {
        $filters = $this->getFilters($request);
        $type = $request->get('type', 'missing');

        // Skill ma'lumoti cache (kam o'zgaradi)
        $skill = Cache::remember("skill_detail_{$skillId}", 1800, function () use ($skillId) {
            return Skill::findOrFail($skillId);
        });

        // Statistika ma'lumotlari cache
        $cacheKey = $this->generateCacheKey("skill_detail_stats_{$skillId}_{$type}", $filters);
        
        $statisticsData = Cache::remember($cacheKey, 600, function () use ($skillId, $type, $filters) {
            return [
                'details' => $this->statisticsService->getDetailedSkillStatistics($skillId, $type, $filters),
                'regionStats' => $this->statisticsService->getSkillsByRegions($skillId, $type, $filters)
            ];
        });

        return view('admin.skill-detail', [
            'skill' => $skill,
            'details' => $statisticsData['details'],
            'regionStats' => $statisticsData['regionStats'],
            'type' => $type,
            'filters' => $filters
        ]);
    }

    public function responses(Request $request)
    {
        $filters = $this->getFilters($request);
        $perPage = min($request->get('per_page', 25), 100);

        // Responses ma'lumotlari uchun cache (qisqaroq vaqt, chunki yangi ma'lumotlar kelishi mumkin)
        $cacheKey = $this->generateCacheKey("responses_{$perPage}_page_" . $request->get('page', 1), $filters);
        
        $responses = Cache::remember($cacheKey, 300, function () use ($filters, $perPage) {
            return SurveyResponse::with(['region:id,name_uz', 'district:id,name_uz', 'activityType:id,name_uz'])
                ->select(['id', 'company_name', 'employee_count', 'headcount_change', 'headcount_six_change', 'region_id', 'district_id', 'activity_type_id', 'created_at', 'survey_period_year', 'survey_period_quarter'])
                ->when($filters['region_id'], fn($q) => $q->where('region_id', $filters['region_id']))
                ->when($filters['district_id'], fn($q) => $q->where('district_id', $filters['district_id']))
                ->when($filters['activity_type_id'], fn($q) => $q->where('activity_type_id', $filters['activity_type_id']))
                ->when($filters['year'], fn($q) => $q->where('survey_period_year', $filters['year']))
                ->when($filters['quarter'], fn($q) => $q->where('survey_period_quarter', $filters['quarter']))
                ->latest()
                ->paginate($perPage);
        });

        // Filter options
        $filterOptions = Cache::remember('responses_filter_options', 3600, function () {
            return [
                'regions' => Region::select('id', 'name_uz')->orderBy('name_uz')->get(),
                'activityTypes' => ActivityType::select('id', 'name_uz')->orderBy('name_uz')->get(),
            ];
        });

        $districts = collect();
        if ($filters['region_id']) {
            $districtsCacheKey = "districts_region_{$filters['region_id']}";
            $districts = Cache::remember($districtsCacheKey, 1800, function () use ($filters) {
                return District::select('id', 'name_uz')
                    ->where('region_id', $filters['region_id'])
                    ->orderBy('name_uz')
                    ->get();
            });
        }

        return view('admin.responses', [
            'responses' => $responses,
            'filters' => $filters,
            'regions' => $filterOptions['regions'],
            'districts' => $districts,
            'activityTypes' => $filterOptions['activityTypes']
        ]);
    }

    public function showResponse($id)
    {
        // Response detail cache (20 daqiqa)
        $response = Cache::remember("response_detail_{$id}", 1200, function () use ($id) {
            return SurveyResponse::with([
                'region:id,name_uz', 
                'district:id,name_uz', 
                'activityType:id,name_uz', 
                'missingSkillsList.skill:id,name', 
                'futureDemandSkillsList.skill:id,name'
            ])->findOrFail($id);
        });

        return view('admin.response-detail', compact('response'));
    }

    public function getDistrictsForAdmin(Request $request)
    {
        $regionId = $request->get('region_id');

        if (!$regionId) {
            return response()->json([]);
        }

        // Districts cache (30 daqiqa)
        $districts = Cache::remember("districts_admin_{$regionId}", 1800, function () use ($regionId) {
            return District::select('id', 'name_uz', 'name_ru')
                ->where('region_id', $regionId)
                ->orderBy('name_uz')
                ->get();
        });

        return response()->json($districts);
    }

    public function clearCache(Request $request)
    {
        // Barcha dashboard cache'larni tozalash
        $patterns = [
            'dashboard_*',
            'skills_statistics_*',
            'skill_detail_*',
            'responses_*',
            'response_detail_*',
            'districts_*',
            '*_filter_options'
        ];

        foreach ($patterns as $pattern) {
            $this->clearCacheByPattern($pattern);
        }

        // Statistics service cache'ni ham tozalash
        $this->statisticsService->clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Barcha cache tozalandi',
        ]);
    }

    /**
     * Cache kalitini yaratish
     */
    private function generateCacheKey($prefix, $filters = [])
    {
        $filterString = collect($filters)
            ->filter()
            ->map(function ($value, $key) {
                return "{$key}_{$value}";
            })
            ->implode('_');

        return $filterString ? "{$prefix}_{$filterString}" : $prefix;
    }

    /**
     * Pattern bo'yicha cache tozalash
     */
    private function clearCacheByPattern($pattern)
    {
        // File cache uchun barcha cache'ni tozalash
        Cache::flush();
    }

    public function exportSkillsStatistics(Request $request)
    {
        $filters = $this->getFilters($request);
        $type = $request->get('type', 'missing');

        $skills = $this->statisticsService->getSkillsForExport($type, $filters);

        $filename = sprintf('%s_skills_detailed_%s.csv', 
            $type === 'missing' ? 'yetishmayotgan' : 'kelajakdagi', 
            date('Y-m-d')
        );

        return $this->exportDetailedSkillsAsCSV($skills, $filename);
    }

    private function exportDetailedSkillsAsCSV($skills, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($skills) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM qo'shish (Excel uchun)
            fprintf($file, chr(0xef) . chr(0xbb) . chr(0xbf));

            // CSV headers - YANGI ustunlar qo'shildi
            fputcsv($file, [
                '№', 'Касб номи', 'Гуруҳ коди', 'Ишчи тури', 'Корхоналар сони', 
                'Ушбу касб учун талаб қилинган минимал таълим даражасини', 
                'Ушбу касб учун талаб қилинган минимал иш тажрибаси', 
                'Ушбу касб учун жинс бўйича талаб', 'Корхона номи', 'Вилоят', 
                'Туман', 'Фаолият тури', 'Ходимлар сони', 'Охирги йилдаги ўзгариш',
                '6 ойлик прогноз', 'Тренд таҳлили', 'Санаси'
            ]);

            // Cache qilingan tarjimalar - YANGI tarjimalar
            $translations = Cache::remember('export_translations', 3600, function () {
                return [
                    'education' => [
                        'ahmiyati_yok' => 'Аҳамияти йўқ',
                        'orta' => 'Ўрта (11 йиллик таълим)',
                        'umumiy_orta' => 'Ўрта махсус / профессионал коллеж (техникум, касб-ҳунар)',
                        'oliy' => 'Олий (бакалавр / магистр)',
                        'phd' => 'Олий илмий даража (PhD/ DcS)',
                    ],
                    'experience' => [
                        '0' => 'Тажриба талаб қилинмайди',
                        '0-1' => '1 йил ёки ундан кам',
                        '1-2' => '1-2 йил',
                        '3-5' => '3-5 йил',
                        '6-9' => '6-9 йил',
                        '10+' => '10 йилдан ортиқ',
                    ],
                    'gender' => [
                        'erkak' => 'Эркак',
                        'ayol' => 'Аёл',
                        'farq_qilmaydi' => 'Аҳамияти йўқ',
                    ],
                    'headcount' => [
                        'oshdi' => 'Ошди',
                        'ozgarmadi' => 'Ўзгармади',
                        'kamaydi' => 'Камайди'
                    ],
                    'headcount_six' => [
                        'oshdi' => 'Ошади',
                        'ozgarmadi' => 'Ўзгармайди',
                        'kamaydi' => 'Камаяди'
                    ]
                ];
            });

            // Data - YANGI maydonlar qo'shildi
            foreach ($skills as $index => $skill) {
                // Trend tahlili hisoblash
                $trend = 'N/A';
                if (isset($skill->headcount_change) && isset($skill->headcount_six_change)) {
                    if ($skill->headcount_change === 'oshdi' && $skill->headcount_six_change === 'oshdi') {
                        $trend = 'Доимий ўсиш';
                    } elseif ($skill->headcount_change === 'kamaydi' && $skill->headcount_six_change === 'kamaydi') {
                        $trend = 'Доимий камайиш';
                    } elseif ($skill->headcount_change === 'ozgarmadi' && $skill->headcount_six_change === 'ozgarmadi') {
                        $trend = 'Барқарор ҳолат';
                    } elseif ($skill->headcount_change === 'oshdi' && $skill->headcount_six_change === 'kamaydi') {
                        $trend = 'Вақтинчалик ўсиш';
                    } elseif ($skill->headcount_change === 'kamaydi' && $skill->headcount_six_change === 'oshdi') {
                        $trend = 'Тикланиш кутилмоқда';
                    } else {
                        $trend = 'Ўтиш даври';
                    }
                }

                fputcsv($file, [
                    $index + 1,
                    $skill->skill_name ?? 'N/A',
                    $skill->group_code ?? 'N/A',
                    $skill->worker_type ?? 'xizmatchi',
                    1,
                    $translations['education'][$skill->education_level] ?? ($skill->education_level ?? 'N/A'),
                    $translations['experience'][$skill->experience_level] ?? ($skill->experience_level ?? 'N/A'),
                    $translations['gender'][$skill->gender_preference] ?? ($skill->gender_preference ?? 'N/A'),
                    $skill->company_name ?? 'N/A',
                    $skill->region_name ?? 'N/A',
                    $skill->district_name ?? 'N/A',
                    $skill->activity_type ?? 'N/A',
                    $skill->employee_count ?? 0,
                    $translations['headcount'][$skill->headcount_change] ?? ($skill->headcount_change ?? 'N/A'),
                    $translations['headcount_six'][$skill->headcount_six_change] ?? ($skill->headcount_six_change ?? 'N/A'),
                    $trend,
                    $skill->created_at ? $skill->created_at->format('d.m.Y H:i') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export(Request $request)
    {
        $filters = $this->getFilters($request);
        $filename = 'survey_responses_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SurveyResponsesExport($filters), $filename);
    }

    protected function getFilters(Request $request)
    {
        return [
            'region_id' => $request->get('region_id'),
            'district_id' => $request->get('district_id'),
            'activity_type_id' => $request->get('activity_type_id'),
            'year' => $request->get('year', date('Y')),
            'quarter' => $request->get('quarter'),
        ];
    }
}