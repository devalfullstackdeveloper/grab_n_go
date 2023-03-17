<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMainCategory extends Model
{
    use HasFactory;
    protected $table = 'mastermaincategory';
     protected $fillable = [
        'mastercategory_id','maincategory_id'
    ];
}
