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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->foreignId('receiver_id')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->string('document_number');
            $table->integer('document_serial_from');
            $table->integer('document_serial_to');
            $table->integer('document_pages');
            $table->string('status');
            $table->date('receiving_date')->nullable();
            $table->date('back_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
