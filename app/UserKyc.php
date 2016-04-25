<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserKyc extends Authenticatable
{
    protected $table        = 'user_kycs';
    protected $primaryKey   = 'user_kycs_id';
    protected $fillable     = ['users_id', 'kyc_ref_id', 'is_admin_approved', 'submitted_at', 'action_done_by', 'approved_at', 'rejected_at'];
}