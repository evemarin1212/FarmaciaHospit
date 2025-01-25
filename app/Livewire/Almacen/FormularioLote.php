<?php

namespace App\Livewire\Almacen;

use Livewire\Component;
use App\Models\Lote;
use App\Models\Medicamento;

class FormularioLote extends Component
{
    public function render()
    {
        return view('livewire.almacen.formulario-lote', [
            'medicamentos' => Medicamento::all(),
        ]);
    }
}