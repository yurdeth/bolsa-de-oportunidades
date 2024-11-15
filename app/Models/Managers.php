<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Managers extends Authenticatable {
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [
        'career_id',
        'user_id'
    ];
}
