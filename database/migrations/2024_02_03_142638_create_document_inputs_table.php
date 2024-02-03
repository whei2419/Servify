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
        Schema::create('document_inputs', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('input_id');
            $table->timestamps();

            $table->index(["document_id"], 'documents-docuinputs');
            $table->index(["input_id"], 'input-docuinputs');


            $table->foreign('document_id', 'documents-docuinputs')
            ->references('id')->on('documents')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            
            $table->foreign('input_id', 'input-docuinputs')
            ->references('id')->on('inputs')
            ->onDelete('restrict')
            ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_inputs');
    }
};
