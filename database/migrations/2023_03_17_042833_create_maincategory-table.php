<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaincategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maincategory', function (Blueprint $table) {
            $table->id();
            $table->integer('mastercategory_id')->nullable();
            $table->string('main_category_name')->nullable();
            $table->string('main_category_image')->nullable();
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
        //
    }
}
