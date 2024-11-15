<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'enabled',
        'rol_id',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getStudentsInfo(): Collection {
        return DB::table('users')
            ->select('users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'students.carnet',
                'students.career_id',
                'careers.career_name',
                'careers.department_id',
                'departments.department_name')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('careers', 'students.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->get();
    }

    public function getStudentsInfoById($id): Collection {
        return DB::table('users')
            ->select('users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'students.carnet',
                'students.career_id',
                'careers.career_name',
                'careers.department_id',
                'departments.department_name')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->join('careers', 'students.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->where('students.id', '=', $id)
            ->get();
    }

    public function getCompaniesInfo(): Collection {
        return DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'companies.nit',
                'companies.address',
                'companies.entity_name_id',
                'companies.district_id',
                'companies.clasification_id',
                'companies.brand_id',
                'companies.sector_id',
                'entity_type.entity_name',
                'sv_districts.id',
                'sv_districts.district_name',
                'sv_districts.municipality_id',
                'sv_municipalities.municipality_name',
                'sv_municipalities.sv_department_id',
                'sv_departments.department_name',
                'clasifications.id',
                'clasifications.clasification_name',
                'brands.id',
                'brands.section'
            )
            ->join('companies', 'users.id', '=', 'companies.user_id')
            ->join('entity_type', 'companies.entity_name_id', '=', 'entity_type.id')
            ->join('sv_districts', 'companies.district_id', '=', 'sv_districts.id')
            ->join('sv_municipalities', 'sv_districts.municipality_id', '=', 'sv_municipalities.id')
            ->join('sv_departments', 'sv_municipalities.sv_department_id', '=', 'sv_departments.id')
            ->join('clasifications', 'companies.clasification_id', '=', 'clasifications.id')
            ->join('brands', 'companies.brand_id', '=', 'brands.id')
            ->get();
    }

    public function getCompaniesInfoById($id): Collection {
        return DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'companies.nit',
                'companies.address',
                'companies.entity_name_id',
                'companies.district_id',
                'companies.clasification_id',
                'companies.brand_id',
                'companies.sector_id',
                'entity_type.entity_name',
                'sv_districts.id',
                'sv_districts.district_name',
                'sv_districts.municipality_id',
                'sv_municipalities.municipality_name',
                'sv_municipalities.sv_department_id',
                'sv_departments.department_name',
                'clasifications.id',
                'clasifications.clasification_name',
                'brands.id',
                'brands.section'
            )
            ->join('companies', 'users.id', '=', 'companies.user_id')
            ->join('entity_type', 'companies.entity_name_id', '=', 'entity_type.id')
            ->join('sv_districts', 'companies.district_id', '=', 'sv_districts.id')
            ->join('sv_municipalities', 'sv_districts.municipality_id', '=', 'sv_municipalities.id')
            ->join('sv_departments', 'sv_municipalities.sv_department_id', '=', 'sv_departments.id')
            ->join('clasifications', 'companies.clasification_id', '=', 'clasifications.id')
            ->join('brands', 'companies.brand_id', '=', 'brands.id')
            ->where('companies.id', '=', $id)
            ->get();
    }

    public function getManagersInfo(): Collection {
        return DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'managers.career_id',
                'careers.career_name',
                'careers.department_id',
                'departments.department_name'
            )
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('careers', 'managers.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->where('managers.id', '<>', 1)
            ->get();
    }

    public function getManagersInfoById($id): Collection {
        return DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'managers.career_id',
                'careers.career_name',
                'careers.department_id',
                'departments.department_name'
            )
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('careers', 'managers.career_id', '=', 'careers.id')
            ->join('departments', 'careers.department_id', '=', 'departments.id')
            ->where('managers.id', '=', $id)
            ->get();
    }
}
