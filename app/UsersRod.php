<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersRod extends Authenticatable
{
    protected $table        = 'users_rods';
    protected $primaryKey   = 'users_rods_id';
    protected $fillable     = ['users_id', 'user_rod_days'];
}
