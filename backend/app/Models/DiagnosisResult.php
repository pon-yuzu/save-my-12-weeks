<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'health_score',
        'mind_score',
        'money_score',
        'career_score',
        'time_score',
        'living_score',
        'relationships_score',
        'vision_score',
        'selected_areas',
        'free_text',
    ];

    protected function casts(): array
    {
        return [
            'health_score' => 'integer',
            'mind_score' => 'integer',
            'money_score' => 'integer',
            'career_score' => 'integer',
            'time_score' => 'integer',
            'living_score' => 'integer',
            'relationships_score' => 'integer',
            'vision_score' => 'integer',
            'selected_areas' => 'array',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(MailSubscription::class, 'subscription_id');
    }

    public function getTotalScoreAttribute(): int
    {
        return $this->health_score + $this->mind_score + $this->money_score +
               $this->career_score + $this->time_score + $this->living_score +
               $this->relationships_score + $this->vision_score;
    }

    public function getAverageScoreAttribute(): float
    {
        return $this->total_score / 8;
    }
}
