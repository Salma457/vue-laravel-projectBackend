<?php

namespace App\Models;

use App\Models\Usersjob;
use App\Models\JobSkill;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public function jobs()
{
    return $this->belongsToMany(Usersjob::class, 'job_skill');
}

}
