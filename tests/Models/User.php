<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NetworkRailBusinessSystems\UserLogin\Traits\ExistingUser;
use NetworkRailBusinessSystems\UserLogin\Traits\GetExistingUser;

class User extends Authenticatable implements ExistingUser
{
    use GetExistingUser;
    use HasFactory;
    use SoftDeletes;

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

    public static function mail($username): ?string
    {
        return static::query()
            ->where('username', '=', $username)
            ->first()?->email;
    }
}
