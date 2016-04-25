<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Order extends Authenticatable
{
    protected $table        = 'orders';
    protected $primaryKey   = 'orders_id';
    protected $fillable     = ['is_order_successful', 'orders_status_code'];
}
