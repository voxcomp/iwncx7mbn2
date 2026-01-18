<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->unique()->index();
            $table->float('amount', 5, 2)->default(0);
            $table->enum('discount_type', ['dollar', 'percent'])->default('dollar');
            $table->smallInteger('active')->default(1);
            $table->integer('valid_from')->unsigned()->default(0);
            $table->integer('valid_to')->unsigned()->default(0);
            $table->integer('last_used')->unsigned()->default(0);
            $table->integer('used')->unsigned()->default(0);
            $table->integer('maxuse')->unsigned()->default(0);
            $table->timestamps();
        });
        DB::update('ALTER TABLE coupons AUTO_INCREMENT = 615382');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
