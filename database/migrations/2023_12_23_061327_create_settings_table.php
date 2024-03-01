<?php

use App\Models\Setting;
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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->float('knet_tax',3,3)->nullable();
            $table->foreignId('cash_account_id')->nullable()->constrained('accounts');
            $table->foreignId('bank_account_id')->nullable()->constrained('accounts');
            $table->foreignId('bank_charges_account_id')->nullable()->constrained('accounts');
            $table->foreignId('receivables_account_id')->nullable()->constrained('accounts');
            $table->foreignId('internal_parts_account_id')->nullable()->constrained('accounts');
            $table->timestamps();
        });

        Setting::create();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
