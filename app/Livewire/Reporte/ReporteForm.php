<?php

namespace App\Livewire\Reporte;

use App\Models\Lote;
use App\Models\Reporte;
use Livewire\Component;
use App\Models\Despacho;
use App\Models\Medicamento;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DespachoMedicamento;
use Illuminate\Support\Facades\Storage;

class ReporteForm extends Component
{
    public $formView = false;
    public $tipo_reporte, $fecha_inicio, $fecha_fin;
    PUBLIC $generarPDF ;

    protected $rules = [
        'tipo_reporte' => 'required|in:hospitalizado,emergencia,quirofano,via_oral,general',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ];

    // protected $messages = [
    //     'tipo_reporte.required' => 'El tipo de reporte es obligatorio.',
    //     'tipo_reporte.in' => 'Seleccione un tipo de reporte válido.',
    //     'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
    //     'fecha_inicio.date' => 'Debe ingresar una fecha válida.',
    //     'fecha_fin.required' => 'La fecha de fin es obligatoria.',
    //     'fecha_fin.date' => 'Debe ingresar una fecha válida.',
    //     'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
    // ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function form()
    {
        $this->formView = true;
    }

    public function cancelar()
    {
        $this->formView = false;
        $this->reset(['tipo_reporte', 'fecha_inicio', 'fecha_fin']);
    }

        public function guardarDespacho()
    {
        // Validar datos
        $this->validate([
            'tipo_reporte' => 'required|in:hospitalizado,emergencia,quirofano,via_oral,general',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // SELECCIÓN DE MODELO SEGÚN EL TIPO DE REPORTE
        $despachos = [];
        $medicamentos = [];

        if ($this->tipo_reporte === 'hospitalizado' || $this->tipo_reporte === 'emergencia' || $this->tipo_reporte === 'quirofano') {
            // Consulta para hospitalizado, emergencia y quirofano
            $despachos = $this->obtenerDatosReporte();
        } elseif ($this->tipo_reporte === 'via_oral') {
            // Consulta para via oral
            $despachos = $this->obtenerDatosReporteViaOral();
        } elseif ($this->tipo_reporte === 'general') {
            // Consulta para reporte general de medicamentos
            $medicamentos = $this->obtenerDatosReporteGeneral();
        }

        // Generar PDF
        $pdf = [];
        if ($this->tipo_reporte === 'general') {
            $pdf = Pdf::loadView('pdf.reporte-general', [
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'medicamentos' => $medicamentos,
            ]);
        } else {
            $pdf = Pdf::loadView('pdf.reporte', [
                'tipo' => $this->tipo_reporte,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'despachos' => $despachos,
            ]);
        }

        // Guardar el PDF en almacenamiento
        $fileName = 'reporte_' . $this->tipo_reporte . '-' . time() . '.pdf';
        $filePath = 'public/reportes/' . $fileName;
        // $fileUrl = 'reportes/' . $fileName;

        if (!Storage::exists('public/reportes')) {
            Storage::makeDirectory('public/reportes');
        }
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // Obtener URL pública
        // $fileUrl = Storage::url($filePath);
        // $fileUrl = asset('storage/reportes/' . $fileName);
        $fileUrl = asset('storage/' . $filePath);

        // Guardar en base de datos
        Reporte::create([
            'tipo' => $this->tipo_reporte,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'ruta_dir' => $filePath,
            'url' => $fileUrl,
        ]);
        // 'url' => asset('storage/' . $filePath),

        // Notificación de éxito
        $this->dispatch('notificacion', [
            'mensaje' => 'Reporte generado con éxito.',
            'tipo' => 'success'
        ]);

        // Cerrar formulario y resetear
        $this->formView = false;
        $this->reset(['tipo_reporte', 'fecha_inicio', 'fecha_fin']);

        // Enviar evento a frontend con la URL del PDF
        // if (Storage::exists($filePath)) {
        //     $this->dispatch('abrirPdf', ['fileUrl' => asset('storage/' . $filePath)]);
        // }
        $this->dispatch('abrirPdf', ['fileUrl' => asset('storage/' . $filePath)]);
        $this->dispatch('render');
        // session()->flash('message', 'Reporte generado con éxito.');
    }

    // Consulta para el reporte de hospitalizado, emergencia o quirofano
    public function obtenerDatosReporte() {
        // Filtrar por tipo de despacho y rango de fechas
        $despachos = Despacho::where('tipo', $this->tipo_reporte)
            ->whereBetween('fecha_pedido', [$this->fecha_inicio, $this->fecha_fin])
            ->get();

            // Para "quirofano", obtener detalles de medicamentos solicitados y despachados
            if ($this->tipo_reporte === 'quirofano') {
                foreach ($despachos as $despacho) {
                    $despacho->medicamentos = DespachoMedicamento::where('despacho_id', $despacho->id)->get();
                }
        }

        return $despachos;
    }

    // Generar el PDF
    public function generarPDF() {
        $despachos = $this->obtenerDatosReporte();

        // Generar PDF
        $pdf = Pdf::loadView('pdf.reporte', [
            'tipo' => $this->tipo_reporte,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'despachos' => $despachos,
        ]);

        return $pdf->download('reporte_' . $this->tipo_reporte . '.pdf');
    }

    // Consulta para el reporte de via_oral
    public function obtenerDatosReporteViaOral() {
        $despachos = Despacho::whereBetween('fecha_pedido', [$this->fecha_inicio, $this->fecha_fin])
                            ->get();

        // Filtrar medicamentos por vía de administración
        foreach ($despachos as $despacho) {
            $despacho->medicamentos = DespachoMedicamento::where('despacho_id', $despacho->id)
                ->whereHas('medicamento.presentacion', function($query)
                {
                    $query->where('via_administracion', 'Oral'); // Filtrar por Oral
                })
                ->get();
        }

        return $despachos;
    }

    // Consulta para el reporte general de medicamentos
    public function obtenerDatosReporteGeneral() {
        $medicamentos = Medicamento::all();
        
        foreach ($medicamentos as $medicamento) {
            // Calcular la cantidad total despachada
            $cantidad_despachada = DespachoMedicamento::where('medicamento_id', $medicamento->id)
                ->whereHas('despacho', function($query) {
                    $query->whereBetween('fecha_pedido', [$this->fecha_inicio, $this->fecha_fin]);
                })
                ->sum('cantidad');

            
            // Calcular la cantidad de lotes ingresados
            $cantidad_lotes_ingresados = Lote::where('medicamento_id', $medicamento->id)
                                            ->sum('cantidad');
            
            // Calcular la cantidad inicial
            $cantidad_inicial = $medicamento->cantidad_disponible + $cantidad_despachada - $cantidad_lotes_ingresados;
            
            $medicamento->cantidad_despachada = $cantidad_despachada;
            $medicamento->cantidad_inicial = $cantidad_inicial;
        }

        return $medicamentos;
    }

    // Generar el PDF
    public function generarPDFGeneral() {
        $medicamentos = $this->obtenerDatosReporteGeneral();

        // Generar PDF
        $pdf = Pdf::loadView('pdf.reporte_general', [
            'tipo' => 'general',
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'medicamentos' => $medicamentos,
        ]);

        return $pdf->download('reporte_general.pdf');
    }


    public function render()
    {
        return view('livewire.reporte.reporte-form');
    }
}

