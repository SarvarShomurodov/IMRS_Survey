<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Region;

class TaxtachiDistrictSeeder extends Seeder
{
    public function run(): void
    {
        // region kodini o'zgartiring agar boshqacha bo'lsa
        $regionCode = 'SM'; // Samarqand viloyati kodini tekshiring
        $region = Region::where('code', $regionCode)->first();

        if (! $region) {
            $this->command->error("Region with code {$regionCode} not found. Seeder aborted.");
            return;
        }

        $data = [
            'region_id' => $region->id,
            'name_uz' => 'Taxtachi tumani',
            'name_ru' => 'Тахтачи тумани',
            'code' => 'SM015', // siz ilgari qo'ygan kod bilan mos kelsin
            'is_active' => true,
        ];

        // Duplicat bo'lmasligi uchun updateOrCreate yoki firstOrCreate ishlatamiz
        District::updateOrCreate(
            ['code' => $data['code']], // lookup
            $data // values to set (create or update)
        );

        $this->command->info("Taxtachi tumani qo'shildi yoki yangilandi (code: {$data['code']}).");
    }
}
