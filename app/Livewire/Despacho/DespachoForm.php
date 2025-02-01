<?php

namespace App\Livewire\Despacho;

use Livewire\Component;
use App\Models\Despacho;
use App\Models\Lote;
use App\Models\Paciente;
use App\Models\Medicamento;
use App\Models\DespachoSolicitado;
use Illuminate\Support\Facades\DB;
use App\Models\DespachoMedicamento;
use Illuminate\Support\Facades\Log;

class DespachoForm extends Component
{
    public $tipo_despacho = '';
    public $paciente_opcion = '';
    public $observacion = '';
    public $search = '';
    public $selectedPacienteId = null;

    public $paciente_nombre = '';
    public $paciente_apellido = '';
    public $paciente_dni = '';
    public $paciente_estatus = '';

    public $tipo_medicamento_busqueda = '';
    public $medicamento_seleccionado = '';
    public $medicamentos_selec_bus = [];
    public $medicamentos_selec = [];
    public $medicamento_solicitado = '';
    public $medicamentos_solicitados_bus = [];
    public $medicamentos_solicitados_selec = [];
    public $medicamento_id;
    public $cantidad_medicamento;
    public $medicamento_solicitado_id;
    public $cantidad_medicamento_solicitado;

    public $formView = false;

    public function mount()
    {
        $this->medicamentos_selec_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')->get();
        $this->medicamentos_solicitados_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')->get();
    }

    public function updatedTipoMedicamentoBusqueda()
    {
        if (empty($this->tipo_medicamento_busqueda)) {
            $this->medicamentos_selec_bus = [];
            return;
        }

        $this->medicamentos_selec_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')
            ->where('nombre', 'like', "%{$this->tipo_medicamento_busqueda}%")
            ->where('cantidad_disponible', '>', 0)
            ->limit(5)
            ->get();
    }

    public function updatedMedicamentoSolicitado()
    {
        if (empty($this->medicamento_solicitado)) {
            $this->medicamentos_solicitados_bus = [];
            return;
        }

        $this->medicamentos_solicitados_bus = Medicamento::select('id', 'nombre', 'medida', 'unidad')
            ->where('nombre', 'like', "%{$this->medicamento_solicitado}%")
            ->where('cantidad_disponible', '>', 0)
            ->limit(5)
            ->get();
    }

