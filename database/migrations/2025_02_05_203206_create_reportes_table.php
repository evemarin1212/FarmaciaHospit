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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['hospitalizado', 'emergencia', 'quirofano', 'via_oral', 'general']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('ruta_dir'); // donde se guarda
            $table->string('url'); // para mostrar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
