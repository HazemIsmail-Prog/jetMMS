<?php

use App\Models\User;
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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manual_id')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->foreignId('payment_id')->nullable()->constrained('payments');
            $table->date('date');
            $table->string('type')->index();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
