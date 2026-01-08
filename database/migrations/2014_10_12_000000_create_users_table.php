<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',25)->unique();
            $table->string('user_type',10)->default('auth');
            $table->string('fname',50)->default('');
            $table->string('lname',75)->default('');
            $table->string('email',150)->default('');
            $table->string('phone',20)->nullable()->default('');
            $table->string('address',200)->nullable()->default('');
            $table->string('city',100)->nullable()->default('');
            $table->string('state',20)->nullable()->default('');
            $table->string('zip',20)->nullable()->default('');
            $table->smallInteger('join')->default(1);
            $table->string('password',100);
            $table->string('photo',200)->nullable()->default('profile/male.png');
            $table->string('slug',100);
            $table->integer('last_login')->unsigned()->default(0);
            $table->unsignedInteger('notified_date')->nullable()->default(null);
            $table->smallInteger('validated')->index()->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
