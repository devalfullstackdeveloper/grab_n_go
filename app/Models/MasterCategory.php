<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
     use HasFactory;
     protected $table = 'mastercategory';
     protected $fillable = [
       'master_category_name', 'master_category_image', 'status'
    ];
}
