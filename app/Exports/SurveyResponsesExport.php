<?php
// app/Exports/SurveyResponsesExport.php

namespace App\Exports;

use App\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyResponsesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return SurveyResponse::with([
            'region', 
            'district', 
            'activityType',
            'missingSkillsList',
            'futureDemandSkillsList'
        ])
        ->when($this->filters['region_id'] ?? null, fn($q) => $q->byRegion($this->filters['region_id']))
        ->when($this->filters['district_id'] ?? null, fn($q) => $q->byDistrict($this->filters['district_id']))
        ->when($this->filters['activity_type_id'] ?? null, fn($q) => $q->byActivityType($this->filters['activity_type_id']))
        ->byPeriod($this->filters['year'] ?? null, $this->filters['quarter'] ?? null)
        ->latest()
        ->get();
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
            'Kelajakda talab oshadigan kadrlar soni',
            'Kelajakda talab oshadigan kadrlar ro\'yxati',
            'Yaratilgan sana',
        ];
    }

    public function map($response): array
    {
        // Yetishmayotgan kadrlar ro'yxati
        $missingSkills = $response->missingSkillsList
            ->pluck('name')
            ->filter()
            ->join('; ');
        
        if (empty($missingSkills)) {
            $missingSkills = 'Mavjud emas';
        }
            
        // Kelajakda talab oshadigan kadrlar ro'yxati
        $futureDemandSkills = $response->futureDemandSkillsList
            ->pluck('name')
            ->filter()
            ->join('; ');
        
        if (empty($futureDemandSkills)) {
            $futureDemandSkills = 'Mavjud emas';
        }

        return [
            $response->id,
            $response->region ? $response->region->getName() : 'N/A',
            $response->organizational_legal_form === 'davlat' ? 'Davlat ташкилоти' : ($response->organizational_legal_form === 'xususiy' ? 'Xususiy корхона' : 'N/A'),
            $response->district ? $response->district->getName() : 'N/A',
            $response->activityType ? $response->activityType->getName() : 'N/A',
            $response->company_name ?? 'N/A',
            $response->employee_count ?? 0,
            $response->getHeadcountChangeText(),
            $response->missingSkillsList->count(),
            $missingSkills,
            $response->futureDemandSkillsList->count(),
            $futureDemandSkills,
            $response->created_at ? $response->created_at->format('d.m.Y H:i') : 'N/A',
        ];
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