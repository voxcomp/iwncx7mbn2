<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('event_id');
            $table->string('fname',50)->default('');
            $table->string('lname',75)->default('');
            $table->string('email',150)->default('');
            $table->string('phone',20)->nullable()->default('');
            $table->string('address',200)->nullable()->default('');
            $table->string('city',75)->nullable()->default('');
            $table->string('state',2)->nullable()->default('');
            $table->string('zip',10)->nullable()->default('');
            $table->string('registrant',10)->default('adult');
            $table->string('shirt',4)->default('l');
            $table->smallInteger('pets')->default(0);
            $table->string('slug',200);
            $table->integer('paid')->default(0);
            $table->integer('goal')->default(0);
            $table->string('pagetitle',200)->default('')->nullable();
            $table->text('pagecontent')->nullable();
            $table->smallInteger('reviewed')->default(0);
            $table->smallInteger('moderated')->default(0);
            $table->string('adminnotes',500)->nullable()->default('');
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
        Schema::dropIfExists('registrants');
    }
}
