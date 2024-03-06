<?php

use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\Company;
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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('car_brand_id')->constrained('car_brands');
            $table->foreignId('car_type_id')->nullable()->constrained('car_types');
            $table->integer('plate_no');
            $table->integer('management_no')->nullable();
            $table->integer('year');
            $table->date('insurance_expiration_date');
            $table->integer('passengers_no');
            $table->date('adv_expiration_date')->nullable();
            $table->boolean('has_installment');
            $table->string('installment_company')->nullable();
            $table->string('fuel_card_serial')->nullable();
            $table->string('fuel_card_number')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
