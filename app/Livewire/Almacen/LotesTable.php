<?php

namespace App\Livewire\Almacen;

use App\Models\Lote;
use Livewire\Component;
use App\Models\Medicamento;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;


class LotesTable extends Component
{
    use WithPagination;

    public $filter = 'todos';
    public $cantidad;
    public $fecha_vencimiento;
    public $fecha_registro;
    public $origen;
    public $codigo_lote;
    public $presentacion;
    public $estatus;
    public $unidad;
    public $medida;
    public $nombre_medicamento;
    public $formView = false;
    public $lote = null;
    public $medicamento_busqueda = null;
    public $medicamentoSeleccionado; // Para almacenar el medicamento seleccionado
    public $LoteSeleccionado; // Para almacenar el medicamento seleccionado
    public $medicamentos; // Para almacenar el medicamento seleccionado
    public $editar = false;         // Para alternar entre los modos de edición y vista
    public $modal = false;  

    protected $listeners = ['render', 'loteEliminado' => 'reder']; // PARA REGACAR LA TABLA

    // Función para ver un medicamento
    public function ver($id)
    {
        session()->flash('error', 'si');
        try {
            session()->flash('error', 'si try');
            $this->LoteSeleccionado = Lote::findOrFail($id)->toArray();
            $this->medicamentoSeleccionado = Medicamento::findOrFail($this->LoteSeleccionado['medicamento_id'])->toArray();
            $this->medicamentos = Medicamento::all();
            $this->editar = false;
            $this->modal = true;
        } catch (\Exception $e) {
            dd($id);
            session()->flash('error', 'El medicamento no fue encontrado.');
        }
    }

    public function edit($id)
    {
        session()->flash('error', 'si');
        try {
            session()->flash('error', 'si try');
            $this->LoteSeleccionado = Lote::findOrFail($id)->toArray();
            $this->medicamentoSeleccionado = Medicamento::findOrFail($this->LoteSeleccionado['medicamento_id'])->toArray();
            $this->medicamentos = Medicamento::all();
            $this->editar = true;
            $this->modal = true;
        } catch (\Exception $e) {
            dd($id);
            session()->flash('error', 'El medicamento no fue encontrado.');
        }
    }   

    public function abrirModal()
    {
        $this->editar = true;
        $this->modal = true;
    }

    public function cerrar() {
        $this->reset(['medicamentoSeleccionado',
                    'LoteSeleccionado',]);
        $this->editar = false;
        $this->modal = false;
    }

    // Función para guardar los cambios realizados durante la edición
    public function guardar()
    {
        $this->validate([
            'medicamentoSeleccionado.nombre' => 'required|string|max:255',
            'medicamentoSeleccionado.presentacion' => 'nullable|string|max:255',
            'medicamentoSeleccionado.unidad' => 'nullable|numeric|max:255',
            'medicamentoSeleccionado.medida' => 'nullable|string|max:255',
            'medicamentoSeleccionado.cantidad_disponible' => 'required|integer|min:0',
        ]);
        
        if (isset($this->medicamentoSeleccionado['id'])) {
            // Actualizar un medicamento existente
            $medicamento = Medicamento::find($this->medicamentoSeleccionado['id']);
            $medicamento->update($this->medicamentoSeleccionado);
        } else {
            // Crear un nuevo medicamento
            Medicamento::create($this->medicamentoSeleccionado);
        }

        session()->flash('message', 'Medicamento guardado exitosamente.');
        $this->cerrar();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDelete', $id); // Simplificamos el envío del id
    }

    public function eliminar($id)
    {
        try {
            // Buscamos el lote por su ID
            $lote = Lote::findOrFail($id);
    
            // Eliminamos el lote
            $lote->delete();
    
            // Enviamos un mensaje de éxito
            session()->flash('message', 'Lote eliminado exitosamente.');
    
            // Emitimos un evento para actualizar la tabla en la vista
            $this->dispatch('loteEliminado');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si el lote no existe, mostramos un error
            session()->flash('error', 'No se encontró el lote.');
        } catch (\Exception $e) {
            // Capturamos cualquier otro error
            session()->flash('error', 'Ocurrió un error al intentar eliminar el lote.');
        }
    }
    
    
    public function render()
    {
        $query = Lote::query();
        
        // Filtro por estatus
        switch ($this->filter) 
        {
            case 'vencidos':
                $query->where('fecha_vencimiento', '<', Carbon::now());
                break;
            
            case 'por_vencer':
                $query->whereBetween('fecha_vencimiento', [Carbon::now(), Carbon::now()->addDays(30)]);
                break;
                
            case 'agotados':
            $query->where('cantidad', '=', 0);
            break;
            
            case 'por_agotar':
                $query->whereBetween('cantidad', [1, 30]);
                break;
        }

        $lotes = $query->paginate(10);
            
        return view('livewire.almacen.lotes-table', [
            'lotes' => $lotes,
        ]);
    }
}
 
    // protected $listeners = ['confirmDelete']; // Listener para manejar confirmación de eliminación
    
    // public function render()
    // {
    //     $query = Lote::query();

    //     if ($this->filter === 'vencidos') {
    //         $query->where('estatus', 'vencido');
    //     }

    //     $lotes = $query->paginate(10);

    //     return view('livewire.almacen.lotes-table', [
    //         'lotes' => $lotes,
    //     ]);