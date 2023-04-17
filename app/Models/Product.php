<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     protected $fillable = [
        'product_name', 'product_details', 'product_price','quantity','point','sale','sale_price','packet','status','latlong'
    ];
}
