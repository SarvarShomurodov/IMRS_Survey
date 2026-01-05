<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SurveyResponse;

class SurveyResponseSeeder extends Seeder
{
    public function run()
    {
        $responses = [];

        // region_id ga mos district_id diapazonlari
        $regionDistricts = [
            1 => [1, 15],
            2 => [16, 30],
            3 => [31, 42],
            4 => [43, 55],
            5 => [56, 69],
            6 => [70, 80],
            7 => [81, 92],
            8 => [93, 106],
            9 => [107, 120],
            10 => [121, 129],
            11 => [130, 144],
            12 => [145, 159],
            13 => [160, 171],
            14 => [172, 183],
        ];

        $options = ['oshdi', 'ozgarmadi', 'kamaydi'];

        for ($i = 0; $i < 400000; $i++) {
            $regionId = rand(1, 14);
            $districtRange = $regionDistricts[$regionId];
            $districtId = rand($districtRange[0], $districtRange[1]);

            $responses[] = [
                'user_id'               => null,
                'respondent_name'       => null,
                'respondent_email'      => null,
                'region_id'             => $regionId,
                'district_id'           => $districtId,
                'activity_type_id'      => rand(1, 12),
                'company_name'          => fake()->company(),
                'company_address'       => null,
                'employee_count'        => rand(10, 90),
                'headcount_change'      => $options[array_rand($options)],
                'survey_period_year'    => 2025,
                'survey_period_quarter' => 3,
                'additional_data' => json_encode([
                    'comment' => fake()->sentence(),
                    'rating'  => rand(1, 5),
                ]),
                'ip_address'            => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ];

            // batch insert
            if ($i % 1000 === 0 && $i > 0) {
                SurveyResponse::insert($responses);
                $responses = [];
            }
        }

        if (!empty($responses)) {
            SurveyResponse::insert($responses);
        }
    }
}
