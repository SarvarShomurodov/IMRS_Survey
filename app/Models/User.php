<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'is_admin',
        'last_survey_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_survey_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function latestSurveyResponse()
    {
        return $this->hasOne(SurveyResponse::class)->latest();
    }

    public function canTakeSurvey($year = null, $quarter = null)
    {
        $year = $year ?? date('Y');
        $quarter = $quarter ?? ceil(date('n') / 3);

        return !$this->surveyResponses()
            ->where('survey_period_year', $year)
            ->where('survey_period_quarter', $quarter)
            ->exists();
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function getAvatarUrl()
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}