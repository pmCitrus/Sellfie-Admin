<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class InternalStatusCode extends Authenticatable
{
    protected $table        = 'internal_status_codes';
    protected $primaryKey   = 'internal_status_codes_id';
    protected $fillable     = ['internal_status_code', 'status_description', 'is_active'];
}
