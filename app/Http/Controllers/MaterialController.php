<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,supervisor']);
    }

    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo_interno', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stock_bajo')) {
            $query->whereRaw('cantidad_disponible <= stock_minimo');
        }

        $materiales = $query->paginate(10);

        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        $unidades_medida = [
            'unidad' => 'Unidad',
            'metro' => 'Metro',
            'kilogramo' => 'Kilogramo',
            'litro' => 'Litro',
            'pieza' => 'Pieza',
            'caja' => 'Caja'
        ];

        return view('materiales.create', compact('unidades_medida'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad_disponible' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string',
            'stock_minimo' => 'required|numeric|min:0',
            'stock_maximo' => 'nullable|numeric|min:0',
            'ubicacion_almacen' => 'nullable|string',
            'codigo_interno' => 'required|string|unique:materiales,codigo_interno',
            'proveedores' => 'nullable|array'
        ]);

        try {
            $material = Material::create($validated);

            return redirect()->route('materiales.index')
                ->with('success', 'Material creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el material. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Material $material)
    {
        $material->load(['ordenesTrabajoAsignadas', 'mantenimientosPreventivos']);
        
        return view('materiales.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $unidades_medida = [
            'unidad' => 'Unidad',
            'metro' => 'Metro',
            'kilogramo' => 'Kilogramo',
            'litro' => 'Litro',
            'pieza' => 'Pieza',
            'caja' => 'Caja'
        ];

        return view('materiales.edit', compact('material', 'unidades_medida'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad_disponible' => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string',
            'stock_minimo' => 'required|numeric|min:0',
            'stock_maximo' => 'nullable|numeric|min:0',
            'ubicacion_almacen' => 'nullable|string',
            'codigo_interno' => 'required|string|unique:materiales,codigo_interno,' . $material->id,
            'proveedores' => 'nullable|array'
        ]);

        try {
            $material->update($validated);

            // Si el stock está bajo, generar una alerta
            if ($material->necesitaReposicion()) {
                // TODO: Implementar sistema de alertas
            }

            return redirect()->route('materiales.index')
                ->with('success', 'Material actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el material. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Material $material)
    {
        try {
            // Verificar si el material está siendo usado en órdenes de trabajo activas
            if ($material->ordenesTrabajoAsignadas()
                ->whereIn('estado', ['pendiente', 'en_proceso'])
                ->exists()) {
                throw new \Exception('No se puede eliminar un material que está siendo usado en órdenes de trabajo activas.');
            }

            $material->delete();

            return redirect()->route('materiales.index')
                ->with('success', 'Material eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el material. ' . $e->getMessage());
        }
    }

    public function ajustarInventario(Request $request, Material $material)
    {
        $validated = $request->validate([
            'cantidad' => 'required|numeric',
            'tipo_ajuste' => 'required|in:entrada,salida',
            'motivo' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $cantidad_anterior = $material->cantidad_disponible;
            
            if ($validated['tipo_ajuste'] === 'entrada') {
                $material->cantidad_disponible += $validated['cantidad'];
            } else {
                if ($material->cantidad_disponible < $validated['cantidad']) {
                    throw new \Exception('No hay suficiente stock disponible.');
                }
                $material->cantidad_disponible -= $validated['cantidad'];
            }

            $material->save();

            // Registrar el movimiento de inventario
            // TODO: Implementar registro de movimientos

            DB::commit();

            return back()->with('success', 'Inventario ajustado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al ajustar el inventario. ' . $e->getMessage());
        }
    }
}
