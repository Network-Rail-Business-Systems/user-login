<?php

namespace NetworkRailBusinessSystems\UserLogin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

class User extends Authenticatable implements LdapAuthenticatable
{
    use AuthenticatesWithLdap;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'username'];

    protected $guarded = [
        'created_at',
        'deleted_at',
        'id',
        'name',
        'password',
        'remember_token',
        'updated_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected string $guard_name = 'web';
}
