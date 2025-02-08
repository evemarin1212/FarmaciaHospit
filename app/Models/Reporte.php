<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reporte extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'ruta_dir',
        'url',
    ];

}
 