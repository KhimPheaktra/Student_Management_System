<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

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
        'role_id',
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


    // public function role(): BelongsTo
    // {
    //     return $this->belongsTo(RoleModel::class);
    // }


    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }   
    public function employee() {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }

    
    /**
     * Check if the user has a specific role.
     *
     * @param string $roleName
     * @return bool
     */
    // public function hasRole(string $roleName): bool
    // {
    //     return $this->role()->where('name', $roleName)->exists();
    // }

    public function hasRole($roleName): bool
    {
        // If an array of roles is passed, check if any match
        if (is_array($roleName)) {
            return in_array($this->role->name, $roleName);
        }

        // If a single role is passed, check for exact match
        return $this->role->name === $roleName;
    }
    
}
