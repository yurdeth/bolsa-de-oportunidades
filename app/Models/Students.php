<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Students extends Model {
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'carnet',
        'email',
        'phone_number',
        'password',
        'career_id',
    ];

    protected $table = 'students';
}
