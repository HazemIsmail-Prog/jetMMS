<?php

use App\Models\Car;
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
        Schema::create('car_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars');
            $table->foreignId('driver_id')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->string('type');  // assign - unassign
            $table->string('notes')->nullable();
            $table->date('date');
            $table->time('time');
            $table->integer('fuel');
            $table->integer('kilos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_actions');
    }
};
