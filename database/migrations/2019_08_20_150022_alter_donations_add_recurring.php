<?php

use Illuminate\Database\Migrations\Migration;
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
        Schema::table('donations', function ($table) {
            $table->string('recurring', 3)->default('NO');
            $table->float('recurring_amount')->default(0);
            $table->string('customerid', 50)->default(0);
            $table->string('planid', 50)->default(0);
            $table->string('subscriptionid', 50)->default(0);
            $table->unsignedInteger('cancelled_on')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('donations', function ($table) {
            $table->dropColumn('recurring');
            $table->dropColumn('recurring_amount');
            $table->dropColumn('customerid');
            $table->dropColumn('planid');
            $table->dropColumn('subscriptionid');
            $table->dropColumn('cancelled_on');
        });
    }
};
