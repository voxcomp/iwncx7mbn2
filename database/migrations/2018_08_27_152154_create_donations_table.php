<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('donations');
        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fname',50)->default('');
            $table->string('lname',75)->default('');
            $table->string('email',150)->default('');
            $table->string('phone',20)->default('');
            $table->string('address',200)->default('');
            $table->string('city',100)->default('');
            $table->string('state',20)->default('');
            $table->string('zip',20)->default('');
            $table->string('message',1000)->default('');
            $table->smallInteger('join')->default(1);
            $table->float('amount');
            $table->unsignedInteger('registrant_id')->nullable()->default(0);
            $table->unsignedInteger('event_id')->nullable()->default(0);
            $table->unsignedInteger('team_id')->nullable()->default(0);
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
        Schema::dropIfExists('donations');
    }
}
