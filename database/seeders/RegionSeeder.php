<?php
// database/seeders/RegionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name_uz' => 'Qoraqalpog\'iston Respublikasi', 'name_ru' => 'Қорақалпоғистон Республикаси', 'code' => 'QR'],
            ['name_uz' => 'Andijon viloyati', 'name_ru' => 'Андижон вилояти', 'code' => 'AN'],
            ['name_uz' => 'Buxoro viloyati', 'name_ru' => 'Бухоро вилояти', 'code' => 'BU'],
            ['name_uz' => 'Jizzax viloyati', 'name_ru' => 'Жиззах вилояти', 'code' => 'JI'],
            ['name_uz' => 'Qashqadaryo viloyati', 'name_ru' => 'Қашқадарё вилояти', 'code' => 'QA'],
            ['name_uz' => 'Navoiy viloyati', 'name_ru' => 'Навоий вилояти', 'code' => 'NV'],
            ['name_uz' => 'Namangan viloyati', 'name_ru' => 'Наманган вилояти', 'code' => 'NM'],
            ['name_uz' => 'Samarqand viloyati', 'name_ru' => 'Самарқанд вилояти', 'code' => 'SM'],
            ['name_uz' => 'Surxondaryo viloyati', 'name_ru' => 'Сурхондарё вилояти', 'code' => 'SU'],
            ['name_uz' => 'Sirdaryo viloyati', 'name_ru' => 'Сирдарё вилояти', 'code' => 'SI'],
            ['name_uz' => 'Toshkent viloyati', 'name_ru' => 'Тошкент вилояти', 'code' => 'TO'],
            ['name_uz' => 'Farg\'ona viloyati', 'name_ru' => 'Фарғона вилояти', 'code' => 'FA'],
            ['name_uz' => 'Xorazm viloyati', 'name_ru' => 'Хоразм вилояти', 'code' => 'XO'],
            ['name_uz' => 'Toshkent shahri', 'name_ru' => 'Тошкент шаҳри', 'code' => 'TS'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}