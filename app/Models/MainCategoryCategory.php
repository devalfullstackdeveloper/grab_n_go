<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategoryCategory extends Model
{
    use HasFactory;
    protected $table = 'maincategorycategory';
     protected $fillable = [
        'maincategory_id','category_id'
    ];
}
