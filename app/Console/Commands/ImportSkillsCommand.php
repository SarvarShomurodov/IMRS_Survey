<?php
// app/Console/Commands/ImportSkillsCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class ImportSkillsCommand extends Command
{
    protected $signature = 'import:skills {file}';
    protected $description = 'Import skills from CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info('Starting skills import...');
        
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // Skip header
        
        $batch = [];
        $batchSize = 1000;
        $count = 0;

        DB::beginTransaction();
        
        try {
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
                $count++;
                
                if (count($batch) >= $batchSize) {
                    Skill::insert($batch);
                    $batch = [];
                    $this->info("Imported {$count} skills...");
                }
            }
            
            // Insert remaining skills
            if (!empty($batch)) {
                Skill::insert($batch);
            }
            
            DB::commit();
            $this->info("Successfully imported {$count} skills!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            return 1;
        }
        
        fclose($handle);
        return 0;
    }

    protected function normalizeText($text)
    {
        $text = mb_strtolower($text, 'UTF-8');
        
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

