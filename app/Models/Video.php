<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'video_type',
        'category_id',
        'author_id',
        'duration',
        'views',
        'is_featured',
        'is_short',
        'show_homepage',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_short' => 'boolean',
        'show_homepage' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Video $video) {
            if (empty($video->slug)) {
                $base = Str::slug($video->title);
                $slug = $base;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $video->id ?? 0)->exists()) {
                    $slug = $base.'-'.$count++;
                }
                $video->slug = $slug;
            }

            // Auto-extract youtube_id if empty or changed
            if (! empty($video->youtube_url) && empty($video->youtube_id)) {
                $video->youtube_id = static::extractYouTubeId($video->youtube_url);
            }

            // Check if shorts
            if (str_contains($video->youtube_url, 'shorts') || $video->video_type === 'shorts') {
                $video->is_short = true;
                $video->video_type = 'shorts';
            }

            // Fallback thumbnail if empty
            if (empty($video->thumbnail) && ! empty($video->youtube_id)) {
                $video->thumbnail = "https://img.youtube.com/vi/{$video->youtube_id}/hqdefault.jpg";
            }
        });
    }

    public static function extractYouTubeId(string $url): string
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|shorts/)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }

        return '';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeShorts(Builder $query): Builder
    {
        return $query->published()->where(function ($q) {
            $q->where('is_short', true)->orWhere('video_type', 'shorts');
        });
    }

    public function scopeStandard(Builder $query): Builder
    {
        return $query->published()->where(function ($q) {
            $q->where('is_short', false)->where('video_type', 'video');
        });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopeHomepage(Builder $query): Builder
    {
        return $query->published()->where('show_homepage', true);
    }

    public function getEmbedUrlAttribute(): string
    {
        return "https://www.youtube-nocookie.com/embed/{$this->youtube_id}?rel=0";
    }

    public function getFormattedViewsAttribute(): string
    {
        if ($this->views >= 1000000) {
            return round($this->views / 1000000, 1).'M';
        }
        if ($this->views >= 1000) {
            return round($this->views / 1000, 1).'k';
        }

        return (string) $this->views;
    }
}
