<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('subcategory', function (Blueprint $table) {
            $table->id();
            $table->string('sub_category_name')->nullable();
            $table->string('sub_category_image')->nullable();
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
