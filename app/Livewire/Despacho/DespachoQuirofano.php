<?php

namespace App\Livewire\Despacho;

use Livewire\Component;
use App\Models\Despacho;
use Livewire\WithPagination;
use App\Models\DespachoSolicitado;
use Illuminate\Support\Facades\DB;
use App\Models\DespachoMedicamento;
use Illuminate\Support\Facades\Log;
Use App\Models\Medicamento;

class DespachoQuirofano extends Component
{
    use WithPagination;

    public $filter = 'todos';
    public $modal = false;
    public $accion = ''; // Puede ser 'ver', 'editar' o 'eliminar'
    public $DespachoSeleccionado;
    public $MedicamentosSeleccionados = []; // Para almacenar los medicamentos asociados al despacho
    protected $paginationTheme = 'tailwind';
    protected $listeners = ['render', 'eliminardespacho'];


    // Ver detalles del despacho
    public function ver($id)
    {
        $this->cargarDespacho($id);
        $this->accion = 'ver';
        $this->modal = true;
    }

    public function eliminardespacho($id)
    {
        Log::info("Intentando eliminar el despacho con ID: " . $id);
        DB::beginTransaction();
        try {
            $this->cargarDespacho($id);
            
            if (!$this->DespachoSeleccionado) {
                throw new \Exception("El despacho no fue encontrado.");
            }
            foreach ($this->MedicamentosSeleccionados as $medicamento) {
                $despachoMedicamento = DespachoMedicamento::find($medicamento['id']);
                if (!$despachoMedicamento || !$despachoMedicamento->medicamento) continue;
                // Restaurar stock del medicamento
                $despachoMedicamento->medicamento->cantidad_disponible += $despachoMedicamento->cantidad;
                $despachoMedicamento->medicamento->save();
            }
            // Validar si el despacho aún existe antes de eliminarlo
            $despacho = Despacho::find($this->DespachoSeleccionado->id);
            if (!$despacho) {
                throw new \Exception("El despacho ya no existe.");
            }

            // Eliminar medicamentos asociados al despacho
            $this->DespachoSeleccionado->despachosMedicamentos()->delete();

            // Eliminar el despacho
            $despacho->delete();
            DB::commit();

            Log::info("Despacho eliminado correctamente.");
            // Reiniciar variables y notificar éxito
            $this->dispatch('render');
            session()->flash('success', 'Despacho eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar el despacho: " . $e->getMessage());
            session()->flash('error', 'Error al eliminar el despacho: ' . $e->getMessage());
        }
    }

    // Confirmar la eliminación de un despacho y sus medicamentos
    public function confirmarEliminacion($message, $id)
    {
        $this->DespachoSeleccionado = Despacho::find($id);
        if (!$this->DespachoSeleccionado) {
            $this->dispatch('error', 'El despacho ya no existe.');
            return;
        }
        $this->dispatch('ConfirmarEliminar', $message, $id);
    }

    // Cerrar modal
    public function cerrar()
    {
        $this->modal = false;
        $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
    }

    // Carga un despacho y sus medicamentos
    private function cargarDespacho($id)
    {
        $this->DespachoSeleccionado = Despacho::with('despachosMedicamentos.medicamento')->findOrFail($id);
        $this->MedicamentosSeleccionados = $this->DespachoSeleccionado->despachosMedicamentos;
    }

    public function render()
    {
        $query = Despacho::where('tipo', 'quirofano');

        if ($this->filter === 'recientes') {
            $query->orderBy('created_at', 'desc');
        }

        return view('livewire.despacho.despacho-quirofano', [
            'despachos' => $query->paginate(10),
        ]);
    }
}