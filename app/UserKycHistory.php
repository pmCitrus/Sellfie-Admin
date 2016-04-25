<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserKycHistory extends Authenticatable
{
    protected $table        = 'user_kycs_history';
    protected $primaryKey   = 'user_kycs_history_id';
    protected $fillable     = ['user_kycs_id', 'uploaded_by', 'uploaded_by_id', 'seller_name', 'seller_username', 'seller_email', 'seller_profile_name', 'seller_profile_url', 'pan_number', 'id_proof_doc', 'id_proof_ref_number', 'addr_proof_doc', 'addr_proof_ref_number', 'doc_set_file_name', 'approval_status', 'action_done_by'];
}