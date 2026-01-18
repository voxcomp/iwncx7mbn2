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
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('registrant_id')->default(0);
            $table->unsignedInteger('event_id')->default(0);
            $table->string('name', 100);
            $table->integer('goal')->default(0);
            $table->string('pagetitle', 200)->default('')->nullable();
            $table->text('pagecontent')->nullable();
            $table->string('photo', 200)->nullable()->default('');
            $table->smallInteger('reviewed')->default(0);
            $table->smallInteger('moderated')->default(0);
            $table->string('adminnotes', 500)->nullable()->default('');
            $table->string('slug', 100)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
