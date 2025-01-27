<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presentacion extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'tipo',
    ];
    
    public function medicamentos()
    {
        return $this->hasMany(Medicamento::class);
    }

}
