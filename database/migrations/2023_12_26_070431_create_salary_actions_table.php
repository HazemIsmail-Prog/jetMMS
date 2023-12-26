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
        Schema::create('salary_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class);
            $table->foreignIdFor(User::class,'created_by');
            $table->date('date');
            $table->float('amount');
            $table->string('type'); // deduction - increase
            $table->string('reason');
            $table->string('status'); // pending - done
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_actions');
    }
};
