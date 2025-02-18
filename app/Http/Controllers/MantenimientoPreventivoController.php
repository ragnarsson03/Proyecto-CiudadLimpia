<?php

namespace App\Http\Controllers;

use App\Models\MantenimientoPreventivo;
use App\Models\Infraestructura;
use App\Models\Material;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantenimientoPreventivoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,supervisor']);
    }

    public function index(Request $request)
    {
        $query = MantenimientoPreventivo::with(['infraestructura']);

        if ($request->filled('frecuencia')) {
            $query->where('frecuencia', $request->frecuencia);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        if ($request->filled('proxima_ejecucion')) {
            $query->whereDate('proxima_ejecucion', '<=', $request->proxima_ejecucion);
        }

        $mantenimientos = $query->paginate(10);

        return view('mantenimiento.index', compact('mantenimientos'));
    }

    public function create()
    {
        $infraestructuras = Infraestructura::all();
        $materiales = Material::where('cantidad_disponible', '>', 0)->get();
        $personal = Personal::with('user')->get();

        $frecuencias = [
            'diaria' => 'Diaria',
            'semanal' => 'Semanal',
            'mensual' => 'Mensual',
            'trimestral' => 'Trimestral',
            'semestral' => 'Semestral',
            'anual' => 'Anual'
        ];

        return view('mantenimiento.create', compact('infraestructuras', 'materiales', 'personal', 'frecuencias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'frecuencia' => 'required|in:diaria,semanal,mensual,trimestral,semestral,anual',
            'checklist' => 'nullable|array',
            'costo_estimado' => 'required|numeric|min:0',
            'duracion_estimada' => 'required|integer|min:1',
            'materiales_requeridos' => 'required|array',
            'materiales_requeridos.*.id' => 'exists:materiales,id',
            'materiales_requeridos.*.cantidad' => 'required|numeric|min:1',
            'personal_requerido' => 'required|array',
            'personal_requerido.*.id' => 'exists:personal,id',
            'personal_requerido.*.horas' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Calcular días de frecuencia
            $diasFrecuencia = match($validated['frecuencia']) {
                'diaria' => 1,
                'semanal' => 7,
                'mensual' => 30,
                'trimestral' => 90,
                'semestral' => 180,
                'anual' => 365
            };

            $mantenimiento = MantenimientoPreventivo::create([
                'infraestructura_id' => $validated['infraestructura_id'],
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'frecuencia' => $validated['frecuencia'],
                'dias_frecuencia' => $diasFrecuencia,
                'proxima_ejecucion' => now()->addDays($diasFrecuencia),
                'checklist' => $validated['checklist'],
                'costo_estimado' => $validated['costo_estimado'],
                'duracion_estimada' => $validated['duracion_estimada'],
                'materiales_requeridos' => $validated['materiales_requeridos'],
                'personal_requerido' => $validated['personal_requerido'],
                'activo' => true
            ]);

            DB::commit();

            return redirect()->route('mantenimiento.index')
                ->with('success', 'Plan de mantenimiento preventivo creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el plan de mantenimiento. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(MantenimientoPreventivo $mantenimiento)
    {
        $mantenimiento->load(['infraestructura', 'ordenesGeneradas']);
        
        return view('mantenimiento.show', compact('mantenimiento'));
    }

    public function edit(MantenimientoPreventivo $mantenimiento)
    {
        $infraestructuras = Infraestructura::all();
        $materiales = Material::where('cantidad_disponible', '>', 0)->get();
        $personal = Personal::with('user')->get();

        $frecuencias = [
            'diaria' => 'Diaria',
            'semanal' => 'Semanal',
            'mensual' => 'Mensual',
            'trimestral' => 'Trimestral',
            'semestral' => 'Semestral',
            'anual' => 'Anual'
        ];

        return view('mantenimiento.edit', compact('mantenimiento', 'infraestructuras', 'materiales', 'personal', 'frecuencias'));
    }

    public function update(Request $request, MantenimientoPreventivo $mantenimiento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'frecuencia' => 'required|in:diaria,semanal,mensual,trimestral,semestral,anual',
            'checklist' => 'nullable|array',
            'costo_estimado' => 'required|numeric|min:0',
            'duracion_estimada' => 'required|integer|min:1',
            'materiales_requeridos' => 'required|array',
            'materiales_requeridos.*.id' => 'exists:materiales,id',
            'materiales_requeridos.*.cantidad' => 'required|numeric|min:1',
            'personal_requerido' => 'required|array',
            'personal_requerido.*.id' => 'exists:personal,id',
            'personal_requerido.*.horas' => 'required|numeric|min:1',
            'activo' => 'required|boolean'
        ]);

        try {
            // Calcular días de frecuencia
            $diasFrecuencia = match($validated['frecuencia']) {
                'diaria' => 1,
                'semanal' => 7,
                'mensual' => 30,
                'trimestral' => 90,
                'semestral' => 180,
                'anual' => 365
            };

            $mantenimiento->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'frecuencia' => $validated['frecuencia'],
                'dias_frecuencia' => $diasFrecuencia,
                'checklist' => $validated['checklist'],
                'costo_estimado' => $validated['costo_estimado'],
                'duracion_estimada' => $validated['duracion_estimada'],
                'materiales_requeridos' => $validated['materiales_requeridos'],
                'personal_requerido' => $validated['personal_requerido'],
                'activo' => $validated['activo']
            ]);

            return redirect()->route('mantenimiento.index')
                ->with('success', 'Plan de mantenimiento actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el plan de mantenimiento. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(MantenimientoPreventivo $mantenimiento)
    {
        try {
            if ($mantenimiento->ordenesGeneradas()->whereIn('estado', ['pendiente', 'en_proceso'])->exists()) {
                throw new \Exception('No se puede eliminar un plan con órdenes de trabajo pendientes.');
            }

            $mantenimiento->delete();

            return redirect()->route('mantenimiento.index')
                ->with('success', 'Plan de mantenimiento eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el plan de mantenimiento. ' . $e->getMessage());
        }
    }

    public function generarOrdenes()
    {
        try {
            DB::beginTransaction();

            $mantenimientos = MantenimientoPreventivo::where('activo', true)
                ->where('proxima_ejecucion', '<=', now())
                ->get();

            $ordenesGeneradas = 0;

            foreach ($mantenimientos as $mantenimiento) {
                $orden = $mantenimiento->generarOrdenTrabajo();
                if ($orden) {
                    $ordenesGeneradas++;
                    $mantenimiento->actualizarProximaEjecucion();
                }
            }

            DB::commit();

            return back()->with('success', "Se generaron {$ordenesGeneradas} órdenes de trabajo preventivo");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar órdenes de trabajo. ' . $e->getMessage());
        }
    }
}
