<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usersjob;

class JobSkill extends Model
{
     use HasFactory;

    protected $fillable = ['job_id', 'skill_id'];

    public function job()
    {
        return $this->belongsTo(Usersjob::class, 'job_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
