<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volunteer_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->string('company', 150)->nullable()->default('');
            $table->string('fname', 50)->nullable()->default('');
            $table->string('lname', 75)->nullable()->default('');
            $table->string('email', 150)->nullable()->default('');
            $table->string('phone', 20)->nullable()->default('');
            $table->string('address', 200)->nullable()->default('');
            $table->string('city', 75)->nullable()->default('');
            $table->string('state', 2)->nullable()->default('');
            $table->string('zip', 10)->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_submissions');
    }
};
