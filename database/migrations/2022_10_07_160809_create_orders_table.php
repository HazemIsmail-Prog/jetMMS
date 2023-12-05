<?php

use App\Models\Address;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Phone;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class);
            $table->foreignIdFor(Phone::class);
            $table->foreignIdFor(Address::class);
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(User::class, 'updated_by');
            $table->foreignIdFor(User::class, 'technician_id')->nullable();
            $table->foreignIdFor(Status::class)->nullable();
            $table->foreignIdFor(Department::class);
            $table->integer('index')->nullable()->default(0);
            $table->date('estimated_start_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('order_description')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->string('tag')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
