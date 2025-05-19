<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;

class Candidate extends Model
{
    
    protected $fillable = [
        'user_id',
        'resume',
        'linkedin_profile',
        'phone_number',
        'experience_level',
        'location'
    ];

    // adding the relationship with the user model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }

}
