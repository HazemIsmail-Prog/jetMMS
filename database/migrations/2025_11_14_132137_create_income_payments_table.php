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
        Schema::create('income_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_invoice_id')->constrained('income_invoices');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('bank_account_id')->nullable()->constrained('accounts');
            $table->decimal('amount', 10, 3);
            $table->string('method');
            $table->text('narration')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_payments');
    }
};
