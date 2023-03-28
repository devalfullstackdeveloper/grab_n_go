<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExploreProductOfferProduct extends Model
{
     use HasFactory;
     protected $table = 'exploreproductofferproduct';
     protected $fillable = [
       'exploreproductoffer_id','product_id'
    ];
}
