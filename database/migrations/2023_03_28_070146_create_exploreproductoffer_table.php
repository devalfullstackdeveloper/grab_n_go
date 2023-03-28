<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExploreproductofferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploreproductoffer', function (Blueprint $table) {
            $table->id();
            $table->string('offer_product_name')->nullable();
            $table->string('offer_product_detail')->nullable();
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
        Schema::dropIfExists('exploreproductoffer');
    }
}
