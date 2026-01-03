<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_number',
        'subject',
        'body',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(MailDelivery::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, int $day)
    {
        return $query->where('day_number', $day);
    }
}
