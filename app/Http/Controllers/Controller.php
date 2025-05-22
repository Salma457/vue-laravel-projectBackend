<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Controller
{
     use HasFactory;

    protected $fillable = [
        'category_icon',
        'category_name',
        'category_description',
    ];
}
