<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    use HasFactory;
    protected $table = 'productssubcategory';
     protected $fillable = [
        'subcategory_id','product_id'
    ];
}
