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
            $table->datetime('date');
            $table->string('email');
            $table->json('values');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('document_id');
            $table->string('code')->nullable();

            $table->timestamps();

            $table->index(["status_id"], 'appointment-status');
            $table->foreign('status_id', 'appointment-status')
            ->references('id')->on('status')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->index(["document_id"], 'appointment-documents');
            $table->foreign('document_id', 'appointment-documents')
            ->references('id')->on('documents')
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
