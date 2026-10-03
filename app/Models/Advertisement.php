<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'position',
        'ad_type',
        'image_url',
        'target_url',
        'ad_code',
        'start_date',
        'end_date',
        'is_active',
        'impressions_count',
        'clicks_count',
        'paragraph_position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'impressions_count' => 'integer',
        'clicks_count' => 'integer',
        'paragraph_position' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }

    public function scopeForPosition(Builder $query, string $position): Builder
    {
        return $query->active()->where('position', $position);
    }

    public function recordImpression(): void
    {
        $this->increment('impressions_count');
    }

    public function recordClick(): void
    {
        $this->increment('clicks_count');
    }
}
