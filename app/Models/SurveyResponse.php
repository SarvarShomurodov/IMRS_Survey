<?php
// app/Models/SurveyResponse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'respondent_name',     // YANGI
        'respondent_email',    // YANGI
        'region_id',
        'district_id',
        'activity_type_id',
        'company_name',
        'company_address',
        'employee_count',
    	'organizational_legal_form',
        'headcount_change',
        'headcount_six_change',  // YANGI - 6 oylik prognoz
        'survey_period_year',
        'survey_period_quarter',
        'additional_data',
        'ip_address',          // YANGI
    ];

    protected function casts(): array
    {
        return [
            'employee_count' => 'integer',
            'survey_period_year' => 'integer',
            'survey_period_quarter' => 'integer',
            'additional_data' => 'array',
        ];
    }

    // User relationship - to'g'ri BelongsTo relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRespondentName()
    {
        return $this->respondent_name ?: 'Anonymous';
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    // Has Many relationships
    public function missingSkills()
    {
        return $this->hasMany(ResponseMissingSkill::class);
    }

    public function futureDemandSkills()
    {
        return $this->hasMany(ResponseFutureDemandSkill::class);
    }

    // Many-to-Many relationships with pivot data
    public function missingSkillsList(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'response_missing_skills', 'survey_response_id', 'skill_id')
                    ->withPivot('required_count')
                    ->withTimestamps();
    }

    public function futureDemandSkillsList(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'response_future_demand_skills', 'survey_response_id', 'skill_id')
                    ->withPivot('expected_count')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeByPeriod($query, $year = null, $quarter = null)
    {
        if ($year) {
            $query->where('survey_period_year', $year);
        }
        if ($quarter) {
            $query->where('survey_period_quarter', $quarter);
        }
        return $query;
    }

    public function scopeByRegion($query, $regionId)
    {
        if ($regionId) {
            return $query->where('region_id', $regionId);
        }
        return $query;
    }

    public function scopeByDistrict($query, $districtId)
    {
        if ($districtId) {
            return $query->where('district_id', $districtId);
        }
        return $query;
    }

    public function scopeByActivityType($query, $activityTypeId)
    {
        if ($activityTypeId) {
            return $query->where('activity_type_id', $activityTypeId);
        }
        return $query;
    }

    // Helper methods
    public function getHeadcountChangeText()
    {
        $changes = [
            'oshdi' => 'Oshdi',
            'ozgarmadi' => 'O\'zgarmadi',
            'kamaydi' => 'Kamaydi'
        ];

        return $changes[$this->headcount_change] ?? $this->headcount_change;
    }

    // YANGI - 6 oylik prognoz uchun helper method
    public function getHeadcountSixChangeText()
    {
        $changes = [
            'oshdi' => 'Oshadi',
            'ozgarmadi' => 'O\'zgarmasligi kutilmoqda',
            'kamaydi' => 'Kamayishi kutilmoqda'
        ];

        return $changes[$this->headcount_six_change] ?? $this->headcount_six_change;
    }

    public function getPeriodText()
    {
        return $this->survey_period_year . ' yil, ' . $this->survey_period_quarter . '-chorak';
    }

    // User ma'lumotlarini xavfsiz olish
    public function getUserName()
    {
        return $this->user ? $this->user->name : ($this->respondent_name ?: 'Anonymous');
    }

    public function getUserEmail()
    {
        return $this->user ? $this->user->email : ($this->respondent_email ?: 'N/A');
    }

    // YANGI - Ikkala headcount o'zgarish ma'lumotini birga olish
    public function getHeadcountChanges()
    {
        return [
            'current' => [
                'value' => $this->headcount_change,
                'text' => $this->getHeadcountChangeText()
            ],
            'six_month_forecast' => [
                'value' => $this->headcount_six_change,
                'text' => $this->getHeadcountSixChangeText()
            ]
        ];
    }

    // YANGI - Headcount trend tahlili
    public function getHeadcountTrend()
    {
        $current = $this->headcount_change;
        $forecast = $this->headcount_six_change;

        if ($current === 'oshdi' && $forecast === 'oshdi') {
            return 'Doimiy o\'sish';
        } elseif ($current === 'kamaydi' && $forecast === 'kamaydi') {
            return 'Doimiy kamayish';
        } elseif ($current === 'ozgarmadi' && $forecast === 'ozgarmadi') {
            return 'Barqaror holat';
        } elseif ($current === 'oshdi' && $forecast === 'kamaydi') {
            return 'Vaqtinchalik o\'sish';
        } elseif ($current === 'kamaydi' && $forecast === 'oshdi') {
            return 'Tiklanish kutilmoqda';
        } else {
            return 'O\'tish davri';
        }
    }
}