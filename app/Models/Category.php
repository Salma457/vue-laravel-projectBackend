<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id', 'category_icon', 'category_name', 'category_description'];

    //I need to add the relationship with the jobs: contain the id

    public function usersjobs(){ 
        return $this->hasMany(Application::class, 'category_id');}

    
}
