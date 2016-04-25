<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class PaymentLink extends Authenticatable
{
    
    protected $table        = 'payment_links';
    protected $primaryKey   = 'payment_links_id';
    protected $fillable     = ['is_active','is_expired','is_admin_approved'];
    
}
