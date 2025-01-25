<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lote extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'medicamento_id',
        'cantidad',
        'fecha_vencimiento',
        'fecha_registro',
        'origen',
        'estatus',
        'codigo_lote',
    ];
    

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }
}
