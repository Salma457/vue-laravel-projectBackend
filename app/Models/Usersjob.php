<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employer;
use App\Models\Category;
use App\Models\Application;

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
        'deadline',
        'description',
        'status',
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

}
