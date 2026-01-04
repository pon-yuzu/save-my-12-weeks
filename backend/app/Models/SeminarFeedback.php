<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SeminarFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_application_id',
        'seminar_id',
        'overall_rating',
        'content_rating',
        'most_helpful',
        'improvement_suggestions',
        'interested_in_program',
        'interested_in_session',
        'questions',
        'token',
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'content_rating' => 'integer',
        'interested_in_program' => 'boolean',
        'interested_in_session' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(SeminarApplication::class, 'seminar_application_id');
    }

    public function seminar(): BelongsTo
    {
        return $this->belongsTo(Seminar::class);
    }

    /**
     * トークンを生成してフィードバックレコードを作成
     */
    public static function createForApplication(SeminarApplication $application): self
    {
        return self::create([
            'seminar_application_id' => $application->id,
            'seminar_id' => $application->seminar_id,
            'token' => Str::random(64),
        ]);
    }

    /**
     * アンケートURLを取得
     */
    public function getUrlAttribute(): string
    {
        return route('seminar.feedback.show', ['token' => $this->token]);
    }
}
