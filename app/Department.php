<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table        = 'departments';
    protected $primaryKey   = 'departments_id';
    protected $fillable     = ['departments_name', 'is_active'];
}
