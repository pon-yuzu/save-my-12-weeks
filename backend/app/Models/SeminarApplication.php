<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'age_group',
        'occupation',
        'occupation_other',
        'referral_source',
        'referral_other',
        'has_canceled_plans',
        'cancel_reason',
        'twelve_weeks_dream',
        'questions',
    ];

    public const AGE_GROUPS = [
        '20s' => '20代',
        '30s' => '30代',
        '40s' => '40代',
        '50s_plus' => '50代以上',
        'prefer_not' => '答えたくない',
    ];

    public const OCCUPATIONS = [
        'employee' => '会社員',
        'self_employed' => '自営業・フリーランス',
        'part_time' => 'パート・アルバイト',
        'homemaker' => '主婦・主夫',
        'student' => '学生',
        'prefer_not' => '答えたくない',
        'other' => 'その他',
    ];

    public const REFERRAL_SOURCES = [
        'sayaka_direct' => 'Sayakaから直接',
        'friend' => '友人・知人の紹介',
        'instagram' => 'Instagram',
        'x_twitter' => 'X（Twitter）',
        'threads' => 'Threads',
        'facebook' => 'Facebook',
        'note' => 'note',
        'other' => 'その他',
    ];

    public const HAS_CANCELED_OPTIONS = [
        'yes' => 'ある',
        'no' => 'ない',
        'dont_remember' => '覚えていない',
    ];

    public function getAgeGroupLabelAttribute(): string
    {
        return self::AGE_GROUPS[$this->age_group] ?? $this->age_group;
    }

    public function getOccupationLabelAttribute(): string
    {
        if ($this->occupation === 'other' && $this->occupation_other) {
            return $this->occupation_other;
        }
        return self::OCCUPATIONS[$this->occupation] ?? $this->occupation;
    }

    public function getReferralSourceLabelAttribute(): string
    {
        if ($this->referral_source === 'other' && $this->referral_other) {
            return $this->referral_other;
        }
        return self::REFERRAL_SOURCES[$this->referral_source] ?? $this->referral_source;
    }

    public function getHasCanceledPlansLabelAttribute(): string
    {
        return self::HAS_CANCELED_OPTIONS[$this->has_canceled_plans] ?? $this->has_canceled_plans;
    }
}
