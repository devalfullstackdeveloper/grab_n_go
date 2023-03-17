<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;
    protected $table = 'maincategory';
     protected $fillable = [
        'mastercategory_id','main_category_name', 'main_category_image', 'status'
    ];
}
