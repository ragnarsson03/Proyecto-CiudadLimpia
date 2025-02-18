<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Personal;
use App\Models\Material;
use App\Models\Infraestructura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin,supervisor'])->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = OrdenTrabajo::with(['infraestructura', 'personal']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_programada', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_programada', '<=', $request->fecha_hasta);
        }

        // Si es técnico, solo ver sus órdenes asignadas
        if (auth()->user()->role === 'tecnico') {
            $query->whereHas('personal', function($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $ordenes = $query->paginate(10);

        return view('ordenes.index', compact('ordenes'));
    }

    public function create()
    {
        $infraestructuras = Infraestructura::all();
        $personal = Personal::with('user')->where('disponibilidad', 'disponible')->get();
        $materiales = Material::where('cantidad_disponible', '>', 0)->get();

        return view('ordenes.create', compact('infraestructuras', 'personal', 'materiales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'tipo' => 'required|in:correctivo,preventivo',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'descripcion' => 'required|string',
            'fecha_programada' => 'required|date',
            'personal' => 'required|array',
            'personal.*.id' => 'exists:personal,id',
            'personal.*.horas' => 'required|numeric|min:1',
            'materiales' => 'required|array',
            'materiales.*.id' => 'exists:materiales,id',
            'materiales.*.cantidad' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Verificar disponibilidad de materiales
            foreach ($validated['materiales'] as $material) {
                $mat = Material::find($material['id']);
                if ($mat->cantidad_disponible < $material['cantidad']) {
                    throw new \Exception("No hay suficiente stock del material: {$mat->nombre}");
                }
            }

            // Crear la orden de trabajo
            $orden = OrdenTrabajo::create([
                'codigo' => 'OT-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'infraestructura_id' => $validated['infraestructura_id'],
                'tipo' => $validated['tipo'],
                'estado' => 'pendiente',
                'prioridad' => $validated['prioridad'],
                'descripcion' => $validated['descripcion'],
                'fecha_programada' => $validated['fecha_programada']
            ]);

            // Asignar personal
            foreach ($validated['personal'] as $pers) {
                $orden->personal()->attach($pers['id'], [
                    'horas_asignadas' => $pers['horas']
                ]);
                
                // Actualizar disponibilidad del personal
                Personal::find($pers['id'])->update(['disponibilidad' => 'ocupado']);
            }

            // Asignar materiales
            foreach ($validated['materiales'] as $mat) {
                $material = Material::find($mat['id']);
                $orden->materiales()->attach($mat['id'], [
                    'cantidad' => $mat['cantidad'],
                    'costo_total' => $mat['cantidad'] * $material->costo_unitario
                ]);

                // Actualizar stock
                $material->decrement('cantidad_disponible', $mat['cantidad']);
            }

            DB::commit();

            return redirect()->route('ordenes.index')
                ->with('success', 'Orden de trabajo creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la orden de trabajo. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(OrdenTrabajo $orden)
    {
        $orden->load(['infraestructura', 'personal.user', 'materiales']);
        
        return view('ordenes.show', compact('orden'));
    }

    public function edit(OrdenTrabajo $orden)
    {
        if ($orden->estado === 'completada') {
            return back()->with('error', 'No se puede editar una orden completada');
        }

        $infraestructuras = Infraestructura::all();
        $personal = Personal::with('user')->where('disponibilidad', 'disponible')
            ->orWhereHas('ordenesAsignadas', function($q) use ($orden) {
                $q->where('orden_trabajo_id', $orden->id);
            })->get();
        $materiales = Material::where('cantidad_disponible', '>', 0)
            ->orWhereHas('ordenesTrabajoAsignadas', function($q) use ($orden) {
                $q->where('orden_trabajo_id', $orden->id);
            })->get();

        return view('ordenes.edit', compact('orden', 'infraestructuras', 'personal', 'materiales'));
    }

    public function update(Request $request, OrdenTrabajo $orden)
    {
        if ($orden->estado === 'completada') {
            return back()->with('error', 'No se puede actualizar una orden completada');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completada,cancelada',
            'descripcion' => 'required|string',
            'fecha_programada' => 'required|date',
            'personal' => 'required|array',
            'personal.*.id' => 'exists:personal,id',
            'personal.*.horas' => 'required|numeric|min:1',
            'materiales' => 'required|array',
            'materiales.*.id' => 'exists:materiales,id',
            'materiales.*.cantidad' => 'required|numeric|min:1',
            'observaciones' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar orden
            $orden->update([
                'estado' => $validated['estado'],
                'descripcion' => $validated['descripcion'],
                'fecha_programada' => $validated['fecha_programada'],
                'observaciones' => $validated['observaciones']
            ]);

            // Actualizar personal
            $orden->personal()->sync([]);
            foreach ($validated['personal'] as $pers) {
                $orden->personal()->attach($pers['id'], [
                    'horas_asignadas' => $pers['horas']
                ]);
            }

            // Actualizar materiales
            $materialesAnteriores = $orden->materiales;
            $orden->materiales()->sync([]);

            // Devolver stock de materiales anteriores
            foreach ($materialesAnteriores as $mat) {
                $mat->increment('cantidad_disponible', $mat->pivot->cantidad);
            }

            // Asignar nuevos materiales
            foreach ($validated['materiales'] as $mat) {
                $material = Material::find($mat['id']);
                if ($material->cantidad_disponible < $mat['cantidad']) {
                    throw new \Exception("No hay suficiente stock del material: {$material->nombre}");
                }

                $orden->materiales()->attach($mat['id'], [
                    'cantidad' => $mat['cantidad'],
                    'costo_total' => $mat['cantidad'] * $material->costo_unitario
                ]);

                $material->decrement('cantidad_disponible', $mat['cantidad']);
            }

            // Si la orden se completa, actualizar disponibilidad del personal
            if ($validated['estado'] === 'completada') {
                foreach ($orden->personal as $pers) {
                    if (!$pers->ordenesAsignadas()
                            ->where('id', '!=', $orden->id)
                            ->whereIn('estado', ['pendiente', 'en_proceso'])
                            ->exists()) {
                        $pers->update(['disponibilidad' => 'disponible']);
                    }
                }
            }

            DB::commit();

            return redirect()->route('ordenes.show', $orden)
                ->with('success', 'Orden de trabajo actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la orden de trabajo. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(OrdenTrabajo $orden)
    {
        if ($orden->estado === 'completada') {
            return back()->with('error', 'No se puede eliminar una orden completada');
        }

        try {
            DB::beginTransaction();

            // Devolver stock de materiales
            foreach ($orden->materiales as $material) {
                $material->increment('cantidad_disponible', $material->pivot->cantidad);
            }

            // Actualizar disponibilidad del personal
            foreach ($orden->personal as $personal) {
                if (!$personal->ordenesAsignadas()
                        ->where('id', '!=', $orden->id)
                        ->whereIn('estado', ['pendiente', 'en_proceso'])
                        ->exists()) {
                    $personal->update(['disponibilidad' => 'disponible']);
                }
            }

            $orden->delete();

            DB::commit();

            return redirect()->route('ordenes.index')
                ->with('success', 'Orden de trabajo eliminada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la orden de trabajo. ' . $e->getMessage());
        }
    }
}
