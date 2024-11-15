<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Students extends Authenticatable {
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'carnet',
        'email',
        'phone_number',
        'password',
        'career_id',
        'enabled',
        'rol_id'
    ];

    protected $table = 'students';
}
