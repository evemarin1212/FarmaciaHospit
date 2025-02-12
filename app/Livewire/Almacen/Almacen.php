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
    public $origen; //oracion
    public $codigo_lote;
    public $presentacion;
    public $unidad;
    public $medida;
    public $nombre; //Primera letra

    public $formView = false;
    public $lote = null;

    public $ultimo_id;
    public $medicamento_select = null;
    public $search = '';
    public $filter = 'todos';

    public $tipo_presentacion = '';
    public $tipos_presentacion = [];
    public $tipo_presentacion_busqueda = '';
    public $select_presentacion = 'search'; // Controla si es "nuevo" o "search"
    public $nueva_presentacion = ''; // Almacena el nombre de la nueva presentación Oracion
    public $via_administracion; // Almacena el nombre de la nueva presentación

    public function mount()
    {
        $this->medicamentos = collect();
        $this->tipos_presentacion = Presentacion::pluck('tipo', 'via_administracion', 'id'); // Recuperar tipos de medicamento desde el modelo Presentacion
    }

    public function updatedTipoPresentacionBusqueda()
    {
        $this->tipos_presentacion = Presentacion::where('tipo', 'like', "%{$this->tipo_presentacion_busqueda}%")
        ->limit(5)
        ->pluck('tipo', 'via_administracion','id');
    }

    public function form()
    {
        $this->formView = true;
    }

    //Convertir primera letra de la oracion
    public function updatedNombre()
    {
        $this->nombre = ucfirst(trim($this->nombre));
    }
    public function updatedOrigen()
    {
        $this->origen = ucfirst(trim($this->origen));
    }
    public function updatedNuevaPresentacion()
    {
        $this->nueva_presentacion = ucfirst(trim($this->nueva_presentacion));
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
        $this->cantidad = '';
        $this->fecha_vencimiento = '';
        $this->origen = '';
        $this->codigo_lote = '';
        $this->presentacion = '';
        $this->unidad = '';
        $this->medida = '';
        $this->nombre = '';
        // $this->lote = null;
        // $this->medicamento_id = null;
        $this->select_medicamento = 'search';
        // $this->medicamento_select = '';
        $this->search = '';
        $this->tipo_presentacion = '';
        $this->tipo_presentacion_busqueda = '';
        $this->select_presentacion = 'search';
        $this->nueva_presentacion = '';
        $this->via_administracion;
    }

    public function save()
    {
        if( $this->select_medicamento === "nuevo" ){
            $this->validate([
                'cantidad' => 'required|integer|min:1',
                'fecha_vencimiento' => 'required|date|after:today',
                'origen' => 'required|string',
                'codigo_lote' => 'required|string|min:3',
                'nombre' => 'required|string|min:3', 
                'select_presentacion' => 'required|in:search,nuevo',
                'nueva_presentacion' => 'required_if:select_presentacion,nuevo|string|max:255|min:3',
                'via_administracion' => 'required_if:select_presentacion,nuevo|required',
                'tipo_presentacion' => 'required_if:select_presentacion,search|nullable|exists:presentacions,id',
                'medida' => 'required|string|min:2',
                'unidad' => 'required|numeric',
            ]);

        } else {
            $this->validate([
                'medicamento_id' => 'required|exists:medicamentos,id',
                'cantidad' => 'required|integer|min:1',
                'fecha_vencimiento' => 'required|date|after:today',
                'origen' => 'required|string|min:3',
                'codigo_lote' => 'required|string|min:3',
            ]);
        }

        if( $this->select_medicamento === "nuevo" ){
            // Si la opción es "nuevo", crea una nueva presentación
            if ($this->select_presentacion === 'nuevo') {
                $presentacion = Presentacion::create([
                    'tipo' => $this->nueva_presentacion,
                    'via_administracion' => $this->via_administracion,
                ]);
                // Asigna la nueva presentación al medicamento
                $this->tipo_presentacion = $presentacion->id;
            }
            $this->ultimo_id = Medicamento::create([
                'nombre' => $this->nombre,
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
