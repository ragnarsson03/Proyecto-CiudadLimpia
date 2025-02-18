<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,supervisor']);
    }

    public function incidencias(Request $request, $format)
    {
        $incidencias = Incidencia::with(['infraestructura', 'tecnico', 'ciudadano'])
            ->latest()
            ->get();

        if ($format === 'excel') {
            $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
            
            // Encabezados
            $csv->insertOne([
                'ID',
                'Tipo',
                'Ubicación',
                'Estado',
                'Prioridad',
                'Infraestructura',
                'Técnico',
                'Ciudadano',
                'Fecha',
                'Descripción'
            ]);

            // Datos
            foreach ($incidencias as $incidencia) {
                $csv->insertOne([
                    $incidencia->id,
                    $incidencia->tipo,
                    $incidencia->ubicacion,
                    $incidencia->estado,
                    $incidencia->prioridad,
                    $incidencia->infraestructura->tipo,
                    $incidencia->tecnico ? $incidencia->tecnico->name : 'No asignado',
                    $incidencia->ciudadano->name,
                    $incidencia->fecha->format('d/m/Y H:i'),
                    $incidencia->descripcion
                ]);
            }

            return Response::stream(
                function() use ($csv) {
                    echo $csv->toString();
                },
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="incidencias.csv"',
                ]
            );
        }

        if ($format === 'pdf') {
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadView('exports.incidencias', compact('incidencias'));
            return $pdf->download('incidencias.pdf');
        }

        return back()->with('error', 'Formato no soportado');
    }
}
