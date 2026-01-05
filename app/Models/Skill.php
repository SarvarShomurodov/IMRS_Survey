<?php
// app/Models/Skill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence_number',
        'group_code',
        'name',
        'name_normalized',
        'worker_type',
        'qualification_category',
        'skill_grade_range',
        'national_qualification_level',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sequence_number' => 'integer',
            'national_qualification_level' => 'integer',
        ];
    }

    public function responseMissingSkills()
    {
        return $this->hasMany(ResponseMissingSkill::class);
    }

    public function responseFutureDemandSkills()
    {
        return $this->hasMany(ResponseFutureDemandSkill::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        $originalTerm = trim($term);
        $normalizedTerm = $this->normalizeSearchTerm($originalTerm);
        $cyrillicTerm = $this->latinToCyrillic($originalTerm);
        
        return $query->where(function ($q) use ($originalTerm, $normalizedTerm, $cyrillicTerm) {
            // Asl matnda qidirish
            $q->where('name', 'LIKE', "%{$originalTerm}%")
              ->orWhere('name_normalized', 'LIKE', "%{$normalizedTerm}%");
            
            // Agar lotin matn kiritilgan bo'lsa, kirill variantida ham qidirish
            if ($cyrillicTerm !== $originalTerm) {
                $q->orWhere('name', 'LIKE', "%{$cyrillicTerm}%");
            }
        });
    }

    public function scopeByWorkerType($query, $type)
    {
        if (!empty($type)) {
            return $query->where('worker_type', $type);
        }
        return $query;
    }

    public function normalizeSearchTerm($term)
    {
        $term = mb_strtolower($term, 'UTF-8');
        
        // Kirill -> Lotin transliteratsiya
        $transliteration = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ў' => 'o\'', 'қ' => 'q',
            'ғ' => 'g\'', 'ҳ' => 'h'
        ];
        
        return strtr($term, $transliteration);
    }

    public function latinToCyrillic($term)
    {
        $term = mb_strtolower($term, 'UTF-8');
        
        // Lotin -> Kirill transliteratsiya (teskari yo'nalish)
        $transliteration = [
            'a' => 'а', 'b' => 'б', 'v' => 'в', 'g' => 'г', 'd' => 'д',
            'e' => 'е', 'yo' => 'ё', 'j' => 'ж', 'z' => 'з', 'i' => 'и',
            'y' => 'й', 'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н',
            'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 't' => 'т',
            'u' => 'у', 'f' => 'ф', 'x' => 'х', 'ts' => 'ц', 'ch' => 'ч',
            'sh' => 'ш', 'sch' => 'щ', 'yu' => 'ю', 'ya' => 'я', 
            'o\'' => 'ў', 'q' => 'қ', 'g\'' => 'ғ', 'h' => 'ҳ'
        ];
        
        // Uzun kombinatsiyalarni birinchi navbatda almashtirish
        $longCombinations = ['sch', 'ch', 'sh', 'yo', 'yu', 'ya', 'ts', 'o\'', 'g\''];
        
        foreach ($longCombinations as $combination) {
            if (isset($transliteration[$combination])) {
                $term = str_replace($combination, $transliteration[$combination], $term);
            }
        }
        
        // Qolgan harflarni almashtirish
        foreach ($transliteration as $latin => $cyrillic) {
            if (strlen($latin) == 1) { // faqat bitta harfli kombinatsiyalar
                $term = str_replace($latin, $cyrillic, $term);
            }
        }
        
        return $term;
    }

    // Yordamchi metod - matnning qaysi alifboda ekanligini aniqlash
    public function detectScript($text)
    {
        // Kirill harflar diapazonlari
        $cyrillicPattern = '/[\x{0400}-\x{04FF}\x{0500}-\x{052F}]/u';
        
        if (preg_match($cyrillicPattern, $text)) {
            return 'cyrillic';
        }
        
        // Lotin harflar
        if (preg_match('/[a-zA-Z]/', $text)) {
            return 'latin';
        }
        
        return 'unknown';
    }

    public static function boot()
    {
        parent::boot();
        
        static::saving(function ($skill) {
            if ($skill->isDirty('name')) {
                $skill->name_normalized = $skill->normalizeSearchTerm($skill->name);
            }
        });
    }

    public function getMissingSkillsCount()
    {
        return $this->responseMissingSkills()->count();
    }

    public function getFutureDemandSkillsCount()
    {
        return $this->responseFutureDemandSkills()->count();
    }
    public function getName($locale = null)
    {
        // Agar sizning skill jadvalingizda faqat 'name' ustuni bo'lsa
        if (isset($this->attributes['name'])) {
            return $this->name;
        }
        
        // Agar ko'p tilli ustunlar bo'lsa
        if (!$locale) {
            $locale = app()->getLocale() ?? 'uz';
        }
        
        switch ($locale) {
            case 'ru':
                return $this->name_ru ?: ($this->name_uz ?? $this->name);
            case 'en':
                return $this->name_en ?: ($this->name_uz ?? $this->name);
            case 'uz':
            default:
                return $this->name_uz ?? $this->name;
        }
    }
}