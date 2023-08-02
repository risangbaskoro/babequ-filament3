<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, CanResetPassword
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

    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN->value;
    }

    public function isCitizen(): bool
    {
        return $this->role == UserRole::CITIZEN->value;
    }

    public function isVisitor(): bool
    {
        return $this->role == UserRole::VISITOR->value;
    }

    public function markAsCitizen(): void
    {
        $this->role = UserRole::CITIZEN->value;
        // TODO: Hapus file foto KTP
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match (true) {
            $panel->getId() == 'admin' && $this->isAdmin(), $panel->getId() == 'citizen' && $this->isCitizen() => true,
            default => false,
        };
    }
}
