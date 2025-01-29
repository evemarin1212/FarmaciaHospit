<?php
namespace App\Livewire\Almacen;

use App\Models\Lote;
use App\Models\Medicamento;
use App\Models\Presentacion;
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
    // public $medicamento_busqueda = null;

    public $medicamento_select = null;
    public $search = '';
    public $filter = 'todos';

    public $tipo_medicamento = '';
    public $tipos_medicamento = [];
    public $tipo_medicamento_busqueda = '';
    public $select_presentacion = ''; // Controla si es "nuevo" o "search"
    public $nueva_presentacion = ''; // Almacena el nombre de la nueva presentación
    
    protected $rules = [
        'cantidad' => 'required|integer|min:1',
        'fecha_vencimiento' => 'required|date',
        'origen' => 'required|string',
        'codigo_lote' => 'required|string',
        'select_presentacion' => 'required|in:search,nuevo',
        'nueva_presentacion' => 'required_if:select_presentacion,nuevo|string|max:255',
        'tipo_medicamento' => 'required_if:select_presentacion,search|exists:presentacions,id',
    ];

    
    public function mount()
    {
        $this->medicamentos = collect();
        $this->tipos_medicamento = Presentacion::pluck('tipo', 'id'); // Recuperar tipos de medicamento desde el modelo Presentacion
    }
    
    public function updatedTipoMedicamentoBusqueda()
    {
        $this->tipos_medicamento = Presentacion::where('tipo', 'like', "%{$this->tipo_medicamento_busqueda}%")
                                            ->pluck('tipo', 'id');
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function form()
    {
        $this->formView = true;
    }

    // public function editDetails($id)
    // {
    //     $this->formView = true;
    //     $this->lote = Lote::with('medicamento')->findOrFail($id);
    //     $this->medicamento_busqueda = $this->lote->medicamento;
    // }

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
        $this->resetalm();
    }

    private function resetalm(){
        $this->medicamento_id = null;
        $this->medicamentos = '';
        $this->select_medicamento = '';
        $this->cantidad = '';
        $this->fecha_vencimiento = '';
        $this->origen = '';
        $this->codigo_lote = '';
        $this->presentacion = '';
        $this->unidad = '';
        $this->medida = '';
        $this->nombre = '';
        $this->lote = null;
        $this->medicamento_select = '';
        $this->search = '';
        $this->tipo_medicamento = '';
        $this->tipos_medicamento = [];
        $this->tipo_medicamento_busqueda = '';
        $this->select_presentacion = 'search';
        $this->nueva_presentacion = '';
    }

    public function save()
    {
        $this->validate();

        // Si la opción es "nuevo", crea una nueva presentación
        if ($this->select_presentacion === 'nuevo') {
            $presentacion = Presentacion::create([
                'tipo' => $this->nueva_presentacion,
            ]);

            // Asigna la nueva presentación al medicamento
            $this->tipo_medicamento = $presentacion->id;
        }

        // Continúa con el flujo de guardado de medicamentos
        $medicamento = Medicamento::create([
            'nombre' => $this->nombre,
            'presentacion_id' => $this->tipo_medicamento,
            'unidad' => $this->unidad,
            'medida' => $this->medida,
            'cantidad_disponible' => $this->cantidad,
            'estatus' => 'Disponible',
        ]);

        // Crea el lote
        Lote::create([
            'medicamento_id' => $medicamento->id,
            'cantidad' => $this->cantidad,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_registro' => now(),
            'origen' => $this->origen,
            'estatus' => 'disponible',
            'codigo_lote' => $this->codigo_lote,
        ]);

        $this->dispatch('alert', "¡El medicamento se creó satisfactoriamente!");
        $this->cancelar();
        $this->dispatch('render');
    }


    public function render()
    {
        return view('livewire.almacen.almacen', [
        'medicamentos' => Medicamento::where('nombre', 'like', "%{$this->search}%")->limit(3)->get(),
        ]);
    }
}
