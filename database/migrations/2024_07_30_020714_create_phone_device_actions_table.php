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
        Schema::create('phone_device_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phone_device_id')->constrained('phone_devices');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('from_id')->nullable()->constrained('users');
            $table->foreignId('to_id')->nullable()->constrained('users');
            $table->string('notes')->nullable();
            $table->date('date');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_device_actions');
    }
};
