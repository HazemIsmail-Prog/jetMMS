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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('user_id')->constrained('users');
            $table->string('contract_type');
            $table->string('contract_date');
            $table->string('contract_duration');
            $table->float('contract_value');
            $table->string('contract_expiration_date')->nullable();
            $table->string('contract_number');
            $table->string('building_type');
            $table->integer('units_count')->nullable();
            $table->integer('central_count')->nullable();
            $table->float('collected_amount')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
