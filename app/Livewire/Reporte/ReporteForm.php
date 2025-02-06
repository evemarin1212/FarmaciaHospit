<?php

namespace App\Livewire\Reporte;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reporte;

class ReporteForm extends Component
{
    public $formView = false;
    public $tipo_reporte, $fecha_inicio, $fecha_fin;

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

        // SELECION DE MODELO SEGUN EL TIPO DE REPORTE
        $reporte = 0;
        // Generar PDF
        $pdf = Pdf::loadView('pdf.reporte', [
            'tipo' => $this->tipo_reporte,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'fecha_fin' => $reporte,
        ]);
    
        // Guardar en almacenamiento
        $fileName = 'reporte_' . $this->tipo_reporte . '-' . time() . '.pdf';
        $filePath = 'public/reportes/' . $fileName;
        $filePath = 'reportes/' . $fileName;

        // Storage::put($filePath, $pdf->output());
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

        // Notificación de éxito
        $this->dispatch('notificacion', [
            'mensaje' => 'Reporte generado con exito.',
            'tipo' => 'success'
        ]);
    
        // Cerrar formulario y resetear
        $this->formView = false;
        $this->reset(['tipo_reporte', 'fecha_inicio', 'fecha_fin']);
        
        // Enviar evento a frontend con la URL del PDF
        $this->dispatch('abrirPdf', ['fileUrl' => $fileUrl]);
    
        session()->flash('message', 'Reporte generado con éxito.');
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
                                                        ->whereHas('medicamento.presentacion', function($query) {
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
                                                    ->whereBetween('despacho.fecha_pedido', [$this->fecha_inicio, $this->fecha_fin])
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
