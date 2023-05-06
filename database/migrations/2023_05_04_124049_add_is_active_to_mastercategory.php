<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToMastercategory extends Migration
{
    public function up()
    {
        Schema::table('mastercategory', function (Blueprint $table) {
            $table->enum('isActive', [0,1])->default(1)->after('status')->comment('Inactive=0,Active=1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mastercategory', function (Blueprint $table) {
            $table->dropColumn('isActive');
        });
    }
}
