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
    protected $listeners = ['render', 'eliminarNormal'];

    public function ver($id)
    {
        $this->DespachoSeleccionado = Despacho::findOrFail($id);
        $this->modal = true;
    }

    public function cerrar()
    {
        $this->modal = false;
        $this->DespachoSeleccionado = null;
    }

    public function eliminarNormal($despachoId)
    {
        try {
            // Buscar el despacho por ID
            $despacho = Despacho::findOrFail($despachoId);

            // Incrementar la cantidad de los medicamentos en la tabla 'medicamentos'
            foreach ($despacho->medicamentos as $medicamento) {
                $medicamento->cantidad_disponible += $medicamento->pivot->cantidad; // Incrementar la cantidad
                $medicamento->save(); // Guardar el cambio en la base de datos
            }

            // Eliminar el despacho y sus relaciones
            $despacho->medicamentos()->detach(); // Eliminar registros de la tabla pivote
            $despacho->delete(); // Eliminar el despacho

            // Emitir un mensaje de éxito
            $this->dispatch('notificacion', [
                'mensaje' => 'despacho eliminado con exito normal.',
                'tipo' => 'success'
            ]);
            $this->dispatch('render');

        } catch (\Exception $e) {
            // Emitir un mensaje de error en caso de excepción
            $this->dispatch('alert', 'Error al eliminar el despacho: ' . $e->getMessage());
        }
    }

    public function confirmarEliminacion($mensaje, $despachoId)
    {
        $this->dispatch('confirmar-eliminacion', ['menssage' => $mensaje,
            'despachoId' => $despachoId, 
            'metodo' => 'eliminarNormal'
        ]);
    }

    public function render()
    {
        // Eager Loading de la relación 'paciente' para evitar acceso a propiedades de null
        $despachos = Despacho::query()
            ->where('tipo', '!=', 'quirofano')
            ->with('paciente')
            ->orderBy('created_at', 'desc');  // Eager loading de la relación 'paciente'

        if ($this->filter === 'recientes') {
            $despachos->orderBy('created_at', 'desc');
        }

        return view('livewire.despacho.despacho-table', [
            'despachos' => $despachos->paginate(10),
        ]);
    }
}
