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
        Schema::create('survey_satisfied', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->enum('score', [1,2,3,4,5]);
            $table->boolean('survey_answered', false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete(null);
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete(null);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_satisfied');
    }
};
