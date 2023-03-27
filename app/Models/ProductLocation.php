<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLocation extends Model
{
    use HasFactory;
    protected $table = 'productslocation';
     protected $fillable = [
        'product_id','lat','long'
    ];
}
