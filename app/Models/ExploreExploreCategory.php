<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExploreExploreCategory extends Model
{
    use HasFactory;
    protected $table = 'exploreexplorecategory';
    protected $fillable = ['explore_id','mastercategory_id','maincategory_id','category_id','subcategory_id'];
}
