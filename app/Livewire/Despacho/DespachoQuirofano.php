<?php

namespace App\Livewire\Despacho;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use App\Models\DespachoMedicamento;
use App\Models\DespachoSolicitado;
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
    protected $listeners = ['render'];

    // Ver detalles del despacho
    public function ver($id)
    {
        $this->cargarDespacho($id);
        $this->accion = 'ver';
        $this->modal = true;
    }

    public function eliminar($id)
    {
        DB::beginTransaction();
        try {
            $this->cargarDespacho($id);
            
            if (!$this->DespachoSeleccionado) {
                throw new \Exception("El despacho no fue encontrado.");
            }
            // dd($id);
            foreach ($this->MedicamentosSeleccionados as $medicamento) {
                $despachoMedicamento = DespachoMedicamento::find($medicamento['id']);
                // dd($id);  dd($id);
                if (!$despachoMedicamento || !$despachoMedicamento->medicamento) continue;
                // dd($id);
                // Restaurar stock del medicamento
                $despachoMedicamento->medicamento->cantidad_disponible += $despachoMedicamento->cantidad;
                // dd($id);
                $despachoMedicamento->medicamento->save();
                // dd($id);
            }
            // dd($id);
            // Validar si el despacho aún existe antes de eliminarlo
            $despacho = Despacho::find($this->DespachoSeleccionado->id);
            if (!$despacho) {
                // dd($id);
                throw new \Exception("El despacho ya no existe.");
                // dd($id);
            }

            // Eliminar medicamentos asociados al despacho
            if ($this->DespachoSeleccionado) {
                // dd($id);
                $this->DespachoSeleccionado->despachosMedicamentos()->delete();
                // dd($id);
            }

            // Eliminar el despacho
            $despacho->delete();
            // dd($id);
            DB::commit();

            // Reiniciar variables y notificar éxito
            $this->modal = false;
            $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
            session()->flash('message', 'Despacho eliminado exitosamente y cantidades restauradas.');
            $this->emit('render');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al eliminar el despacho: ' . $e->getMessage());
        }
    }

    // Confirmar la eliminación de un despacho y sus medicamentos
    public function confirmarEliminacion($id)
    {
        $this->DespachoSeleccionado = Despacho::find($id);
        if (!$this->DespachoSeleccionado) {
            session()->flash('error', 'El despacho ya no existe.');
            return;
        }

        $this->accion = 'eliminar';
        $this->modal = true;
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