<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Authenticatable
{
    use SoftDeletes;
    
    protected $table        = 'products';
    protected $primaryKey   = 'products_id';
    protected $fillable     = ['is_active', 'is_admin_approved'];
    protected $date         = 'deleted_at';
}
