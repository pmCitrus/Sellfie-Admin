<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TransactionStatusCode extends Authenticatable
{
    protected $table        = 'pg_status_codes';
    protected $primaryKey   = 'pg_status_codes_id';
}