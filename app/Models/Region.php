<?php
// app/Models/Region.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz',
        'name_ru',
        'code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function districts()
    {
        return $this->hasMany(District::class)->where('is_active', true);
    }

    public function allDistricts()
    {
        return $this->hasMany(District::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getName($locale = 'uz')
    {
        return $locale === 'ru' && $this->name_ru 
            ? $this->name_ru 
            : $this->name_uz;
    }
}