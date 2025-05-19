<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\User;

class Candidate extends Model
{
    protected $fillable = [
    'user_id',
    'current_job',
    'experience_level',
    'highest_qualification',
    'bio',
    'resume',
    'linkedin_profile',
    'phone_number',
    'location'
];
    public function applications()
{
    return $this->hasMany(Application::class);
}

}
