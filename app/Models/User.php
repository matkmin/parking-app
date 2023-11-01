<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleName::ADMIN);
    }

    public function isUser(): bool
    {
        return $this->hasRole(RoleName::USER);
    }

    public function hasRole(RoleName $role): bool
    {
        return $this->roles()->where('name', $role->name)->exists();
    }

    public function permission(): array
    {
        return $this->roles()->with('permissions')->get()
            ->map(function ($role) {
                return $role->permission->pluck('name');
            })->flatten()->value->unique()->toArray();
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permission(), true);
    }
}
