<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employer;
use App\Models\Category;
use App\Models\Application;
use App\Models\JobSkill;

class Usersjob extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'title',
        'work_type',
        'location',
        'category_id',
        'salary_from',
        'salary_to',
        'experience',
        'deadline',
        'description',
        'responsibilities',
        'benefits',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function applications()
{
    return $this->hasMany(Application::class, 'job_id');
}
public function skills()
{
    return $this->hasMany(JobSkill::class, 'job_id');
}


}
