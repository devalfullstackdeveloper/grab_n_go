<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExploreProductOffer extends Model
{
     use HasFactory;
     protected $table = 'exploreproductoffer';
     protected $fillable = [
       'offer_product_name','offer_product_detail','status'
    ];
}
