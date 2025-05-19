<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
class Candidate extends Model
{
    public function applications()
{
    return $this->hasMany(Application::class);
}

}
