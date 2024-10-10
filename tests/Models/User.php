<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NetworkRailBusinessSystems\UserLogin\Traits\SyncWithLdap;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use SyncWithLdap;

    protected $fillable = ['first_name', 'last_name', 'email', 'username'];

    protected $guarded = [
        'created_at',
        'deleted_at',
        'id',
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

    public static function syncUser(string $username): bool
    {
        if (config('userlogin.ldap-sync') === true) {
            return false;
        }

        return true;
    }
}
