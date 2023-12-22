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
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Company::class);
            $table->date('joinDate');
            $table->date('recidencyExpirationDate');
            $table->date('passportIssueDate');
            $table->date('passportExpirationDate');
            $table->date('lastWorkingDate')->nullable();
            $table->string('status');
            $table->string('cid');
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
