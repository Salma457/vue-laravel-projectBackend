<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'employer_id', 'job_id', 'amount', 'payment_method',
        'status', 'transaction_id', 'paid_at',
    ];
}
