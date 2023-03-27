<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMainCategory extends Model
{
    use HasFactory;
    protected $table = 'productsmaincategory';
     protected $fillable = [
        'maincategory_id','product_id'
    ];
}
