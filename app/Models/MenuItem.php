<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'type',
        'category_id',
        'page_id',
        'parent_id',
        'order',
        'target',
        'badge',
        'badge_color',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function activeChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('is_active', true)->orderBy('order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function getFinalUrlAttribute(): string
    {
        if ($this->type === 'category' && $this->category) {
            return route('category.show', $this->category->slug);
        }

        if ($this->type === 'page' && $this->page) {
            return route('page.show', $this->page->slug);
        }

        if (empty($this->url) || $this->url === '#') {
            return '#';
        }

        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }

        if (str_starts_with($this->url, '/')) {
            return url($this->url);
        }

        return url('/'.$this->url);
    }

    public function isDropdown(): bool
    {
        return $this->children()->exists();
    }
}
