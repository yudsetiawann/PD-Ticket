<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // 'is_admin' => 'boolean',
        ];
    }

    /**
     * Helper method untuk mengecek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    /**
     * Check if the user is a scanner.
     */
    public function isScanner(): bool
    {
        return $this->role === 'scanner';
    }

    /**
     * Metode ini WAJIB ada setelah implementasi FilamentUser.
     * Filament akan memanggil metode ini untuk menentukan izin akses.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access if the role is 'admin' or 'scanner'
        return $this->isAdmin() || $this->isScanner();
    }
}
