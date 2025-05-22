<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employer;
use App\Models\Usersjob;

class Payment extends Model
{
      protected $fillable = [
        'stripe_payment_id',
        'user_id',
        'employer_id',
        'job_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'paid_at',
    ];
 public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function job()
    {
        return $this->belongsTo(Usersjob::class, 'job_id');
    }

}
