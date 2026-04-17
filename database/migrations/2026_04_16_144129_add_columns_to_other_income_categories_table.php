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
        Schema::table('other_income_categories', function (Blueprint $table) {
            $table->foreignId('refund_account_id')->nullable()->constrained('accounts');
            $table->foreignId('cost_account_id')->nullable()->constrained('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_income_categories', function (Blueprint $table) {
            $table->dropForeign(['refund_account_id']);
            $table->dropForeign(['cost_account_id']);
        });
    }
};
