<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Personal;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:admin,supervisor'])->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Ruta::with(['personal.user']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Si es técnico, solo ver sus rutas
        if (auth()->user()->role === 'tecnico') {
            $query->whereHas('personal', function($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $rutas = $query->paginate(10);

        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        $personal = Personal::with('user')
            ->where('disponibilidad', 'disponible')
            ->get();

        $ordenesPendientes = OrdenTrabajo::whereIn('estado', ['pendiente', 'en_proceso'])
            ->whereNull('ruta_id')
            ->get();

        return view('rutas.create', compact('personal', 'ordenesPendientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
            'fecha' => 'required|date',
            'ordenes_trabajo' => 'required|array',
            'ordenes_trabajo.*' => 'exists:ordenes_trabajo,id'
        ]);

        try {
            DB::beginTransaction();

            // Obtener órdenes de trabajo y sus ubicaciones
            $ordenes = OrdenTrabajo::with('infraestructura')
                ->whereIn('id', $validated['ordenes_trabajo'])
                ->get();

            // Crear array de puntos para la ruta
            $puntos = $ordenes->map(function($orden) {
                return [
                    'lat' => $orden->infraestructura->latitud,
                    'lng' => $orden->infraestructura->longitud,
                    'orden_id' => $orden->id
                ];
            })->toArray();

            // Crear la ruta
            $ruta = Ruta::create([
                'personal_id' => $validated['personal_id'],
                'fecha' => $validated['fecha'],
                'ordenes_trabajo' => $validated['ordenes_trabajo'],
                'puntos' => $puntos,
                'estado' => 'pendiente',
                'distancia_total' => 0, // Se calculará después
                'tiempo_estimado' => 0  // Se calculará después
            ]);

            // Optimizar la ruta
            $ruta->optimizarRuta();

            // Actualizar órdenes de trabajo
            OrdenTrabajo::whereIn('id', $validated['ordenes_trabajo'])
                ->update(['ruta_id' => $ruta->id]);

            DB::commit();

            return redirect()->route('rutas.index')
                ->with('success', 'Ruta creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la ruta. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Ruta $ruta)
    {
        $ruta->load(['personal.user', 'ordenesTrabajoRelacionadas.infraestructura']);
        
        return view('rutas.show', compact('ruta'));
    }

    public function edit(Ruta $ruta)
    {
        if ($ruta->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden editar rutas pendientes');
        }

        $personal = Personal::with('user')
            ->where('disponibilidad', 'disponible')
            ->orWhere('id', $ruta->personal_id)
            ->get();

        $ordenesPendientes = OrdenTrabajo::whereIn('estado', ['pendiente', 'en_proceso'])
            ->where(function($query) use ($ruta) {
                $query->whereNull('ruta_id')
                    ->orWhere('ruta_id', $ruta->id);
            })
            ->get();

        return view('rutas.edit', compact('ruta', 'personal', 'ordenesPendientes'));
    }

    public function update(Request $request, Ruta $ruta)
    {
        if ($ruta->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden actualizar rutas pendientes');
        }

        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
            'fecha' => 'required|date',
            'ordenes_trabajo' => 'required|array',
            'ordenes_trabajo.*' => 'exists:ordenes_trabajo,id'
        ]);

        try {
            DB::beginTransaction();

            // Liberar órdenes anteriores
            OrdenTrabajo::where('ruta_id', $ruta->id)
                ->update(['ruta_id' => null]);

            // Obtener órdenes de trabajo y sus ubicaciones
            $ordenes = OrdenTrabajo::with('infraestructura')
                ->whereIn('id', $validated['ordenes_trabajo'])
                ->get();

            // Crear array de puntos para la ruta
            $puntos = $ordenes->map(function($orden) {
                return [
                    'lat' => $orden->infraestructura->latitud,
                    'lng' => $orden->infraestructura->longitud,
                    'orden_id' => $orden->id
                ];
            })->toArray();

            // Actualizar la ruta
            $ruta->update([
                'personal_id' => $validated['personal_id'],
                'fecha' => $validated['fecha'],
                'ordenes_trabajo' => $validated['ordenes_trabajo'],
                'puntos' => $puntos
            ]);

            // Optimizar la ruta
            $ruta->optimizarRuta();

            // Actualizar órdenes de trabajo
            OrdenTrabajo::whereIn('id', $validated['ordenes_trabajo'])
                ->update(['ruta_id' => $ruta->id]);

            DB::commit();

            return redirect()->route('rutas.index')
                ->with('success', 'Ruta actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la ruta. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Ruta $ruta)
    {
        if ($ruta->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden eliminar rutas pendientes');
        }

        try {
            DB::beginTransaction();

            // Liberar órdenes de trabajo
            OrdenTrabajo::where('ruta_id', $ruta->id)
                ->update(['ruta_id' => null]);

            $ruta->delete();

            DB::commit();

            return redirect()->route('rutas.index')
                ->with('success', 'Ruta eliminada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la ruta. ' . $e->getMessage());
        }
    }

    public function iniciarRuta(Ruta $ruta)
    {
        try {
            if ($ruta->iniciarRuta()) {
                return back()->with('success', 'Ruta iniciada exitosamente');
            }
            return back()->with('error', 'No se pudo iniciar la ruta');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al iniciar la ruta. ' . $e->getMessage());
        }
    }

    public function finalizarRuta(Ruta $ruta)
    {
        try {
            if ($ruta->finalizarRuta()) {
                return back()->with('success', 'Ruta finalizada exitosamente');
            }
            return back()->with('error', 'No se pudo finalizar la ruta');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al finalizar la ruta. ' . $e->getMessage());
        }
    }
}
