<?php

namespace App\Livewire\archivo;

use App\Models\Medicamento;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class archivo extends Component
{
    // public function pdf(){
    //     $archivo=Medicamento::all();
    //     $pdf = Pdf::loadView('archivo.Medicamento', compact('archivo'));
        
    // }

    // public function render()
    // {
    //     return view('archivo.archivo',[
    //         $this->pdf->stream(),
    //     ]);
    // }
}
