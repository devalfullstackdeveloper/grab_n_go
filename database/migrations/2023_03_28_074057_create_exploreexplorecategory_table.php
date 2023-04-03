<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExploreExploreCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploreexplorecategory', function (Blueprint $table) {
            $table->id();
            $table->integer('explore_id');
            $table->integer('mastercategory_id')->default(0);
            $table->integer('maincategory_id')->default(0); 
            $table->integer('category_id')->default(0); 
            $table->integer('subcategory_id')->default(0); 
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
        Schema::dropIfExists('exploreexplorecategory');
    }
}
