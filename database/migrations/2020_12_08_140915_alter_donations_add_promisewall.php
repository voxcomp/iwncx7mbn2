<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDonationsAddPromisewall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('donations', function($table) {
	        $table->string('promise',3)->default('no');
            $table->string('memoryof',100)->default('');
            $table->string('photo',350)->default('');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('donations', function($table) {
	        $table->dropColumn('promise');
	        $table->dropColumn('memoryof');
	        $table->dropColumn('photo');
	    });
    }
}
