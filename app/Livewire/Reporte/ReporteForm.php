<?php

namespace App\Livewire\Reporte;

use Livewire\Component;

class ReporteForm extends Component
{
    public $formView = false;

    public function form()
    {
        $this->formView = true;
    }

    public function cancelar()
    {
        $this->formView = false;
        // $this->resetForm();
    }

    public function render()
    {
        return view('livewire.reporte.reporte-form');
    }
}
