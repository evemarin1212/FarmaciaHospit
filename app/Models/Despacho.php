<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Despacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'observacion',
        'fecha_pedido',
        'paciente_id',
    ];
    
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class, 'despacho_medicamento')->withPivot('cantidad');
    }
    
    public function despachosMedicamentos()
    {
        return $this->hasMany(DespachoMedicamento::class);
    }
    
    public function medicamentosSolicitados() {
        return $this->belongsToMany(DespachoSolicitado::class);
    }

}
