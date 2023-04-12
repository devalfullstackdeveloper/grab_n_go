<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
     protected $fillable = [
        'banner_name', 'banner_image', 'banner_offer_type','status','mastercategory_id','maincategory_id','category_id','subcategory_id'
    ];
}
