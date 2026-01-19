<?php
// app/Services/SkillSearchService.php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class SkillSearchService
{
    public function search($query, $workerType = null, $limit = 20)
    {
        $normalizedQuery = $this->normalizeSearchTerm($query);
        
        $skillsQuery = Skill::active()
            ->select(['id', 'name', 'group_code', 'worker_type'])
            ->where(function ($q) use ($query, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('name_normalized', 'LIKE', "%{$normalizedQuery}%");
            })
            ->when($workerType, function ($q) use ($workerType) {
                return $q->byWorkerType($workerType);
            })
            ->orderByRaw("
                CASE 
                    WHEN name LIKE ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN name_normalized LIKE ? THEN 3
                    WHEN name_normalized LIKE ? THEN 4
                    ELSE 5
                END
            ", [
                "{$query}%",
                "%{$query}%", 
                "{$normalizedQuery}%",
                "%{$normalizedQuery}%"
            ])
            ->limit($limit);

        return $skillsQuery->get()->map(function ($skill) {
            return [
                'id' => $skill->id,
                'text' => $skill->name, // Select2 uchun
                'name' => $skill->name,
                'group_code' => $skill->group_code,
                'worker_type' => $skill->worker_type,
            ];
        });
    }

    protected function normalizeSearchTerm($term)
    {
        $term = mb_strtolower($term, 'UTF-8');
        
        // Kirill -> Lotin transliteratsiya
        $transliteration = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ў' => 'o\'', 'қ' => 'q',
            'ғ' => 'g\'', 'ҳ' => 'h'
        ];
        
        return strtr($term, $transliteration);
    }
}
