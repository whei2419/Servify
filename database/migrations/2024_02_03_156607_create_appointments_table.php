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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('timeframe_id');
            $table->string('email');
            $table->json('values');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            $table->index(["timeframe_id"], 'appointment-timeframe');
            $table->index(["status_id"], 'appointment-status');


            $table->foreign('timeframe_id', 'appointment-timeframe')
            ->references('id')->on('timeframes')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            
            $table->foreign('status_id', 'appointment-status')
            ->references('id')->on('status')
            ->onDelete('restrict')
            ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
