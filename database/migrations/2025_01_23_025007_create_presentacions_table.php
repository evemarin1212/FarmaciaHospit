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
        Schema::create('presentacions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->enum('via_administracion', [
                'Oral',
                'Sublingual',
                'Tópica',
                'Oftálmica',
                'Ótica',
                'Nasal',
                'Inhalatoria',
                'Rectal',
                'Vaginal',
                'Intramuscular',
                'Intravenosa',
                'Subcutanea'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentacions');
    }
};
