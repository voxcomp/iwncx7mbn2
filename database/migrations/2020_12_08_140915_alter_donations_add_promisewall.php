<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function ($table) {
            $table->string('promise', 3)->default('no');
            $table->string('memoryof', 100)->default('');
            $table->string('photo', 350)->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function ($table) {
            $table->dropColumn('promise');
            $table->dropColumn('memoryof');
            $table->dropColumn('photo');
        });
    }
};
