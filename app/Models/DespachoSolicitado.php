<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DespachoSolicitado extends Model
{
    use HasFactory;

    protected $table = 'despacho_solicitado'; // Opcional, pero explícito es mejor

    // protected $fillable = [
    //     'despacho_id',
    //     'medicamento_id',
    //     'cantidad',
    //     'despacho_medicamento_id',
    // ];

    protected $fillable = [
        'despacho_id', 
        'medicamento_id', 
        'cantidad', 
        'despacho_medicamento_id', 
    ];


    public function despacho()
    {
        return $this->belongsTo(Despacho::class);
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

        public function despachoMedicamento()
    {
        return $this->belongsTo(DespachoMedicamento::class);
    }
}
