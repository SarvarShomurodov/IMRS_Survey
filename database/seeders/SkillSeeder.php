<?php
// database/seeders/SkillSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Facades\File;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // CSV fayldan o'qish usuli (12k+ ma'lumot uchun)
        $csvFile = database_path('seeders/data/skills.csv');
        
        if (File::exists($csvFile)) {
            $this->seedFromCsv($csvFile);
        } else {
            $this->seedSampleData();
        }
    }
    
    protected function seedFromCsv($csvFile)
    {
        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle); // Header o'tkazib yuborish
        
        $batch = [];
        $batchSize = 1000;
        
        while (($data = fgetcsv($handle)) !== false) {
            $skill = [
                'sequence_number' => (int)$data[0],
                'group_code' => $data[1],
                'name' => $data[2],
                'name_normalized' => $this->normalizeText($data[2]),
                'worker_type' => $data[3], // xizmatchi/ishchi
                'qualification_category' => $data[4] ?? null,
                'skill_grade_range' => $data[5] ?? null,
                'national_qualification_level' => !empty($data[6]) ? (int)$data[6] : null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $batch[] = $skill;
            
            if (count($batch) >= $batchSize) {
                Skill::insert($batch);
                $batch = [];
            }
        }
        
        // Qolgan ma'lumotlarni saqlash
        if (!empty($batch)) {
            Skill::insert($batch);
        }
        
        fclose($handle);
    }
    
    protected function seedSampleData()
    {
        // Sample data - haqiqiy loyihada CSV/Excel dan yuklanadi
        $skills = [
            [
                'sequence_number' => 102,
                'group_code' => '11130001',
                'name' => 'Маҳаллий ўзини-ўзи бошқариш органи (маҳалла, шаҳарча, қишлоқ, овул фуқаролар йиғини) раиси',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'B',
                'national_qualification_level' => 6,
            ],
            [
                'sequence_number' => 103,
                'group_code' => '11130002',
                'name' => 'Қорақалпоғистон Республикаси, Тошкент шаҳри, вилоятлар туманлари ва шаҳарлар ҳокимлари ташкилий-кадрлар гуруҳ раҳбари',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'B',
                'national_qualification_level' => 6,
            ],
            [
                'sequence_number' => 105,
                'group_code' => '11130004',
                'name' => 'Тошкент шаҳри, вилоятлар ва Республика бўйсунувидаги шаҳарлар ҳокимлари ташкилий-кадрлар гуруҳ раҳбари',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'B',
                'national_qualification_level' => 6,
            ],
            [
                'sequence_number' => 200,
                'group_code' => '21110001',
                'name' => 'Бухгалтер',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'A',
                'national_qualification_level' => 5,
            ],
            [
                'sequence_number' => 201,
                'group_code' => '21110002',
                'name' => 'Молия мутахассиси',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'A',
                'national_qualification_level' => 5,
            ],
            [
                'sequence_number' => 300,
                'group_code' => '31200001',
                'name' => 'Дастурчи',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'A',
                'national_qualification_level' => 6,
            ],
            [
                'sequence_number' => 301,
                'group_code' => '31200002',
                'name' => 'Тизим администратори',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'A',
                'national_qualification_level' => 6,
            ],
            [
                'sequence_number' => 400,
                'group_code' => '41100001',
                'name' => 'Офис ходими',
                'worker_type' => 'xizmatchi',
                'qualification_category' => 'C',
                'national_qualification_level' => 4,
            ],
            [
                'sequence_number' => 500,
                'group_code' => '51200001',
                'name' => 'Сотувчи',
                'worker_type' => 'ishchi',
                'qualification_category' => 'C',
                'national_qualification_level' => 3,
            ],
            [
                'sequence_number' => 600,
                'group_code' => '61100001',
                'name' => 'Фермер',
                'worker_type' => 'ishchi',
                'qualification_category' => 'C',
                'national_qualification_level' => 3,
            ],
        ];

        foreach ($skills as $skillData) {
            $skillData['name_normalized'] = $this->normalizeText($skillData['name']);
            $skillData['is_active'] = true;
            
            Skill::create($skillData);
        }
    }
    
    protected function normalizeText($text)
    {
        $text = mb_strtolower($text, 'UTF-8');
        
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
        
        return strtr($text, $transliteration);
    }
}