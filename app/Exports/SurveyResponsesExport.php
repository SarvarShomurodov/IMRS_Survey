<?php
// app/Exports/SurveyResponsesExport.php

namespace App\Exports;

use App\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyResponsesExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        // Memory va timeout limitlarini oshirish
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 1800);
        set_time_limit(1800);
        
        $this->filters = $filters;
    }

    public function query()
    {
        return SurveyResponse::query()
            ->select([
                'id',
                'region_id',
                'district_id',
                'activity_type_id',
                'company_name',
                'employee_count',
                'organizational_legal_form',
                'headcount_change',
                'created_at'
            ])
            ->with([
                'region',
                'district',
                'activityType',
                'missingSkills.skill',
                'futureDemandSkills.skill'
            ])
            ->when($this->filters['region_id'] ?? null, fn($q) => $q->byRegion($this->filters['region_id']))
            ->when($this->filters['district_id'] ?? null, fn($q) => $q->byDistrict($this->filters['district_id']))
            ->when($this->filters['activity_type_id'] ?? null, fn($q) => $q->byActivityType($this->filters['activity_type_id']))
            ->byPeriod($this->filters['year'] ?? null, $this->filters['quarter'] ?? null)
            ->orderBy('id', 'desc');
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Viloyat',
            'Корхона/ташкилот ташкилий-ҳуқуқий шакли',
            'Tuman',
            'Faoliyat turi',
            'Korxona nomi',
            'Xodimlar soni',
            'Xodimlar soni o\'zgarishi',
            'Yetishmayotgan kadrlar soni',
            'Yetishmayotgan kadrlar ro\'yxati',
            'Ta\'lim darajasi (Yetishmayotgan)',
            'Tajriba darajasi (Yetishmayotgan)',
            'Jins talabi (Yetishmayotgan)',
            'Kelajakda talab oshadigan kadrlar soni',
            'Kelajakda talab oshadigan kadrlar ro\'yxati',
            'Ta\'lim darajasi (Kelajak)',
            'Tajriba darajasi (Kelajak)',
            'Jins talabi (Kelajak)',
            'Yaratilgan sana',
        ];
    }

    public function map($response): array
    {
        $missingSkillsData = $this->formatSkillsWithDetails($response->missingSkills);
        $futureSkillsData = $this->formatSkillsWithDetails($response->futureDemandSkills);

        return [
            $response->id,
            $this->getRegionName($response),
            $this->formatOrganizationalForm($response->organizational_legal_form),
            $this->getDistrictName($response),
            $this->getActivityTypeName($response),
            $response->company_name ?? 'N/A',
            $response->employee_count ?? 0,
            $response->getHeadcountChangeText(),
            $response->missingSkills->count(),
            $missingSkillsData['names'],
            $missingSkillsData['education'],
            $missingSkillsData['experience'],
            $missingSkillsData['gender'],
            $response->futureDemandSkills->count(),
            $futureSkillsData['names'],
            $futureSkillsData['education'],
            $futureSkillsData['experience'],
            $futureSkillsData['gender'],
            $response->created_at ? $response->created_at->format('d.m.Y H:i') : 'N/A',
        ];
    }

    private function getRegionName($response)
    {
        if (!$response->region) {
            return 'N/A';
        }
        
        if (method_exists($response->region, 'getName')) {
            return $response->region->getName();
        }
        
        return $response->region->name_uz ?? 'N/A';
    }

    private function getDistrictName($response)
    {
        if (!$response->district) {
            return 'N/A';
        }
        
        if (method_exists($response->district, 'getName')) {
            return $response->district->getName();
        }
        
        return $response->district->name_uz ?? 'N/A';
    }

    private function getActivityTypeName($response)
    {
        if (!$response->activityType) {
            return 'N/A';
        }
        
        if (method_exists($response->activityType, 'getName')) {
            return $response->activityType->getName();
        }
        
        return $response->activityType->name_uz ?? 'N/A';
    }

    private function formatSkillsWithDetails($skills)
    {
        if (!$skills || $skills->isEmpty()) {
            return [
                'names' => 'Mavjud emas',
                'education' => 'N/A',
                'experience' => 'N/A',
                'gender' => 'N/A'
            ];
        }

        $names = [];
        $educations = [];
        $experiences = [];
        $genders = [];

        foreach ($skills as $skillRecord) {
            if ($skillRecord->skill) {
                $skillName = method_exists($skillRecord->skill, 'getName') 
                    ? $skillRecord->skill->getName() 
                    : ($skillRecord->skill->name_uz ?? 'N/A');
                $names[] = $skillName;
            }
            
            if (!empty($skillRecord->education_level)) {
                $educations[] = $this->formatEducationLevel($skillRecord->education_level);
            }
            
            if (!empty($skillRecord->experience_level)) {
                $experiences[] = $this->formatExperienceLevel($skillRecord->experience_level);
            }
            
            if (!empty($skillRecord->gender_preference)) {
                $genders[] = $this->formatGenderPreference($skillRecord->gender_preference);
            }
        }

        return [
            'names' => !empty($names) ? implode('; ', $names) : 'N/A',
            'education' => !empty($educations) ? implode('; ', $educations) : 'N/A',
            'experience' => !empty($experiences) ? implode('; ', $experiences) : 'N/A',
            'gender' => !empty($genders) ? implode('; ', $genders) : 'N/A'
        ];
    }

    private function formatOrganizationalForm($form)
    {
        $forms = [
            'davlat' => 'Davlat tashkiloti',
            'xususiy' => 'Xususiy korxona'
        ];
        
        return $forms[$form] ?? 'N/A';
    }

    private function formatEducationLevel($level)
    {
        $levels = [
            'umumiy_orta' => 'Umumiy o\'rta',
            'orta_maxsus' => 'O\'rta maxsus / professional kollej',
            'oliy' => 'Oliy'
        ];
        
        return $levels[$level] ?? $level;
    }

    private function formatExperienceLevel($level)
    {
        $levels = [
            '0' => 'Tajriba talab qilinmaydi',
            '1-2' => '1 - 2 yil',
            '3-5' => '3 - 5 yil',
            '5+' => '5 yildan ko\'p'
        ];
        
        return $levels[$level] ?? $level;
    }

    private function formatGenderPreference($gender)
    {
        $genders = [
            'erkak' => 'Erkak',
            'ayol' => 'Ayol',
            'farq_qilmaydi' => 'Farq qilmaydi'
        ];
        
        return $genders[$gender] ?? $gender;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '366092']
                ]
            ]
        ];
    }
}
