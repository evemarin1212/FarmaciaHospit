<?php
namespace App\Livewire\Almacen;

use App\Models\Lote;
use App\Models\Medicamento;
use Livewire\Component;
use Livewire\WithPagination;

class Almacen extends Component
{
    use WithPagination;
    
    public $medicamento_id;
    public $medicamentos;
    public $select_medicamento;
    public $cantidad;
    public $fecha_vencimiento;
    public $origen;
    public $codigo_lote;
    public $presentacion;
    public $unidad;
    public $medida;
    public $nombre;
    public $formView = false;
    public $lote = null;
    public $medicamento_busqueda = null;
    

    public $medicamento_select = null;
    public $search = '';
    public $filter = 'todos';

    protected $rules = [
        'cantidad' => 'required|integer|min:1',
        'fecha_vencimiento' => 'required|date',
        'origen' => 'required|string',
        'codigo_lote' => 'required|string',
    ];

    public function mount()
    {
        $this->medicamentos = collect();
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function form()
    {
        $this->formView = true;
    }

    public function editDetails($id)
    {
        $this->formView = true;
        $this->lote = Lote::with('medicamento')->findOrFail($id);
        $this->medicamento_busqueda = $this->lote->medicamento;
    }

    public function updatedSearch()
    {
        $this->medicamento_select = null;
        $this->medicamentos = Medicamento::where('nombre', 'like', "%{$this->search}%")
                                         ->limit(5)
                                         ->get(); // Esto devuelve una colección
    }
    
    public function selectSearch($id)
    {
        $this->medicamento_select = $id;
        $this->search = '';
        $this->select_medicamento = 'search';
        // $medicamento_select = Medicamento::findOrFail($id);
        // // $this->selectedMedicamento = $medicamento_select;
        // $this->search = $medicamento_select->nombre; // Actualizar el campo de búsqueda con el nombre seleccionado
        // // $this->medicamentos = []; // Limpiar la lista de resultados
    }

    public function cancelar()
    {
        $this->formView = false;
        $this->reset([
            'medicamento_id',
            'cantidad',
            'fecha_vencimiento',
            'origen',
            'codigo_lote',
            'presentacion',
            'unidad',
            'medida',
            'nombre',
        ]);
    }

    public function save()
    {
        $this->validate(
            $this->select_medicamento === "nuevo"
            ? array_merge($this->rules, [
                'nombre' => 'required|string',
                'presentacion' => 'required|string',
                'unidad' => 'required|numeric',
                'medida' => 'required|string',
                ])
                : array_merge($this->rules, [
                    'search' => 'required|exists:medicamentos,nombre',
                    ])
                );

                // dd($this->medicamento_id, $this->codigo_lote, $this->nombre, $this->presentacion, $this->medida, $this->unidad,  $this->cantidad, $this->fecha_vencimiento, $this->origen);
        if ($this->select_medicamento === "nuevo") {
            $medicamento = Medicamento::create([
                'nombre' => $this->nombre,
                'presentacion' => $this->presentacion,
                'unidad' => $this->unidad,
                'medida' => $this->medida,
                'cantidad_disponible' => $this->cantidad,
                'estatus' => 'Disponible',
            ]);
        } else {

            $medicamento = Medicamento::findOrFail($this->medicamento_id);
            $medicamento->increment('cantidad_disponible', $this->cantidad);
        }

        return $this->medicamento_select;

        Lote::create([
            'medicamento_id' => $medicamento->id,
            'cantidad' => $this->cantidad,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_registro' => now(),
            'origen' => $this->origen,
            'estatus' => 'disponible',
            'codigo_lote' => $this->codigo_lote,
        ]);
        // $this->formView = false;

        $this->dispatch('render'); // ... POR ESTO SE RECARGA TODA LA VISTA
        $this->dispatch('alert', "¡El medicamento se creó satisfactoriamente!");
        $this->cancelar();
        // session()->flash('message', 'Lote agregado con éxito.');
    }

    public function render()
    {
        return view('livewire.almacen.almacen');
    }
}
