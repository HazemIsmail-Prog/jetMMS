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
        Schema::create('income_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('other_income_category_id')->constrained('other_income_categories');
            $table->foreignId('created_by')->constrained('users');
            $table->string('manual_number')->nullable();
            $table->text('narration')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_invoices');
    }
};
