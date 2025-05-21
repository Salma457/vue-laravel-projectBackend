<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Usersjob;

class Employer extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'location',
        'company_website',
        'company_logo',
        'phone',
        'bio',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function jobs()
    {
        return $this->hasMany(Usersjob::class);
    }
 
}