    protected $rules = [
        'tipo_despacho' => 'required',
        'paciente_opcion' => 'required',
        'medicamento_id' => 'required',
        'cantidad_medicamento' => 'required|integer|min:1',
        'observacion' => 'nullable|string',
    ];

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
        $this->tipo_despacho = '';
        $this->paciente_opcion = '';
        $this->observacion = '';
        $this->search = '';
        // $this->selectedPacienteId = null;
        $this->paciente_nombre = '';
        $this->paciente_apellido = '';
        $this->paciente_dni = '';
        $this->paciente_estatus = '';
        $this->medicamento_seleccionado = '';
        // $this->medicamentos_seleccionados = [];
        // $this->medicamento_solicitado = '';
        // $this->medicamento_id = null;
        $this->cantidad_medicamento = '';
        // $this->medicamento_solicitado_id = null;
        $this->cantidad_medicamento_solicitado = '';
    }

    private function obtenerPacienteId()
    {
        if ($this->paciente_opcion === 'nuevo') {
            // Verificar si el DNI ya existe en la base de datos
            $existingPaciente = Paciente::where('dni', $this->paciente_dni)->first();
            if ($existingPaciente) {
                session()->flash('error', 'El paciente con este DNI ya existe.');
                return null;
            }

            return Paciente::create([
                'nombre' => $this->paciente_nombre,
                'apellido' => $this->paciente_apellido,
                'dni' => $this->paciente_dni,
                'estatus' => $this->paciente_estatus,
            ])->id;
        }

        return $this->selectedPacienteId;
    }

    public function agregarMedicamento()
    {
        $this->validate([
            'medicamento_id' => 'required|nullable|exists:medicamentos,id',
            'cantidad_medicamento' => 'required|integer|min:1',
        ]);

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
            'medicamento_solicitado_id' => 'required|nullable|exists:medicamentos,id',
            'cantidad_medicamento_solicitado' => 'required|integer|min:1',
            'cantidad_medicamento' => 'required|integer|min:1',
        ]);

        $medicamentoSolicitado = Medicamento::find($this->medicamento_solicitado_id);
        if ($medicamentoSolicitado && $medicamentoSolicitado->cantidad_disponible >= $this->cantidad_medicamento_solicitado) {
            $this->medicamentos_solicitados_selec[] = [
                'id' => $medicamentoSolicitado->id,
                'nombre' => $medicamentoSolicitado->nombre,
                'unidad' => $medicamentoSolicitado->unidad,
                'medida' => $medicamentoSolicitado->medida,
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

    public function guardarDespacho()
    {
        DB::beginTransaction();
        
        $this->validate([
            'tipo_despacho' => 'required|in:emergencia,hospitalizado,quirofano',
        ]);

        
        if ($this->tipo_despacho != 'quirofano') {
            if($this->paciente_opcion === "nuevo"){
                $this->validate([
                    'paciente_nombre' => 'required_if:tipo_despacho,emergencia,hospitalizado|string',
                    'paciente_apellido' => 'required_if:tipo_despacho,emergencia,hospitalizado|string',
                    'paciente_dni' => 'required_if:tipo_despacho,emergencia,hospitalizado|string',
                    'paciente_estatus' => 'required_if:tipo_despacho,emergencia,hospitalizado',
                ]);
            }else{
                $this->validate([
                    'selectedPacienteId' => 'required_if:tipo_despacho,emergencia,hospitalizado|exists:pacientes,id',
                ]);
            }
            $pacienteId = $this->obtenerPacienteId();

            // ingresar observacion
            if ($this->tipo_despacho == 'emergencia') {
                $despacho = Despacho::create([
                    'tipo' => $this->tipo_despacho,
                    'paciente_id' => $pacienteId,
                    'observacion' => $this->observacion,
                    'fecha_pedido' => now(),
                ]);
            } else {
                $despacho = Despacho::create([
                    'tipo' => $this->tipo_despacho,
                    'paciente_id' => $pacienteId,
                    'fecha_pedido' => now(),
                ]);
            }

            foreach ($this->medicamentos_selec as $medicamento) {
                DespachoMedicamento::create([
                    'despacho_id' => $despacho->id,
                    'medicamento_id' => $medicamento['id'],
                    'cantidad' => $medicamento['cantidad'],
                ]);
                $medicamentoModel = Medicamento::find($medicamento['id']);
                $medicamentoModel->decrement('cantidad_disponible', $medicamento['cantidad']);
                // $this->actualizarLote($medicamento['id'], $medicamento['cantidad']);
            }

        } else {
            $despacho = Despacho::create([
                'tipo' => $this->tipo_despacho,
                'fecha_pedido' => now(),
            ]);

            foreach ($this->medicamentos_solicitados_selec as $medicamentoSolicitado) {
                $despachoMedicamento = DespachoMedicamento::create([
                    'despacho_id' => $despacho->id,
                    'medicamento_id' => $medicamentoSolicitado['id'],
                    'cantidad' => $medicamentoSolicitado['cantidad'],
                ]);
                DespachoSolicitado::create([
                    'despacho_id' => $despacho->id,
                    'medicamento_id' => $medicamentoSolicitado['id'],
                    'cantidad' => $medicamentoSolicitado['cantidad_despacho'],
                    'despacho_medicamento_id' => $despachoMedicamento->id,
                ]);
                $medicamentoModel = Medicamento::find($medicamentoSolicitado['id']);
                $medicamentoModel->decrement('cantidad_disponible', $medicamentoSolicitado['cantidad_despacho']);
                // $this->actualizarLote($medicamentoSolicitado['id'], $medicamentoSolicitado['cantidad_despacho']);
            }
        }
        DB::commit();
        $this->dispatch('alert', "¡El despacho se creó satisfactoriamente!");
        $this->dispatch('render');
        $this->cancelar();
        $this->resetForm();
    }

    // private function actualizarLote($medicamentoId, $cantidad)
    // {
    //     $lotes = Lote::where('medicamento_id', $medicamentoId)
    //         ->where('cantidad', '>', 0)
    //         ->orderBy('fecha_vencimiento', 'asc')
    //         ->get();
    //     foreach ($lotes as $lote) {
    //         if ($cantidad <= 0) break;
    //         $cantidadDecrementar = min($cantidad, $lote->cantidad);
    //         $lote->decrement('cantidad', $cantidadDecrementar);
    //         $cantidad -= $cantidadDecrementar;
    //     }
    // }

    public function render()
    {
        return view('livewire.despacho.despacho-form', [
            'pacientes' => Paciente::where('dni', 'like', "%{$this->search}%")->limit(3)->get(),
            'medicamentos' => Medicamento::where('nombre', 'like', "%{$this->search}%")->limit(3)->get(),
            // dd($this->search),
            'medicamentos' => Medicamento::all(),
        ]);
    }
}