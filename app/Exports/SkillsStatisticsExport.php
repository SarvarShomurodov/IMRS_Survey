<?php
// app/Exports/SkillsStatisticsExport.php

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

class SkillsStatisticsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
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
            'Корхоналар сони',
            'Умумий талаб',
            'Умумий ўрта таълим',
            'Ўрта махсус таълим',
            'Олий таълим',
            'Тажриба керак эмас',
            '1-2 йил тажриба',
            '3-5 йил тажриба',
            '5+ йил тажриба',
            'Эркак',
            'Аёл',
            'Жинс фарқи йўқ'
        ];
    }

    public function map($skill): array
    {
        static $index = 0;
        $index++;
        
        return [
            $index,
            $skill->skill_name ?? $skill->name ?? 'N/A',
            $skill->group_code ?? 'N/A',
            $skill->worker_type ?? 'Умумий',
            $skill->responses_count ?? $skill->count ?? 0,
            $skill->total_required ?? $skill->total_expected ?? 0,
            $skill->edu_umumiy_orta ?? 0,
            $skill->edu_orta_maxsus ?? 0,
            $skill->edu_oliy ?? 0,
            $skill->exp_0 ?? 0,
            $skill->exp_1_2 ?? 0,
            $skill->exp_3_5 ?? 0,
            $skill->exp_5_plus ?? 0,
            $skill->gender_male ?? 0,
            $skill->gender_female ?? 0,
            $skill->gender_any ?? 0
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header styles
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $this->type === 'missing' ? 'FF6B35' : '17A2B8']
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
        $title = $this->type === 'missing' ? 'Етишмаётган кадрлар' : 'Келажакдаги талаб';
        
        if (!empty($this->filters['year'])) {
            $title .= ' - ' . $this->filters['year'];
        }
        
        if (!empty($this->filters['quarter'])) {
            $title .= ' (Q' . $this->filters['quarter'] . ')';
        }
        
        return $title;
    }
}

