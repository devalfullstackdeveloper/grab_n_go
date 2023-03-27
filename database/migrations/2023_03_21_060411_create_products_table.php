<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('product_details')->nullable();
            $table->string('product_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('point')->nullable();
            $table->enum('sale', ['Yes', 'No'])->nullable();
            $table->string('sale_price')->nullable();
            $table->string('status')->nullable()->comment('Available=1,Unavailable=2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
