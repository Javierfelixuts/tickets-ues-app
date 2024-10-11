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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_id')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['Abierto','En proceso', 'Cerrado', 'Pendiente', 'Resuelto'])->default('Abierto');
            $table->enum('priority', ['Baja', 'Media', 'Alta', 'Urgente'])->default('Baja');
            $table->enum('satisfied', ['Sin responder', 'Excelente', 'Buena', 'Regular', 'Mal'])->default('Sin responder');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->timestamps();

            $table->foreign('assignee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
