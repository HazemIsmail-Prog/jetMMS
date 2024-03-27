<?php

use App\Models\Account;
use App\Models\Voucher;
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
        Schema::create('voucher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers');
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers');
            $table->string('narration')->nullable();
            $table->float('debit',10,3);
            $table->float('credit',10,3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_details');
    }
};
