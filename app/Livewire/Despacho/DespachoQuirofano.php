<?php

namespace App\Livewire\Despacho;

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

    // Editar despacho
    public function editar($id)
    {
        $this->cargarDespacho($id);
        $this->accion = 'editar';
        $this->modal = true;
    }

    // Guardar los cambios realizados en un despacho
    public function guardarCambios()
    {
        foreach ($this->MedicamentosSeleccionados as $key => $medicamento) {
            $this->validate([
                "MedicamentosSeleccionados.{$key}.cantidad" => 'required|integer|min:1',
            ]);

            // Actualiza el medicamento despachado
            $medicamentoModel = DespachoMedicamento::findOrFail($medicamento['id']);
            $medicamentoModel->update([
                'cantidad' => $medicamento['cantidad'],
            ]);
        }

        $this->modal = false;
        $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
        session()->flash('message', 'Cambios guardados exitosamente.');
        $this->emit('render');
    }

    // Eliminar un despacho
    public function eliminar($id)
    {
        $this->cargarDespacho($id);
        $this->accion = 'eliminar';
        $this->modal = true;
    }

    // Confirmar la eliminación de un despacho y sus medicamentos
    public function confirmarEliminacion()
    {
        $despacho = Despacho::findOrFail($this->DespachoSeleccionado->id);
        $despacho->medicamentos()->delete(); // Elimina los medicamentos asociados
        $despacho->delete(); // Elimina el despacho

        $this->modal = false;
        $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
        session()->flash('message', 'Despacho eliminado exitosamente.');
        $this->dispatch('render');
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
        $this->DespachoSeleccionado = Despacho::findOrFail($id);
        $this->MedicamentosSeleccionados = $this->DespachoSeleccionado->despachosMedicamentos()->get()->toArray();
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