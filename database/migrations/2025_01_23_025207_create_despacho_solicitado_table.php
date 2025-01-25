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
        Schema::create('despacho_solicitado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('despacho_id')->constrained('despachos')->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->foreignId('despacho_medicamento_id')->constrained('despacho_medicamentos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despacho_solicitado');
    }
};
