<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentUserMapping extends Model
{
    //
    protected $table        = 'department_user_mappings';
    protected $primaryKey   = 'department_user_mappings_id';
    protected $fillable     = ['users_id', 'departments_id'];
}
