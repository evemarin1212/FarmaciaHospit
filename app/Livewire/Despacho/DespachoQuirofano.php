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
        $this->dispatch('render');
    }

    // // Eliminar un despacho
    // public function eliminar($id)
    // {
    //     $this->cargarDespacho($id);
    //     $this->accion = 'eliminar';
    //     $this->modal = true;
    // }

    // public function eliminar($id)
    // {
    //     // Cargar el despacho seleccionado
    //     $this->cargarDespacho($id);
    //     // Iterar sobre los medicamentos asociados al despacho
    //     foreach ($this->MedicamentosSeleccionados as $medicamento) {
    //         // Recuperar el modelo del medicamento despachado
    //         $despachoMedicamento = DespachoMedicamento::findOrFail($medicamento['id']);
    //         if (!$despachoMedicamento) {
    //             continue; // Evita errores si el medicamento ya fue eliminado
    //         }
    //         // // Incrementar la cantidad en la tabla Lote
    //         // $lote = $despachoMedicamento->lote; // Asumiendo que el modelo DespachoMedicamento tiene relación con Lote
    //         // $lote->cantidad_medicamento += $despachoMedicamento->cantidad;
    //         // $lote->save();
    //         // Incrementar la cantidad en la tabla Medicamento
    //         // $medicamentoModel = Medicamento::findOrFail($lote->medicamento_id); // Relación con la tabla Medicamento
    //         $despachoMedicamento->medicamento->cantidad += $despachoMedicamento->cantidad;
    //         $despachoMedicamento->medicamento->save();
    //     }
    //     // Eliminar solo el despacho
    //     $despacho = Despacho::find($this->DespachoSeleccionado->id);
    //     if (!$despacho) {
    //         session()->flash('error', 'El despacho ya no existe.');
    //         return;
    //     }
    //     $this->DespachoSeleccionado->despachosMedicamentos()->delete();
    //     $despacho->delete();
    //     // Resetear variables y cerrar modal
    //     $this->modal = false;
    //     $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
    //     // Mensaje de confirmación
    //     session()->flash('message', 'Despacho eliminado exitosamente y cantidades restauradas.');
    //     $this->dispatch('render');
    // }

    // public function eliminar($id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->cargarDespacho($id);

    //         foreach ($this->MedicamentosSeleccionados as $medicamento) {
    //             $despachoMedicamento = DespachoMedicamento::find($medicamento['id']);

    //             if (!$despachoMedicamento) continue;

    //             $despachoMedicamento->medicamento->cantidad += $despachoMedicamento->cantidad;
    //             $despachoMedicamento->medicamento->save();
    //         }

    //         $despacho = Despacho::find($this->DespachoSeleccionado->id);
    //         if (!$despacho) {
    //             throw new \Exception("El despacho ya no existe.");
    //         }

    //         $this->DespachoSeleccionado->despachosMedicamentos()->delete();
    //         $despacho->delete();

    //         DB::commit();

    //         $this->modal = false;
    //         $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
    //         session()->flash('message', 'Despacho eliminado exitosamente y cantidades restauradas.');
    //         $this->dispatch('render');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         session()->flash('error', 'Error al eliminar el despacho: ' . $e->getMessage());
    //     }
    // }
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
    // public function confirmarEliminacion()
    // {
    //     $despacho = Despacho::findOrFail($this->DespachoSeleccionado->id);
    //     $despacho->medicamentos()->delete(); // Elimina los medicamentos asociados
    //     $despacho->delete(); // Elimina el despacho
    //     $this->modal = false;
    //     $this->reset(['accion', 'DespachoSeleccionado', 'MedicamentosSeleccionados']);
    //     session()->flash('message', 'Despacho eliminado exitosamente.');
    //     $this->dispatch('render');
    // }

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

        // $this->DespachoSeleccionado = Despacho::findOrFail($id);
        // $this->MedicamentosSeleccionados = $this->DespachoSeleccionado->despachosMedicamentos()->get()->toArray();
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