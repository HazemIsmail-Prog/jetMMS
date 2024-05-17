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
        Schema::create('part_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manual_id')->nullable();
            $table->date('date');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('contact_id')->constrained('users');
            $table->float('invoice_amount',8,3)->nullable();
            $table->float('discount_amount',8,3)->nullable();
            $table->float('cost_amount',8,3);
            $table->float('sales_amount',8,3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_invoices');
    }
};
