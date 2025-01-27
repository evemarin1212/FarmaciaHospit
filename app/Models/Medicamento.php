<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicamento extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre',
        'presentacion_id',
        'medida',
        'unidad',
        'cantidad_disponible',
        'estatus',
    ];
    
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class);
    }
    
    public function despachos()
    {
        return $this->belongsToMany(Despacho::class, 'despacho_medicamentos')->withPivot('cantidad');
    }

    public function despachosMedicamentos()
    {
        return $this->hasMany(DespachoMedicamento::class);
    }

    public function despachosSolicitados() {
        return $this->belongsToMany(Despacho::class, 'despacho_solicitado')->withPivot('cantidad', 'despacho_medicamento_id');
    }

}
