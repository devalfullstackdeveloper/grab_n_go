<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAllCategory extends Model
{
    use HasFactory;
    protected $table = 'products_all_category';

    protected $fillable = [
        'product_id','mastercategory_id','maincategory_id','category_id','subcategory_id'
    ];
}
