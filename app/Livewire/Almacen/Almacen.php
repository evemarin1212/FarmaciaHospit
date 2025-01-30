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

    public $ultimo_id;
    public $medicamento_select = null;
    public $search = '';
    public $filter = 'todos';

    public $tipo_presentacion = '';
    public $tipos_presentacion = [];
    public $tipo_presentacion_busqueda = '';
    public $select_presentacion = ''; // Controla si es "nuevo" o "search"
    public $nueva_presentacion = ''; // Almacena el nombre de la nueva presentación

    public function mount()
    {
        $this->medicamentos = collect();
        $this->tipos_presentacion = Presentacion::pluck('tipo', 'id'); // Recuperar tipos de medicamento desde el modelo Presentacion
    }

    public function updatedTipoPresentacionBusqueda()
    {
        $this->tipos_presentacion = Presentacion::where('tipo', 'like', "%{$this->tipo_presentacion_busqueda}%")
        ->limit(5)
        ->pluck('tipo', 'id');
    }

    public function form()
    {
        $this->formView = true;
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
        $this->medicamento_id = $id;
        $this->search = '';
        $this->select_medicamento = 'search';
    }

    public function cancelar()
    {
        $this->formView = false;
        $this->resetalm();
    }

    private function resetalm(){
        $this->medicamento_id = null;
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
        $this->tipo_presentacion = '';
        $this->tipo_presentacion_busqueda = '';
        $this->select_presentacion = 'search';
        $this->nueva_presentacion = '';
    }

    public function save()
    {
        if( $this->medicamento_id === "nuevo" ){
            $this->validate([
                'cantidad' => 'required|integer|min:1',
                'fecha_vencimiento' => 'required|date|after:today',
                'origen' => 'required|string',
                'codigo_lote' => 'required|string',
                'nombre' => 'required|string',
                'presentacion' => 'required|string','select_presentacion' => 'required|in:search,nuevo',
                'nueva_presentacion' => 'required_if:select_presentacion,nuevo|string|max:255',
                'tipo_presentacion' => 'required_if:select_presentacion,search|exists:presentacions,id',
                'medida' => 'required|numeric',
                'unidad' => 'required|string',
            ]);
            
        } else {
            $this->validate([
                'medicamento_id' => 'required|exists:medicamentos,id',
                'cantidad' => 'required|integer|min:1',
                'fecha_vencimiento' => 'required|date|after:today',
                'origen' => 'required|string',
                'codigo_lote' => 'required|string',
            ]);
        }

        if( $this->medicamento_id === "nuevo" ){
            // Si la opción es "nuevo", crea una nueva presentación
            if ($this->select_presentacion === 'nuevo') {
                $presentacion = Presentacion::create([
                    'tipo' => $this->nueva_presentacion,
                ]);
                // Asigna la nueva presentación al medicamento
                $this->tipo_presentacion = $presentacion->id;
            }
            $this->ultimo_id = Medicamento::create([
                'nombre' => $this->nombre_medicamento,
                'presentacion_id' => $this->tipo_presentacion,
                'unidad' => $this->unidad,
                'medida' => $this->medida,
                'cantidad_disponible' => $this->cantidad,
                'estatus' => 'Disponible',
            ])->id;

            Lote::create([
                'medicamento_id' => $this->ultimo_id,
                'cantidad' => $this->cantidad,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'fecha_registro' => now(),
                'origen' => $this->origen,
                'estatus' => 'disponible',
                'codigo_lote' => $this->codigo_lote,
            ]);
        } else {
            Lote::create([
                'medicamento_id' => $this->medicamento_id,
                'cantidad' => $this->cantidad,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'fecha_registro' => now(),
                'origen' => $this->origen,
                'estatus' => 'disponible',
                'codigo_lote' => $this->codigo_lote,
            ]);
            $medicamento = Medicamento::findOrFail($this->medicamento_id);
            $medicamento->cantidad_disponible += $this->cantidad;
            $medicamento->save();
        }

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
