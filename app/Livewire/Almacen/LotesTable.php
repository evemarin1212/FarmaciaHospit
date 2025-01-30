<?php

namespace App\Livewire\Almacen;

use App\Models\Lote;
use Livewire\Component;
use App\Models\Medicamento;
use App\Models\presentacion;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;


class LotesTable extends Component
{
    use WithPagination;

    public $filter = 'todos';
    public $search = '';

    public $cantidad, $fecha_vencimiento, $fecha_registro, $origen, $codigo_lote;
    public $presentacion, $estatus, $unidad, $medida, $nombre_medicamento;

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
        try {
            $this->LoteSeleccionado = Lote::findOrFail($id)->toArray();
            $this->medicamentoSeleccionado = Medicamento::findOrFail($this->LoteSeleccionado['medicamento_id'])->toArray();
            $this->presentacion = presentacion::findOrFail($this->medicamentoSeleccionado['presentacion_id']);
            $this->medicamentos = Medicamento::all();
            $this->editar = false;
            $this->modal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'El medicamento no fue encontrado.');
        }
    }

    public function edit($id)
    {
        try {
            $this->LoteSeleccionado = Lote::findOrFail($id)->toArray();
            $this->medicamentoSeleccionado = Medicamento::findOrFail($this->LoteSeleccionado['medicamento_id'])->toArray();
            $this->presentacion = presentacion::findOrFail($this->medicamentoSeleccionado['presentacion_id']);
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
            'medicamentoSeleccionado.unidad' => 'nullable|numeric|max:255',
            'medicamentoSeleccionado.medida' => 'nullable|string|max:255',
            'LoteSeleccionado.presentacion' => 'nullable|string|max:255',
            'medicamentoSeleccionado.cantidad_disponible' => 'required|integer|min:0',
            'LoteSeleccionado.fecha_vencimiento' => 'required|date|after:today',
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
        $this->dispatch('render');
    }

    // 1. Obtener el medicamento relacionado al lote, lote->medicamento->ID
    // 2. Buscar el medicamento, Medicamento::findOrFail($id_Medicamento).
    // 3. Hacer un decremento en medicamento en cantidad_disponible con respecto a la cantidad del lote.
    // 4. Realizar el delete
    public function eliminar($id)
    {
        try {
            // Buscamos el lote por su ID
            $lote = Lote::findOrFail($id);

            // Obtenemos el medicamento relacionado al lote y su ID
            $medicamento_id = $lote->medicamento->id;

            // Buscamos el medicamento
            $medicamento = Medicamento::findOrFail($medicamento_id);

            // Restamos la cantidad del lote al medicamento
            $medicamento->cantidad_disponible -= $lote->cantidad;
            $medicamento->save();

            // Eliminamos el lote
            $lote->delete();

            // Enviamos un mensaje de éxito
            session()->flash('message', 'Lote eliminado exitosamente.');

            // Emitimos un evento para actualizar la tabla en la vista
            $this->dispatch('loteEliminado');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si el lote o el medicamento no existe, mostramos un error
            session()->flash('error', 'No se encontró el lote o el medicamento.');
        } catch (\Exception $e) {
            // Capturamos cualquier otro error
            session()->flash('error', 'Ocurrió un error al intentar eliminar el lote.');
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDelete', $id); // Simplificamos el envío del id
        $this->dispach('render');
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
        }

        // Busqueda por nombre de medicamento
    if (!empty($this->search)) {
        $query->whereHas('medicamento', function ($query) {
            $query->where('nombre', 'like', "%{$this->search}%");
        });
    }
        $lotes = $query->paginate(10);

        return view('livewire.almacen.lotes-table', [
            'lotes' => $lotes,
        ]);
    }
}