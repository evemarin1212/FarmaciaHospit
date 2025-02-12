<?php

namespace App\Livewire\Despacho;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\{Despacho, Lote, Paciente, Medicamento, DespachoSolicitado, DespachoMedicamento};

class DespachoForm extends Component
{
    public $tipo_despacho = '', $paciente_opcion = 'search', $observacion = '', $search = '';
    public $selectedPacienteId = null;

    public $paciente_nombre = '', $paciente_apellido = '', $nacionalidad = 'V', $paciente_dni = '', $paciente_estatus = '';

    public $tipo_medicamento_busqueda = '', $medicamento_seleccionado = '';
    public $medicamentos_selec_bus = [], $medicamentos_selec = [];
    public $medicamento_solicitado = '', $medicamentos_solicitados_bus = [], $medicamentos_solicitados_selec = [];

    public $medicamento_id, $cantidad_medicamento;
    public $medicamento_solicitado_id, $cantidad_medicamento_solicitado;

    public $formView = false;

    protected $rules = [
        'tipo_despacho' => 'required|in:emergencia,hospitalizado,quirofano',
        'paciente_opcion' => 'required',
        'medicamento_id' => 'required_if:tipo_despacho,emergencia,hospitalizado|exists:medicamentos,id',
        'medicamentos_selec_bus' => 'required_if:tipo_despacho,quirofano|exists:medicamentos,id',
        'cantidad_medicamento' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->medicamentos_selec_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')->get();
        $this->medicamentos_solicitados_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')->get();
    }

    public function updatedTipoMedicamentoBusqueda()
    {

        $this->medicamentos_selec_bus = $this->buscarMedicamentos($this->tipo_medicamento_busqueda);
    }
    //Convertir primera letra de la oracion
    public function updatedPacienteNombre()
    {
        $this->paciente_nombre = ucfirst(trim($this->paciente_nombre));
    }
    public function updatedPacienteApellido()
    {
        $this->paciente_apellido = ucfirst(trim($this->paciente_apellido));
    }
    public function updatedObservacion()
    {
        $this->observacion = ucfirst(trim($this->observacion));
    }

    public function updatedMedicamentoSolicitado()
    {
        $this->medicamentos_solicitados_bus = $this->buscarMedicamentos($this->medicamento_solicitado);
    }

    private function buscarMedicamentos($nombre)
    {
        return empty($nombre) ? [] : Medicamento::where('nombre', 'like', "%{$nombre}%")
            ->where('cantidad_disponible', '>', 0)
            ->select('id', 'nombre', 'medida', 'unidad')
            ->limit(5)
            ->get();
    }

    public function updatedSearch()
    {
        $this->selectedPacienteId = null;
    }

    public function selectPaciente($id)
    {
        $this->selectedPacienteId = $id;
        $this->search = '';
        $this->paciente_opcion = 'search';
    }

    public function form()
    {
        $this->formView = true;
    }

    public function cancelar()
    {
        $this->formView = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        
        $this->reset();
    }

    private function obtenerPacienteId()
    {
        if ($this->paciente_opcion === 'nuevo') {
            $dni = $this->nacionalidad . "-" . $this->paciente_dni;
            return Paciente::firstOrCreate(
                ['dni' => $dni],
                [
                    'nombre' => $this->paciente_nombre,
                    'apellido' => $this->paciente_apellido,
                    'estatus' => $this->paciente_estatus
                ]
            )->id;
        }

        return $this->selectedPacienteId;
    }

    public function agregarMedicamento()
    {
        $this->validate(['medicamento_id' => 'required|exists:medicamentos,id', 'cantidad_medicamento' => 'required|integer|min:1']);

        $medicamento = Medicamento::find($this->medicamento_id);

        if ($medicamento && $medicamento->cantidad_disponible >= $this->cantidad_medicamento) {
            $this->medicamentos_selec[] = [
                'id' => $medicamento->id,
                'nombre' => $medicamento->nombre,
                'unidad' => $medicamento->unidad,
                'medida' => $medicamento->medida,
                'cantidad' => $this->cantidad_medicamento,
            ];
        } else {
            session()->flash('error', 'No hay suficiente inventario para este medicamento.');
        }
    }

