<?php
// app/Models/ResponseMissingSkill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseMissingSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_response_id',
        'skill_id',
        'required_count',
        'education_level',
        'experience_level',
        'gender_preference',
    ];

    protected $casts = [
        'required_count' => 'integer',
    ];

    // Relationships
    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponse::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    // Accessor methods
    public function getEducationLevelText()
    {
        $levels = [
            'umumiy_orta' => 'Умумий ўрта',
            'orta_maxsus' => 'Ўрта махсус / профессионал коллеж',
            'oliy' => 'Олий',
        ];

        return $levels[$this->education_level] ?? $this->education_level;
    }

    public function getExperienceLevelText()
    {
        $levels = [
            '0' => 'Тажриба талаб қилинмайди',
            '1-2' => '1 - 2 йил',
            '3-5' => '3 - 5 йил',
            '5+' => '5 йилдан кўп',
        ];

        return $levels[$this->experience_level] ?? $this->experience_level;
    }

    public function getGenderPreferenceText()
    {
        $genders = [
            'erkak' => 'Эркак',
            'ayol' => 'Аёл',
            'farq_qilmaydi' => 'Фарқ қилмайди',
        ];

        return $genders[$this->gender_preference] ?? $this->gender_preference;
    }
}



