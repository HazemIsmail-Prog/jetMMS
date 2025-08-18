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
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('cash_account_id')->nullable()->constrained('accounts');
            $table->foreignId('receivables_account_id')->nullable()->constrained('accounts');
            $table->foreignId('bank_account_id')->nullable()->constrained('accounts');
            $table->foreignId('bank_charges_account_id')->nullable()->constrained('accounts');
            $table->foreignId('internal_parts_account_id')->nullable()->constrained('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['cash_account_id']);
            $table->dropColumn('cash_account_id');
            $table->dropForeign(['receivables_account_id']);
            $table->dropColumn('receivables_account_id');
            $table->dropForeign(['bank_account_id']);
            $table->dropColumn('bank_account_id');
            $table->dropForeign(['bank_charges_account_id']);
            $table->dropColumn('bank_charges_account_id');
            $table->dropForeign(['internal_parts_account_id']);
            $table->dropColumn('internal_parts_account_id');
        });
    }
};
