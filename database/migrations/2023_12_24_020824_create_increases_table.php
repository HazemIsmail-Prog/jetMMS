<?php

use App\Models\Employee;
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
        Schema::create('increases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class,'created_by');
            $table->foreignIdFor(Employee::class);
            $table->date('increase_date');
            $table->float('amount');
            $table->string('type');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('increases');
    }
};
