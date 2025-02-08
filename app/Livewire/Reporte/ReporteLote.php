<?php

namespace App\Livewire\Reporte;

use App\Models\Reporte;
use Livewire\Component;
use Livewire\WithPagination;

class ReporteLote extends Component
{
    use WithPagination;

    public $filter = 'todos';

    protected $listeners = ['render']; // PARA REGACAR LA TABLA


    public function render()
    {
        $reportes = Reporte::query()
            ->when($this->filter !== 'todos', function ($query) {
                return $query->where('tipo', $this->filter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.reporte.reporte-lote', compact('reportes'));
    }
}
