<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Employer;
use App\Models\Candidate;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'profile_picture'];

    protected $hidden = ['remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employer(){
        return $this->hasOne(Employer::class);
    }

    public function candidate(){
        return $this->hasOne(Candidate::class);
    }

    public function isEmployer(): bool{
        return $this->role === 'employer';
    }

    public function isCandidate(): bool{
        return $this->role === 'candidate';
    }
// App\Models\User.php


public function jobs()
{
    return $this->hasMany(Usersjob::class, 'employer_id'); // employer_id هو اسم العمود الذي يربط الوظائف بالمستخدم
}

}
