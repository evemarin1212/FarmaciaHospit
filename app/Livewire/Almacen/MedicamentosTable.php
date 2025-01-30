<?php

namespace App\Livewire\Almacen;

use Livewire\Component;
use App\Models\Medicamento;
use Livewire\WithPagination;

class MedicamentosTable extends Component
{
    use WithPagination;

    public $filter = 'todos';
    public $search = '';
    public $medicamentoSeleccionado = []; // Para almacenar el medicamento seleccionado
    public $editar = false;         // Para alternar entre los modos de edición y vista
    public $modal = false;         // Para alternar entre los modos de edición y vista

    protected $listeners = ['render' , 'confirmDelete', 'reder' => 'loteEliminado']; // Listener para manejar confirmación de eliminación

    public function mount($medicamentoId = null)
    {
        if ($medicamentoId) {
            // Cargar el medicamento existente si se proporciona un ID
            $this->medicamentoSeleccionado = Medicamento::find($medicamentoId);
        } else {
            // Crear una nueva instancia si no se proporciona un ID
            $this->medicamentoSeleccionado = new Medicamento();
        }
    }
    
    // Función para ver un medicamento
    public function ver($id)
    {
        $this->medicamentoSeleccionado = Medicamento::findOrFail($id)->toArray(); // Convierte el modelo en array
        $this->editar = false; // Modo vista
        $this->modal = true;   // Mostrar el modal
    }
    
    public function edit($id)
    {
        $this->medicamentoSeleccionado = Medicamento::findOrFail($id)->toArray(); // Carga el modelo directamente
        $this->editar = true;  // Activa el modo edición
        $this->modal = true;   // Muestra el modal

        // Debugging opcional
        // dd($this->medicamentoSeleccionado);
    }

    public function abrirModal()
    {
        $this->medicamentoSeleccionado = new Medicamento(); // Para nuevos medicamentos
        $this->editar = true;
        $this->modal = true;
    }

    public function cerrar() {
        $this->reset(['medicamentoSeleccionado']);
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

    public function eliminar($id)
    {
        $this->dispatch('confirmDelete', $id); // Simplificamos el envío del id
    }

    public function confirmDelete($id)
    {
        $medicamento = Medicamento::find($id); // Buscamos el medicamento
        if ($medicamento) { // Verificamos si existe antes de eliminar
            $medicamento->delete();
            session()->flash('message', 'Medicamento eliminado exitosamente.');
            $this->dispatch('medicamentoEliminado'); // Emitimos un evento para actualizar la tabla
        } else {
            session()->flash('error', 'No se encontró el medicamento.');
        }
        $this->dispatch('render');
    }

    public function render()
    {
        $query = Medicamento::query();

        // Filtro por estatus
        switch ($this->filter)
        {
            case 'todos':
                $query->where('cantidad_disponible', '>', 0);
                break;

            case 'agotados':
                $query->where('cantidad_disponible', '=', 0);
                break;

            case 'por_agotar':
                $query->whereBetween('cantidad_disponible', [1, 30]);
                break;

        }
        // Aplicar búsqueda y paginación
        $medicamentos = $query
            ->where('nombre', 'like', "%{$this->search}%")
            ->paginate(5);

        return view('livewire.almacen.medicamentos-table', [
            'medicamentos' => $medicamentos
        ]);

    }
}
