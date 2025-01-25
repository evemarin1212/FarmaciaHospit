<?php
namespace App\Livewire\Despacho;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Despacho;
use App\Models\DespachoMedicamento;

class DespachoTable extends Component
{
    use WithPagination;

    public $filter = 'todos';
    public $modal = false;
    public $DespachoSeleccionado;
    public $editar = false;
    
    protected $paginationTheme = 'tailwind';
    protected $listeners = ['render'];
    
    public function ver($id)
    {
        $this->DespachoSeleccionado = DespachoMedicamento::findOrFail($id);
        $this->modal = true;
        $this->editar = false;
    }
    
    public function cerrar()
    {
        $this->modal = false;
        $this->DespachoSeleccionado = null;
    }
    
    public function render()
    {
        $despachos = Despacho::query()
            ->where('tipo', '!=', 'quirofano');
    
        if ($this->filter === 'recientes') {
            $despachos->orderBy('created_at', 'desc');
        }
    
        return view('livewire.despacho.despacho-table', [
            'despachos' => $despachos->paginate(10),
        ]);
    }
    
}
