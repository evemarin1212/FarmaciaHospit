<?php

namespace App\Livewire\Despacho;

use Livewire\Component;
use App\Models\Despacho;
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

    public $paciente_data_nombre = '';
    public $paciente_data_apellido = '';
    public $paciente_data_dni = '';
    public $paciente_data_estatus = '';

    public $medicamentos_seleccionados = [];
    public $medicamentos_solicitados = [];
    public $medicamento_id;
    public $cantidad_medicamento;
    public $medicamento_solicitado_id;
    public $cantidad_medicamento_solicitado;

    public $formView = false;

    protected function rules()
    {
        return [
            'medicamento_id' => 'required',
            'cantidad_medicamento' => 'required|integer|min:1',
            'tipo_despacho' => 'required',
            'paciente_opcion' => 'required',
        ];
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
    }

    private function obtenerPacienteId()
    {
        if ($this->paciente_opcion === 'nuevo') {
            return Paciente::create([
                'nombre' => $this->paciente_data_nombre,
                'apellido' => $this->paciente_data_apellido,
                'dni' => $this->paciente_data_dni,
                'estatus' => $this->paciente_data_estatus,
            ])->id;
        }
        return $this->selectedPacienteId;
    }

    public function agregarMedicamento()
    {
        $this->validate([
            'medicamento_id' => 'required',
            'cantidad_medicamento' => 'required|integer|min:1',
        ]);

        $medicamento = Medicamento::find($this->medicamento_id);
        if ($medicamento && $medicamento->cantidad_disponible >= $this->cantidad_medicamento) {
            $this->medicamentos_seleccionados[] = [
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
            'medicamento_solicitado_id' => 'required',
            'cantidad_medicamento_solicitado' => 'required|integer|min:1',
            'cantidad_medicamento' => 'required|integer|min:1',
        ]);

        $medicamentoSolicitado = Medicamento::find($this->medicamento_solicitado_id);
        if ($medicamentoSolicitado && $medicamentoSolicitado->cantidad_disponible >= $this->cantidad_medicamento_solicitado) {
            $this->medicamentos_solicitados[] = [
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
        unset($this->medicamentos_seleccionados[$index]);
        $this->medicamentos_seleccionados = array_values($this->medicamentos_seleccionados);
    }

    public function eliminarMedicamentoSolicitado($index)
    {
        unset($this->medicamentos_solicitados[$index]);
        $this->medicamentos_solicitados = array_values($this->medicamentos_solicitados);
    }

    public function guardarDespacho()
    {

        DB::beginTransaction();

        try {
            if($this->tipo_despacho != 'quirofano') {
                $this->validate([
                    'tipo_despacho' => 'required',
                    'pacienteId' => 'required',
                ]);
            
                $pacienteId = $this->paciente_opcion === 'nuevo'
                    ? Paciente::create([
                        'nombre' => $this->paciente_data_nombre,
                        'apellido' => $this->paciente_data_apellido,
                        'dni' => $this->paciente_data_dni,
                        'estatus' => $this->paciente_data_estatus,
                    ])->id
                    : $this->selectedPacienteId;

                $despacho = Despacho::create([
                    'tipo' => $this->tipo_despacho,
                    'paciente_id' => $pacienteId,
                    'fecha_pedido' => now(),
                ]);

                foreach ($this->medicamentos_seleccionados as $medicamento) {
                    DespachoMedicamento::create([
                        'despacho_id' => $despacho->id,
                        'medicamento_id' => $medicamento['id'],
                        'cantidad' => $medicamento['cantidad'],
                    ]);

                    // Si el tipo de despacho es "Emergencia", se agrega observación
                    if ($this->tipo_despacho === 'emergencia') {
                        $despacho->update(['observacion' => $this->observacion]);
                    }
                }

            // Si hay medicamentos solicitados (Quirófano)
            }else {
                $this->validate([
                    'tipo_despacho' => 'required',
                ]);

                $despacho = Despacho::create([
                    'tipo' => $this->tipo_despacho,
                    'fecha_pedido' => now(),
                ]);

                foreach ($this->medicamentos_solicitados as $medicamentoSolicitado) {
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
                }
            }

            DB::commit();

            session()->flash('message', 'Despacho guardado exitosamente.');
            $this->formView = false;
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el despacho: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al guardar el despacho.'. $e->getMessage());
        }

        $this->dispatch('render');
    }

    public function render()
    {
        return view('livewire.despacho.despacho-form', [
            'pacientes' => Paciente::where('dni', 'like', "%{$this->search}%")->limit(3)->get(),
            'medicamentos' => Medicamento::all(),
        ]);
    }
}
