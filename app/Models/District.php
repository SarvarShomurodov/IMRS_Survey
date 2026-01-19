<?php
// app/Models/District.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    public function getName($locale = 'uz')
    {
        return $locale === 'ru' && $this->name_ru 
            ? $this->name_ru 
            : $this->name_uz;
    }

    public function getFullName($locale = 'uz')
    {
        return $this->region->getName($locale) . ', ' . $this->getName($locale);
    }
}