<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSponsorSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('sponsor_submissions', function($table) {
	        $table->string('paytype',20)->nullable()->default('');
	        $table->integer('inkind_value')->default(0);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('sponsor_submissions', function($table) {
	        $table->dropColumn('paytype');
	        $table->dropColumn('inkind_value');
	    });
    }
}
