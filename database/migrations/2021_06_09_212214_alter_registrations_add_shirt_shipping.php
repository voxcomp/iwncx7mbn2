<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRegistrationsAddShirtShipping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('registrants', function($table) {
            $table->smallInteger('shipshirt')->default(0);
            $table->string('shipaddress',200)->nullable()->default('');
            $table->string('shipcity',75)->nullable()->default('');
            $table->string('shipstate',2)->nullable()->default('');
            $table->string('shipzip',10)->nullable()->default('');
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
	        $table->dropColumn('shipshirt');
	        $table->dropColumn('shipaddress');
	        $table->dropColumn('shipcity');
	        $table->dropColumn('shipstate');
	        $table->dropColumn('shipzip');
	    });
    }
}
