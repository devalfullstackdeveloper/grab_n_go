<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToExploreexplorecategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exploreexplorecategory', function (Blueprint $table) {
            $table->enum('isActive', [0,1])->default(1)->after('subcategory_id')->comment('Inactive=0,Active=1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exploreexplorecategory', function (Blueprint $table) {
            $table->dropColumn('isActive');
        });
    }
}
