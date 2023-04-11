<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;
     protected $table = 'category';
     protected $fillable = [
       'category_name', 'category_image','category_banner_image','status'
    ];
}