    public function agregarMedicamentoSolicitado()
    {
        $this->validate([
            'medicamento_solicitado_id' => 'required|exists:medicamentos,id',
            'cantidad_medicamento_solicitado' => 'required|integer|min:1',
            'cantidad_medicamento' => 'required|integer|min:1',
        ]);

        $medicamento = Medicamento::find($this->medicamento_solicitado_id);

        if ($medicamento && $medicamento->cantidad_disponible >= $this->cantidad_medicamento_solicitado) {
            $this->medicamentos_solicitados_selec[] = [
                'id' => $medicamento->id,
                'nombre' => $medicamento->nombre,
                'unidad' => $medicamento->unidad,
                'medida' => $medicamento->medida,
                'cantidad' => $this->cantidad_medicamento_solicitado,
                'cantidad_despacho' => $this->cantidad_medicamento,
            ];
        } else {
            session()->flash('error', 'No hay suficiente inventario para este medicamento.');
        }
    }

    public function eliminarMedicamento($index)
    {
        unset($this->medicamentos_selec[$index]);
        $this->medicamentos_selec = array_values($this->medicamentos_selec);
    }

    public function eliminarMedicamentoSolicitado($index)
    {
        unset($this->medicamentos_solicitados_selec[$index]);
        $this->medicamentos_solicitados_selec = array_values($this->medicamentos_solicitados_selec);
    }

    private function validatePaciente()
    {
        if ($this->paciente_opcion === 'nuevo') {
            $this->validate([
                'paciente_nombre' => 'required|string',
                'paciente_apellido' => 'required|string',
                'paciente_estatus' => 'required',
                'nacionalidad' => 'required',
                'paciente_dni' => 'required|unique:pacientes,dni|numeric|max:99999999|min:99999',
            ]);
            Log::info("Intentando eliminar el despacho con ID: " . $this->nacionalidad);
        } else {
            $this->validate([
                'selectedPacienteId' => 'required|exists:pacientes,id',
            ]);
        }
    }


    public function guardarDespacho()
    {
        DB::beginTransaction();
    
        $this->validate(['tipo_despacho' => 'required|in:emergencia,hospitalizado,quirofano']);
    
        $despachoData = [
            'tipo' => $this->tipo_despacho,
            'fecha_pedido' => now(),
        ];

        if ($this->tipo_despacho !== 'quirofano') {
            $this->validatePaciente();
            $this->validate(['medicamentos_selec' => 'required']);
            $despachoData['paciente_id'] = $this->obtenerPacienteId();
        } else {
            $this->validate(['medicamentos_solicitados_selec' => 'required']);
            if (empty($this->medicamentos_solicitados_selec)) {
                session()->flash('error', 'Debe solicitar al menos un medicamento para el quirófano.');
                DB::rollBack();
                return;
            }
        }
        if ($this->tipo_despacho === 'emergencia' && $this->tipo_despacho === 'quirofano') {
            $this->validate(['observacion' => 'required|string|min:5|max:150']);
            $despachoData['observacion'] = $this->observacion;
        }
        $despacho = Despacho::create($despachoData);

        // Procesar medicamentos según el tipo de despacho
        if ($this->tipo_despacho === 'quirofano') {
            $this->procesarMedicamentosSolicitados($despacho);
        } else {
            $this->procesarMedicamentos($despacho);
        }

        DB::commit();
        $this->reset();
        $this->dispatch('render');
        $this->dispatch('alert', "¡El despacho se creó satisfactoriamente!");
    }


    private function procesarMedicamentos($despacho)
    {
        foreach ($this->medicamentos_selec as $medicamento) {
            DespachoMedicamento::create([
                'despacho_id' => $despacho->id,
                'medicamento_id' => $medicamento['id'],
                'cantidad' => $medicamento['cantidad'],
            ]);
            Medicamento::find($medicamento['id'])->decrement('cantidad_disponible', $medicamento['cantidad']);
        }
    }

    private function procesarMedicamentosSolicitados($despacho)
    {
        foreach ($this->medicamentos_solicitados_selec as $medicamento) {
            $despachomedicamento = DespachoMedicamento::create([
                'despacho_id' => $despacho->id,
                'medicamento_id' => $medicamento['id'],
                'cantidad' => $medicamento['cantidad_despacho'],
            ]);
            DespachoSolicitado::create([
                'despacho_id' => $despacho->id,
                'medicamento_id' => $medicamento['id'],
                'cantidad' => $medicamento['cantidad'],
                'despacho_medicamento_id' => $despachomedicamento->id,
            ]);
            Medicamento::find($medicamento['id'])->decrement('cantidad_disponible', $medicamento['cantidad_despacho']);

        }
    }

    public function render()
    {
        return view('livewire.despacho.despacho-form', [
            'pacientes' => Paciente::where('dni', 'like', "%{$this->search}%")->limit(3)->get(),
            'medicamentos' => Medicamento::all(),
        ]);
    }
}
