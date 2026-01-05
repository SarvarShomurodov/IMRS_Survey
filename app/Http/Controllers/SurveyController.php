<?php
// app/Http/Controllers/SurveyController.php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\District;
use App\Models\ActivityType;
use App\Models\SurveyResponse;
use App\Models\ResponseMissingSkill;
use App\Models\ResponseFutureDemandSkill;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class SurveyController extends Controller
{
    public function __construct()
    {
        // Auth middleware ni olib tashlash
        // $this->middleware('auth');
        // $this->middleware('check.survey.duplicate')->except(['success', 'getDistricts']);
    }

    public function showStep1(Request $request)
    {
        // Regions cache (2 soat - kam o'zgaradi)
        $regions = Cache::remember('survey_regions', 7200, function () {
            return Region::active()->orderBy('name_uz')->get();
        });

        // Activity Types cache (2 soat - kam o'zgaradi)
        $activityTypes = Cache::remember('survey_activity_types', 7200, function () {
            return ActivityType::active()->orderBy('name_uz')->get();
        });

        // Sessiyada saqlangan ma'lumotlarni olish
        $formData = session('survey.step1', []);

        return view('survey.step1', compact('regions', 'activityTypes', 'formData'));
    }

    public function processStep1(Request $request)
    {
        $validated = $request->validate(
            [
                'region_id' => 'required|exists:regions,id',
                'district_id' => 'required|exists:districts,id',
                'activity_type_id' => 'required|exists:activity_types,id',
                'company_name' => 'required|string|max:255',
                'company_address' => 'nullable|string|max:500',
                'employee_count' => 'required|integer|min:1|max:100000',
                'organizational_legal_form' => ['required', Rule::in(['davlat', 'xususiy'])], // YANGI
                'headcount_change' => ['required', Rule::in(['oshdi', 'ozgarmadi', 'kamaydi'])],
                'headcount_six_change' => ['required', Rule::in(['oshdi', 'ozgarmadi', 'kamaydi'])], // YANGI
            ],
            [
                'region_id.required' => 'Viloyatni tanlang',
                'district_id.required' => 'Tumanni tanlang',
                'activity_type_id.required' => 'Faoliyat turini tanlang',
                'company_name.required' => 'Korxona nomini kiriting',
                'employee_count.required' => 'Xodimlar sonini kiriting',
                'employee_count.min' => 'Xodimlar soni kamida 1 bo\'lishi kerak',
                'organizational_legal_form.required' => 'Tashkiliy-huquqiy shaklni tanlang', // YANGI
                'organizational_legal_form.in' => 'Noto\'g\'ri tashkiliy-huquqiy shakl tanlangan', // YANGI
                'headcount_change.required' => 'Xodimlar soni o\'zgarishini tanlang',
                'headcount_six_change.required' => '6 oylik prognozni tanlang', // YANGI
                'headcount_six_change.in' => '6 oylik prognoz uchun to\'g\'ri qiymat tanlang', // YANGI
            ],
        );

        // Tuman va viloyat mos kelishini tekshirish (cache bilan)
        $district = Cache::remember("district_check_{$validated['district_id']}", 1800, function () use ($validated) {
            return District::find($validated['district_id']);
        });

        if ($district->region_id != $validated['region_id']) {
            return back()
                ->withErrors(['district_id' => 'Tanlangan tuman va viloyat mos kelmaydi'])
                ->withInput();
        }

        // Ma'lumotlarni sessiyada saqlash
        session(['survey.step1' => $validated]);

        return redirect()->route('survey.step2');
    }

    public function showStep2()
    {
        // 1-bosqich ma'lumotlari tekshirilsin
        if (!session('survey.step1')) {
            return redirect()->route('survey.step1')->with('error', 'Avval 1-bosqichni to\'ldiring');
        }

        $step1Data = session('survey.step1');
        $formData = session('survey.step2', []);

        return view('survey.step2', compact('step1Data', 'formData'));
    }

    public function processStep2(Request $request)
    {
        $validated = $request->validate(
            [
                'missing_skills' => 'nullable|array',
                'missing_skills.*' => 'exists:skills,id',
                'future_demand_skills' => 'nullable|array',
                'future_demand_skills.*' => 'exists:skills,id',
            ],
            [
                'missing_skills.*.exists' => 'Noto\'g\'ri kadr tanlangan',
                'future_demand_skills.*.exists' => 'Noto\'g\'ri kadr tanlangan',
            ],
        );

        // Hech bo'lmaganda bitta skill tanlanganini tekshirish
        if (empty($validated['missing_skills']) && empty($validated['future_demand_skills'])) {
            return back()
                ->withErrors(['skills' => 'Hech bo\'lmaganda bitta kadr turini tanlang'])
                ->withInput();
        }

        // Ma'lumotlarni sessiyada saqlash
        session(['survey.step2' => $validated]);

        return redirect()->route('survey.step3');
    }

    public function showStep3()
    {
        // Oldingi bosqichlar ma'lumotlari tekshirilsin
        if (!session('survey.step1') || !session('survey.step2')) {
            return redirect()->route('survey.step1')->with('error', 'Avval oldingi bosqichlarni to\'ldiring');
        }

        $step1Data = session('survey.step1');
        $step2Data = session('survey.step2');

        // Tanlangan kadrlar haqida ma'lumot olish (TARTIB SAQLAB)
        $missingSkills = collect();
        $futureDemandSkills = collect();

        if (!empty($step2Data['missing_skills'])) {
            // Step2 da tanlangan tartibni saqlash
            $orderedMissingSkills = collect();
            foreach ($step2Data['missing_skills'] as $skillId) {
                $skill = Cache::remember("skill_details_{$skillId}", 1800, function () use ($skillId) {
                    return Skill::find($skillId);
                });

                if ($skill) {
                    $orderedMissingSkills->push($skill);
                }
            }
            $missingSkills = $orderedMissingSkills;
        }

        if (!empty($step2Data['future_demand_skills'])) {
            // Step2 da tanlangan tartibni saqlash
            $orderedFutureSkills = collect();
            foreach ($step2Data['future_demand_skills'] as $skillId) {
                $skill = Cache::remember("skill_details_{$skillId}", 1800, function () use ($skillId) {
                    return Skill::find($skillId);
                });

                if ($skill) {
                    $orderedFutureSkills->push($skill);
                }
            }
            $futureDemandSkills = $orderedFutureSkills;
        }

        // Eski ma'lumotlarni sessiyadan olish
        $formData = session('survey.step3', []);

        return view('survey.step3', compact('step1Data', 'step2Data', 'missingSkills', 'futureDemandSkills', 'formData'));
    }

    public function processStep3(Request $request)
    {
        $step2Data = session('survey.step2');

        if (!$step2Data) {
            return redirect()->route('survey.step1')->with('error', 'Session muddati tugagan. Iltimos, qaytadan boshlang.');
        }

        // Validation rules yaratish
        $rules = [];
        $messages = [];

        // Missing skills uchun validation
        if (!empty($step2Data['missing_skills'])) {
            foreach ($step2Data['missing_skills'] as $skillId) {
                $rules["missing_skill_{$skillId}_education"] = 'required|string';
                $rules["missing_skill_{$skillId}_experience"] = 'required|string';
                $rules["missing_skill_{$skillId}_gender"] = 'required|string';

                $messages["missing_skill_{$skillId}_education.required"] = 'Таълим даражасини танланг';
                $messages["missing_skill_{$skillId}_experience.required"] = 'Иш тажрибасини танланг';
                $messages["missing_skill_{$skillId}_gender.required"] = 'Жинсни танланг';
            }
        }

        // Future demand skills uchun validation
        if (!empty($step2Data['future_demand_skills'])) {
            foreach ($step2Data['future_demand_skills'] as $skillId) {
                $rules["future_skill_{$skillId}_education"] = 'required|string';
                $rules["future_skill_{$skillId}_experience"] = 'required|string';
                $rules["future_skill_{$skillId}_gender"] = 'required|string';

                $messages["future_skill_{$skillId}_education.required"] = 'Таълим даражасини танланг';
                $messages["future_skill_{$skillId}_experience.required"] = 'Иш тажрибасини танланг';
                $messages["future_skill_{$skillId}_gender.required"] = 'Жинсни танланг';
            }
        }

        $validated = $request->validate($rules, $messages);

        // Ma'lumotlarni sessiyada saqlash
        session(['survey.step3' => $validated]);

        return $this->submitSurvey($request);
    }

    public function submitSurvey(Request $request)
    {
        $step1Data = session('survey.step1');
        $step2Data = session('survey.step2');
        $step3Data = session('survey.step3');

        if (!$step1Data || !$step2Data || !$step3Data) {
            return redirect()->route('survey.step1')->with('error', 'Session muddati tugagan. Iltimos, qaytadan boshlang.');
        }

        try {
            DB::beginTransaction();

            $surveyResponse = SurveyResponse::create([
                'user_id' => null,
                'respondent_name' => $request->input('respondent_name'),
                'respondent_email' => $request->input('respondent_email'),
                'region_id' => $step1Data['region_id'],
                'district_id' => $step1Data['district_id'],
                'activity_type_id' => $step1Data['activity_type_id'],
                'company_name' => $step1Data['company_name'],
                'company_address' => $step1Data['company_address'] ?? null,
                'employee_count' => $step1Data['employee_count'],
                'organizational_legal_form' => $step1Data['organizational_legal_form'], // YANGI
                'headcount_change' => $step1Data['headcount_change'],
                'headcount_six_change' => $step1Data['headcount_six_change'], // YANGI
                'survey_period_year' => date('Y'),
                'survey_period_quarter' => ceil(date('n') / 3),
                'ip_address' => $request->ip(),
            ]);

            // Missing Skills - qo'shimcha ma'lumotlar bilan saqlash
            if (!empty($step2Data['missing_skills'])) {
                $uniqueSkills = array_unique($step2Data['missing_skills']);
                foreach ($uniqueSkills as $skillId) {
                    ResponseMissingSkill::create([
                        'survey_response_id' => $surveyResponse->id,
                        'skill_id' => $skillId,
                        'required_count' => 1, // Default qiymat
                        'education_level' => $step3Data["missing_skill_{$skillId}_education"] ?? null,
                        'experience_level' => $step3Data["missing_skill_{$skillId}_experience"] ?? null,
                        'gender_preference' => $step3Data["missing_skill_{$skillId}_gender"] ?? null,
                    ]);
                }
            }

            // Future Demand Skills - qo'shimcha ma'lumotlar bilan saqlash
            if (!empty($step2Data['future_demand_skills'])) {
                $uniqueSkills = array_unique($step2Data['future_demand_skills']);
                foreach ($uniqueSkills as $skillId) {
                    ResponseFutureDemandSkill::create([
                        'survey_response_id' => $surveyResponse->id,
                        'skill_id' => $skillId,
                        'expected_count' => 1, // Default qiymat
                        'education_level' => $step3Data["future_skill_{$skillId}_education"] ?? null,
                        'experience_level' => $step3Data["future_skill_{$skillId}_experience"] ?? null,
                        'gender_preference' => $step3Data["future_skill_{$skillId}_gender"] ?? null,
                    ]);
                }
            }

            // Cache'larni tozalash (yangi ma'lumot qo'shilganda)
            $this->clearSurveyRelatedCache($step1Data['region_id'], $step1Data['activity_type_id']);

            // Success ma'lumotlarini saqlash (cached regions/districts bilan)
            $regionName = Cache::remember("region_name_{$step1Data['region_id']}", 3600, function () use ($step1Data) {
                return Region::find($step1Data['region_id'])->getName();
            });

            $districtName = Cache::remember("district_name_{$step1Data['district_id']}", 3600, function () use ($step1Data) {
                return District::find($step1Data['district_id'])->getName();
            });

            $activityTypeName = Cache::remember("activity_type_name_{$step1Data['activity_type_id']}", 3600, function () use ($step1Data) {
                return ActivityType::find($step1Data['activity_type_id'])->getName();
            });

            session([
                'latest_survey_response' => [
                    'id' => $surveyResponse->id,
                    'company_name' => $surveyResponse->company_name,
                    'organizational_legal_form' => $surveyResponse->organizational_legal_form, // YANGI
                    'organizational_legal_form_text' => $surveyResponse->organizational_legal_form === 'davlat' ? 'Davlat' : 'Xususiy', // YANGI
                    'region_name' => $regionName,
                    'district_name' => $districtName,
                    'activity_type_name' => $activityTypeName,
                    'employee_count' => $surveyResponse->employee_count,
                    'created_at' => $surveyResponse->created_at,
                    'period_text' => $surveyResponse->getPeriodText(),
                    'headcount_change_text' => $surveyResponse->getHeadcountChangeText(), // YANGI
                    'headcount_six_change_text' => $surveyResponse->getHeadcountSixChangeText(), // YANGI
                    'headcount_trend' => $surveyResponse->getHeadcountTrend(), // YANGI
                    'missing_skills_count' => count($step2Data['missing_skills'] ?? []),
                    'future_demand_skills_count' => count($step2Data['future_demand_skills'] ?? []),
                ],
            ]);

            DB::commit();
            session()->forget(['survey.step1', 'survey.step2', 'survey.step3']);

            return redirect()->route('survey.success')->with('success', 'So\'rovnoma muvaffaqiyatli yuborildi!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Survey submission error: ' . $e->getMessage());

            return back()->with('error', 'Хатолик юз берди. Қайтадан уриниб кўринг.')->withInput();
        }
    }

    public function success()
    {
        // Anonymous survey uchun sessiyada saqlangan ma'lumotlarni olish
        $latestResponseData = session('latest_survey_response');

        return view('survey.success', compact('latestResponseData'));
    }

    public function getDistricts(Request $request)
    {
        $regionId = $request->get('region_id');

        if (!$regionId) {
            return response()->json([]);
        }

        // Districts cache (1 soat - kam o'zgaradi)
        $districts = Cache::remember("survey_districts_region_{$regionId}", 3600, function () use ($regionId) {
            return District::active()
                ->byRegion($regionId)
                ->orderBy('name_uz')
                ->get(['id', 'name_uz', 'name_ru']);
        });

        return response()->json($districts);
    }

    /**
     * Survey bilan bog'liq cache'larni tozalash
     */
    private function clearSurveyRelatedCache($regionId, $activityTypeId)
    {
        try {
            // Admin dashboard cache'larini tozalash
            $this->clearCacheByPattern('dashboard_*');
            $this->clearCacheByPattern('skills_statistics_*');
            $this->clearCacheByPattern('responses_*');

            // Region/activity specific cache'larni tozalash
            $this->clearCacheByPattern("*_region_{$regionId}_*");
            $this->clearCacheByPattern("*_activity_{$activityTypeId}_*");

            \Log::info('Survey cache cleared', [
                'region_id' => $regionId,
                'activity_type_id' => $activityTypeId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Survey cache clear error: ' . $e->getMessage());
        }
    }

    /**
     * Pattern bo'yicha cache tozalash
     */
    private function clearCacheByPattern($pattern)
    {
        try {
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $keys = $redis->keys($pattern);
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            } else {
                // File cache uchun - faqat tegishli cache'larni tozalash mumkin emas
                // Shuning uchun barchani tozalamaymiz, faqat log qilamiz
                \Log::info('File cache: cannot clear by pattern', ['pattern' => $pattern]);
            }
        } catch (\Exception $e) {
            \Log::error('Cache pattern clear error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Survey cache'larini manual tozalash (admin uchun)
     */
    public function clearSurveyCache(Request $request)
    {
        try {
            $patterns = ['survey_regions', 'survey_activity_types', 'survey_districts_*', 'skills_missing_*', 'skills_future_*', 'district_check_*', 'region_name_*', 'district_name_*', 'activity_type_name_*'];

            foreach ($patterns as $pattern) {
                if (strpos($pattern, '*') !== false) {
                    $this->clearCacheByPattern($pattern);
                } else {
                    Cache::forget($pattern);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Survey cache tozalandi',
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Cache tozalashda xatolik: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
