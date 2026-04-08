<?php

namespace App\Models;

// Tambahkan Hash agar password otomatis terenkripsi (Laravel 11+)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Jika kamu pakai Laravel 11, tambahkan ini agar password otomatis di-hash
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}