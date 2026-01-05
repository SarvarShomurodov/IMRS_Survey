<?php
// database/seeders/ActivityTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityType;

class ActivityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $activityTypes = [
            ['name_uz' => 'Qishloq, o\'rmon va baliq xo\'jaligi', 'name_ru' => 'Қишлоқ, ўрмон ва балиқ хўжалиги', 'code' => 'AGR'],
            ['name_uz' => 'Tog\'-kon sanoati va ochiq konlarni ishlash', 'name_ru' => 'Тоғ-кон саноати ва очиқ конларни ишлаш', 'code' => 'MIN'],
            ['name_uz' => 'Ishlab chiqarish sanoati', 'name_ru' => 'Ишлаб чиқариш саноати', 'code' => 'MAN'],
            ['name_uz' => 'Elektr, gaz, bug\' bilan ta\'minlash va havoni konditsiyalash', 'name_ru' => 'Электр, газ, буғ билан таъминлаш ва ҳавони кондициялаш', 'code' => 'ELE'],
            ['name_uz' => 'Suv bilan ta\'minlash; kanalizatsiya tizimi, chiqindilarni yig\'ish va utilizatsiya qilish', 'name_ru' => 'Сув билан таъминлаш; канализация тизими, чиқиндиларни йиғиш ва утилизация қилиш', 'code' => 'WAT'],
            ['name_uz' => 'Qurilish', 'name_ru' => 'Қурилиш', 'code' => 'CON'],
            ['name_uz' => 'Ulgurji va chakana savdo; motorli transport vositalari va mototsikllarni ta\'mirlash', 'name_ru' => 'Улгуржи ва чакана савдо; моторли транспорт воситалари ва мотоциклларни таъмирлаш', 'code' => 'TRA'],
            ['name_uz' => 'Tashish va saqlash', 'name_ru' => 'Ташиш ва сақлаш', 'code' => 'TRS'],
            ['name_uz' => 'Yashash va ovqatlanish bo\'yicha xizmatlar', 'name_ru' => 'Яшаш ва овқатланиш бўйича хизматлар', 'code' => 'ACC'],
            ['name_uz' => 'Axborot va aloqa', 'name_ru' => 'Ахборот ва алоқа', 'code' => 'ICT'],
            ['name_uz' => 'Moliyaviy va sug\'urta faoliyati', 'name_ru' => 'Молиявий ва суғурта фаолияти', 'code' => 'FIN'],
            ['name_uz' => 'Ko\'chmas mulk bilan operatsiyalar', 'name_ru' => 'Кўчмас мулк билан операциялар', 'code' => 'REA'],
            ['name_uz' => 'Professional, ilmiy va texnik faoliyat', 'name_ru' => 'Профессионал, илмий ва техник фаолият', 'code' => 'PRO'],
            ['name_uz' => 'Boshqarish bo\'yicha faoliyat va yordamchi xizmatlar ko\'rsatish', 'name_ru' => 'Бошқариш бўйича фаолият ва ёрдамчи хизматлар кўрсатиш', 'code' => 'ADM'],
            ['name_uz' => 'Davlat boshqaruvi va mudofaa; majburiy ijtimoiy ta\'minot', 'name_ru' => 'Давлат бошқаруви ва мудофаа; мажбурий ижтимоий таъминот', 'code' => 'PUB'],
            ['name_uz' => 'Ta\'lim', 'name_ru' => 'Таълим', 'code' => 'EDU'],
            ['name_uz' => 'Sog\'liqni saqlash va ijtimoiy xizmatlar ko\'rsatish', 'name_ru' => 'Соғлиқни сақлаш ва ижтимоий хизматлар кўрсатиш', 'code' => 'HEA'],
            ['name_uz' => 'San\'at, ko\'ngil ochish va dam olish', 'name_ru' => 'Санъат, кўнгил очиш ва дам олиш', 'code' => 'ART'],
        ];

        foreach ($activityTypes as $activityType) {
            ActivityType::create($activityType);
        }
    }
}