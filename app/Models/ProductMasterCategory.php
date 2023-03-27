<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMasterCategory extends Model
{
    use HasFactory;
    protected $table = 'productsmastercategory';
     protected $fillable = [
        'mastercategory_id','product_id'
    ];
}
