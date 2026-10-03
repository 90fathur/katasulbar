<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'bio',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function editedArticles(): HasMany
    {
        return $this->hasMany(Article::class, 'editor_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'author_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isPemred(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'pemred']);
    }

    public function isEditor(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'pemred', 'editor']);
    }

    public function isReporter(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'pemred', 'editor', 'reporter']);
    }

    public function canPublish(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'pemred', 'editor']);
    }

    public function canManageAll(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Administrator',
            'pemred' => 'Pemimpin Redaksi',
            'editor' => 'Editor',
            'reporter' => 'Reporter',
            'kontributor' => 'Kontributor',
            default => ucfirst((string) $this->role),
        };
    }
}
