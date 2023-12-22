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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('attachable_id')->unsigned();
            $table->string('attachable_type');
            $table->string('description_ar')->nullable();
            $table->string('description_en')->nullable();
            $table->string('file');
            $table->date('expirationDate')->nullable();
            $table->boolean('alertable')->default(false);
            $table->integer('alertBefore')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
