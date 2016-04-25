<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    
    protected $table        = 'users';
    protected $primaryKey   = 'users_id';
    protected $fillable     = ['email', 'username', 'password', 'permissions', 'block_status', 'last_login_ip', 'last_login'];
    protected $date         = 'deleted_at';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden   = [
        'password', 'remember_token',
    ];
}
