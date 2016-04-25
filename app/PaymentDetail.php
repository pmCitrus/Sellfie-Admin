<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentDetail extends Authenticatable
{
    protected $table        = 'payment_details';
    protected $primaryKey   = 'payment_details_id';
}
