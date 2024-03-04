<?php

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
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('phone_id')->constrained('phones');
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->foreignId('department_id')->constrained('departments');
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
