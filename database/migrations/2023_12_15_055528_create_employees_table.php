<?php

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('comapny_id')->constrained('companies');
            $table->date('joinDate');
            $table->date('recidencyExpirationDate');
            $table->date('passportIssueDate');
            $table->date('passportExpirationDate');
            $table->date('lastWorkingDate')->nullable();
            $table->string('status');
            $table->string('iban');
            $table->string('nationality');
            $table->string('cid');
            $table->string('gender');
            $table->string('passport_no');
            $table->integer('startingSalary');
            $table->float('startingLeaveBalance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
