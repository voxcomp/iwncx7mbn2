<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRegistrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('registrants', function($table) {
	        $table->string('pageurl',150)->nullable()->default('');
	        $table->string('pageshorturl',100)->nullable()->default('');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('registrants', function($table) {
	        $table->dropColumn('pageurl');
	        $table->dropColumn('pageshorturl');
	    });
    }
}
