<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'subcategory_id',
        'author_id',
        'editor_id',
        'thumbnail',
        'thumbnail_caption',
        'gallery',
        'status',
        'is_headline',
        'is_breaking',
        'is_editor_pick',
        'is_featured',
        'allow_comments',
        'published_at',
        'views_count',
        'unique_views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_headline' => 'boolean',
        'is_breaking' => 'boolean',
        'is_editor_pick' => 'boolean',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'unique_views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $base = Str::slug($article->title);
                $slug = $base;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$count++;
                }
                $article->slug = $slug;
            }
            if (empty($article->excerpt) && ! empty($article->content)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 160);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function articleViews(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeHeadline(Builder $query): Builder
    {
        return $query->published()->where('is_headline', true);
    }

    public function scopeBreaking(Builder $query): Builder
    {
        return $query->published()->where('is_breaking', true);
    }

    public function scopeEditorPick(Builder $query): Builder
    {
        return $query->published()->where('is_editor_pick', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopePopular(Builder $query, int $days = 7): Builder
    {
        return $query->published()
            ->where('published_at', '>=', now()->subDays($days))
            ->orderByDesc('views_count');
    }

    public function getEstimatedReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->content));
        $minutes = ceil($words / 200);

        return max(1, (int) $minutes);
    }

    public function getViewsAttribute(): int
    {
        return (int) $this->views_count;
    }

    public function getFormattedViewsAttribute(): string
    {
        if ($this->views_count >= 1000000) {
            return round($this->views_count / 1000000, 1).'M';
        }
        if ($this->views_count >= 1000) {
            return round($this->views_count / 1000, 1).'k';
        }

        return (string) $this->views_count;
    }
}
