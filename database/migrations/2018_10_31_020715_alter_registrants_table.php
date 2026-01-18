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
        Schema::table('registrants', function ($table) {
            $table->string('pageurl', 150)->nullable()->default('');
            $table->string('pageshorturl', 100)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrants', function ($table) {
            $table->dropColumn('pageurl');
            $table->dropColumn('pageshorturl');
        });
    }
};
