<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PresupuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Presupuesto::query();

        if ($request->filled('año')) {
            $query->where('año', $request->año);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('zona')) {
            $query->where('zona', $request->zona);
        }

        $presupuestos = $query->paginate(10);

        // Calcular totales
        $totales = [
            'asignado' => $query->sum('monto_asignado'),
            'ejecutado' => $query->sum('monto_ejecutado'),
            'comprometido' => $query->sum('monto_comprometido'),
            'disponible' => $query->sum(DB::raw('monto_asignado - monto_ejecutado - monto_comprometido'))
        ];

        return view('presupuestos.index', compact('presupuestos', 'totales'));
    }

    public function create()
    {
        $categorias = [
            'mantenimiento_correctivo' => 'Mantenimiento Correctivo',
            'mantenimiento_preventivo' => 'Mantenimiento Preventivo',
            'materiales' => 'Materiales',
            'personal' => 'Personal',
            'equipamiento' => 'Equipamiento',
            'otros' => 'Otros'
        ];

        $zonas = [
            'norte' => 'Zona Norte',
            'sur' => 'Zona Sur',
            'este' => 'Zona Este',
            'oeste' => 'Zona Oeste',
            'centro' => 'Zona Centro'
        ];

        return view('presupuestos.create', compact('categorias', 'zonas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'año' => 'required|integer|min:2000',
            'mes' => 'required|integer|between:1,12',
            'monto_asignado' => 'required|numeric|min:0',
            'categoria' => 'required|string',
            'zona' => 'nullable|string',
            'desglose' => 'nullable|array',
            'notas' => 'nullable|string'
        ]);

        try {
            $presupuesto = Presupuesto::create($validated);

            return redirect()->route('presupuestos.index')
                ->with('success', 'Presupuesto creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el presupuesto. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Presupuesto $presupuesto)
    {
        return view('presupuestos.show', compact('presupuesto'));
    }

    public function edit(Presupuesto $presupuesto)
    {
        $categorias = [
            'mantenimiento_correctivo' => 'Mantenimiento Correctivo',
            'mantenimiento_preventivo' => 'Mantenimiento Preventivo',
            'materiales' => 'Materiales',
            'personal' => 'Personal',
            'equipamiento' => 'Equipamiento',
            'otros' => 'Otros'
        ];

        $zonas = [
            'norte' => 'Zona Norte',
            'sur' => 'Zona Sur',
            'este' => 'Zona Este',
            'oeste' => 'Zona Oeste',
            'centro' => 'Zona Centro'
        ];

        return view('presupuestos.edit', compact('presupuesto', 'categorias', 'zonas'));
    }

    public function update(Request $request, Presupuesto $presupuesto)
    {
        $validated = $request->validate([
            'monto_asignado' => 'required|numeric|min:' . ($presupuesto->monto_ejecutado + $presupuesto->monto_comprometido),
            'desglose' => 'nullable|array',
            'notas' => 'nullable|string'
        ]);

        try {
            $presupuesto->update($validated);

            return redirect()->route('presupuestos.index')
                ->with('success', 'Presupuesto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el presupuesto. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Presupuesto $presupuesto)
    {
        try {
            if ($presupuesto->monto_ejecutado > 0 || $presupuesto->monto_comprometido > 0) {
                throw new \Exception('No se puede eliminar un presupuesto con montos ejecutados o comprometidos.');
            }

            $presupuesto->delete();

            return redirect()->route('presupuestos.index')
                ->with('success', 'Presupuesto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el presupuesto. ' . $e->getMessage());
        }
    }

    public function exportarPDF(Request $request)
    {
        $query = Presupuesto::query();

        if ($request->filled('año')) {
            $query->where('año', $request->año);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        $presupuestos = $query->get();

        $totales = [
            'asignado' => $presupuestos->sum('monto_asignado'),
            'ejecutado' => $presupuestos->sum('monto_ejecutado'),
            'comprometido' => $presupuestos->sum('monto_comprometido'),
            'disponible' => $presupuestos->sum(function($p) {
                return $p->monto_asignado - $p->monto_ejecutado - $p->monto_comprometido;
            })
        ];

        $pdf = PDF::loadView('presupuestos.pdf', compact('presupuestos', 'totales'));
        
        return $pdf->download('reporte_presupuesto.pdf');
    }

    public function exportarExcel(Request $request)
    {
        $query = Presupuesto::query();

        if ($request->filled('año')) {
            $query->where('año', $request->año);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        $presupuestos = $query->get();

        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        
        // Encabezados
        $csv->insertOne([
            'Año',
            'Mes',
            'Categoría',
            'Zona',
            'Monto Asignado',
            'Monto Ejecutado',
            'Monto Comprometido',
            'Monto Disponible',
            'Notas'
        ]);

        // Datos
        foreach ($presupuestos as $p) {
            $csv->insertOne([
                $p->año,
                $p->mes,
                $p->categoria,
                $p->zona ?? 'N/A',
                $p->monto_asignado,
                $p->monto_ejecutado,
                $p->monto_comprometido,
                $p->montoDisponible(),
                $p->notas
            ]);
        }

        return response()->streamDownload(
            function() use ($csv) {
                echo $csv->toString();
            },
            'presupuestos.csv',
            ['Content-Type' => 'text/csv']
        );
    }
}
