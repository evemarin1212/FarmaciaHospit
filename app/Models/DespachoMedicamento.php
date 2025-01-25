<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DespachoMedicamento extends Model
{
    use HasFactory;

    // Definir la tabla asociada (opcional si el nombre sigue la convención)
    protected $table = 'despacho_medicamento';

    // Definir los campos que pueden ser asignados en masa
    protected $fillable = [
        'despacho_id',
        'medicamento_id',
        'cantidad',
    ];

    // Relación con Despacho
    public function despacho()
    {
        return $this->belongsTo(Despacho::class);
    }

    // Relación con Medicamento
    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function solicitudes() // Nombre descriptivo para la relación
    {
        return $this->hasMany(DespachoSolicitado::class, 'despacho_medicamento_id');
    }

} 
