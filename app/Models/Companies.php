<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Companies extends Model {
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'commercial_name',
        'email',
        'nit',
        'phone_number',
        'entity_name_id',
        'address',
        'district_id',
        'clasification_id',
        'sector_id',
        'brand_id',
        'rol_id',
        'enabled',
    ];
}
