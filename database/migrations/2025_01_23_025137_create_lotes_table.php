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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            $table->string('codigo_lote');
            $table->integer('cantidad');
            $table->date('fecha_vencimiento');
            $table->date('fecha_registro');
            $table->string('origen');
            $table->enum('estatus', ['disponible', 'agotado', 'por vencer', 'vencido']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
