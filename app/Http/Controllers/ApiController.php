<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Services\SkillSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $skillSearchService;

    public function __construct(SkillSearchService $skillSearchService)
    {
        $this->skillSearchService = $skillSearchService;
    }

    public function searchSkills(Request $request)
    {
        $query = $request->get('q', '');
        $workerType = $request->get('type', '');
        $limit = min($request->get('limit', 20), 50); // maksimal 50

        if (empty($query) || mb_strlen($query) < 2) {
            return response()->json([]);
        }

        // Cache key yaratish
        $cacheKey = 'skills_search:' . md5($query . $workerType . $limit);
        
        $results = Cache::remember($cacheKey, 300, function () use ($query, $workerType, $limit) {
            return $this->performSearch($query, $workerType, $limit);
        });

        return response()->json($results);
    }

    private function performSearch($query, $workerType, $limit)
    {
        try {
            // Qidiruv query ni build qilish
            $skillsQuery = Skill::active()
                ->search($query)
                ->byWorkerType($workerType);

            // Debug uchun (production da olib tashlang)
            Log::info('Skill Search Query', [
                'original_query' => $query,
                'worker_type' => $workerType,
                'sql' => $skillsQuery->toSql(),
                'bindings' => $skillsQuery->getBindings()
            ]);

            $skills = $skillsQuery
                ->orderBy('sequence_number')
                ->limit($limit)
                ->get();

            // Agar natija topilmasa, kengaytirilgan qidiruv
            if ($skills->isEmpty()) {
                $skills = $this->expandedSearch($query, $workerType, $limit);
            }

            return $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'text' => $skill->name, // Select2 uchun 'text' field kerak
                    'name' => $skill->name,
                    'worker_type' => $skill->worker_type,
                    'group_code' => $skill->group_code,
                    'qualification_category' => $skill->qualification_category
                ];
            });

        } catch (\Exception $e) {
            Log::error('Skill search error: ' . $e->getMessage());
            return collect();
        }
    }

    private function expandedSearch($query, $workerType = null, $limit = 20)
    {
        // Har bir so'z bo'yicha qidirish
        $words = array_filter(explode(' ', trim($query)), function($word) {
            return mb_strlen($word) >= 2;
        });
        
        if (empty($words)) {
            return collect();
        }

        $skillsQuery = Skill::active()->byWorkerType($workerType);

        $skillsQuery->where(function($q) use ($words) {
            foreach ($words as $word) {
                $normalizedWord = (new Skill())->normalizeSearchTerm($word);
                $cyrillicWord = (new Skill())->latinToCyrillic($word);
                
                $q->orWhere('name', 'LIKE', "%{$word}%")
                  ->orWhere('name_normalized', 'LIKE', "%{$normalizedWord}%");
                
                // Agar lotin matn kiritilgan bo'lsa, kirill variantida ham qidirish
                if ($cyrillicWord !== $word) {
                    $q->orWhere('name', 'LIKE', "%{$cyrillicWord}%");
                }
            }
        });

        return $skillsQuery->orderBy('sequence_number')->limit($limit)->get();
    }

    // ApiController da getSkillById metodini tuzatish
    public function getSkillById($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json(['error' => 'Valid ID required'], 400);
        }-

        $skill = Cache::remember("skill:{$id}", 3600, function () use ($id) {
            return Skill::active()->find($id, ['id', 'name', 'group_code', 'worker_type']);
        });

        if (!$skill) {
            return response()->json(['error' => 'Skill not found'], 404);
        }

        return response()->json([
            'id' => $skill->id,
            'text' => $skill->name, // Select2 uchun
            'name' => $skill->name,
            'group_code' => $skill->group_code,
            'worker_type' => $skill->worker_type,
        ]);
    }

    public function getSkillsByIds(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:skills,id'
        ]);

        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json([]);
        }

        $skills = Skill::active()
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'group_code', 'worker_type'])
            ->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'text' => $skill->name,
                    'name' => $skill->name,
                    'group_code' => $skill->group_code,
                    'worker_type' => $skill->worker_type,
                ];
            });

        return response()->json($skills);
    }

    public function clearSkillsCache()
    {
        try {
            // Cache pattern bo'yicha tozalash
            $pattern = 'skills_search:*';
            
            $redis = \Illuminate\Support\Facades\Redis::connection();
            $keys = $redis->keys($pattern);
            
            if (!empty($keys)) {
                $redis->del($keys);
            }

            return response()->json(['message' => 'Cache cleared', 'cleared_keys' => count($keys)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cache clear failed: ' . $e->getMessage()], 500);
        }
    }

    // Debug uchun test metodi
    public function testSearch(Request $request)
    {
        $query = $request->get('q', 'hisobchi');
        
        $skill = new Skill();
        
        $testResults = [
            'original_query' => $query,
            'normalized' => $skill->normalizeSearchTerm($query),
            'cyrillic' => $skill->latinToCyrillic($query),
            'script_detected' => $skill->detectScript($query),
        ];

        // Test qidiruvi
        $results = Skill::active()->search($query)->limit(10)->get();
        
        $testResults['search_results'] = $results->map(function($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'name_normalized' => $skill->name_normalized,
                'worker_type' => $skill->worker_type
            ];
        });

        // Expanded search test
        $expandedResults = $this->expandedSearch($query, null, 10);
        $testResults['expanded_search_results'] = $expandedResults->map(function($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'name_normalized' => $skill->name_normalized
            ];
        });

        return response()->json($testResults);
    }
}