<?php
// app/Exports/DetailedCompanySkillsExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DetailedCompanySkillsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $skills;
    protected $type;
    protected $filters;

    public function __construct($skills, $type = 'missing', $filters = [])
    {
        $this->skills = $skills;
        $this->type = $type;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->skills;
    }

    public function headings(): array
    {
        return [
            '№',
            'Касб номи',
            'Гуруҳ коди',
            'Ишчи тури',
            'Таълим даражаси',
            'Иш тажрибаси',
            'Жинс талаби',
            'Корхона номи',
            'Вилоят',
            'Туман',
            'Фаолият тури',
            'Ходимлар сони',
            'Санаси'
        ];
    }

    public function map($skill): array
    {
        static $index = 0;
        $index++;
        
        // Taълim darajasi matnini olish
        $educationLevels = [
            'umumiy_orta' => 'Умумий ўрта',
            'orta_maxsus' => 'Ўрта махсус',
            'oliy' => 'Олий таълим'
        ];
        
        // Tajriba darajasi matnini olish
        $experienceLevels = [
            '0' => 'Тажриба керак эмас',
            '1-2' => '1-2 йил',
            '3-5' => '3-5 йил',
            '5+' => '5+ йил'
        ];
        
        // Jins talabi matnini olish
        $genderPreferences = [
            'erkak' => 'Эркак',
            'ayol' => 'Аёл',
            'farq_qilmaydi' => 'Фарқ қилмайди'
        ];
        
        return [
            $index,
            $skill->skill_name ?? 'N/A',
            $skill->group_code ?? 'N/A',
            $skill->worker_type ?? 'Умумий',
            $educationLevels[$skill->education_level] ?? $skill->education_level ?? 'N/A',
            $experienceLevels[$skill->experience_level] ?? $skill->experience_level ?? 'N/A',
            $genderPreferences[$skill->gender_preference] ?? $skill->gender_preference ?? 'N/A',
            $skill->company_name ?? 'N/A',
            $skill->region_name ?? 'N/A',
            $skill->district_name ?? 'N/A',
            $skill->activity_type ?? 'N/A',
            $skill->employee_count ?? 0,
            $skill->created_at ? $skill->created_at->format('d.m.Y H:i') : 'N/A'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $this->type === 'missing' ? 'DC3545' : '17A2B8']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
        ];
    }

    public function title(): string
    {
        $title = $this->type === 'missing' ? 'Етишмаётган кадрлар (батафсил)' : 'Келажакдаги талаб (батафсил)';
        
        if (!empty($this->filters['year'])) {
            $title .= ' - ' . $this->filters['year'];
        }
        
        return $title;
    }
}