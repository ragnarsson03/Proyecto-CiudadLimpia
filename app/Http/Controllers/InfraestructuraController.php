<?php

namespace App\Http\Controllers;

use App\Models\Infraestructura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfraestructuraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,tecnico')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infraestructuras = Infraestructura::with(['incidencias' => function($query) {
                $query->latest()->take(5);
            }])
            ->withCount(['incidencias', 'incidencias as incidencias_activas_count' => function($query) {
                $query->whereIn('estado', ['pendiente', 'en_proceso']);
            }])
            ->latest()
            ->paginate(10);
            
        return view('infraestructura.index', compact('infraestructuras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('infraestructura.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'estado' => 'required|in:operativo,mantenimiento,reparacion,fuera_de_servicio',
            'capacidad' => 'required|integer|min:1',
            'fecha_instalacion' => 'required|date|before_or_equal:today',
            'imagen' => 'nullable|image|max:2048'
        ], [
            'tipo.required' => 'El tipo de infraestructura es obligatorio',
            'ubicacion.required' => 'La ubicación es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'capacidad.min' => 'La capacidad debe ser mayor a 0',
            'fecha_instalacion.before_or_equal' => 'La fecha de instalación no puede ser futura',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe pesar más de 2MB'
        ]);

        try {
            $infraestructura = new Infraestructura($validated);
            
            if ($request->hasFile('imagen')) {
                $infraestructura->imagen = $request->file('imagen')->store('infraestructuras', 'public');
            }
            
            $infraestructura->save();
            
            return redirect()->route('infraestructura.index')
                ->with('success', 'Infraestructura creada correctamente');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al crear la infraestructura. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function show(Infraestructura $infraestructura)
    {
        return view('infraestructura.show', compact('infraestructura'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function edit(Infraestructura $infraestructura)
    {
        return view('infraestructura.edit', compact('infraestructura'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Infraestructura $infraestructura)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string|min:10|max:1000',
            'estado' => 'required|in:operativo,mantenimiento,reparacion,fuera_de_servicio',
            'capacidad' => 'required|integer|min:1',
            'fecha_instalacion' => 'required|date|before_or_equal:today',
            'imagen' => 'nullable|image|max:2048'
        ]);

        try {
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($infraestructura->imagen) {
                    Storage::disk('public')->delete($infraestructura->imagen);
                }
                $validated['imagen'] = $request->file('imagen')->store('infraestructuras', 'public');
            }

            $infraestructura->update($validated);
            
            return redirect()->route('infraestructura.show', $infraestructura)
                ->with('success', 'Infraestructura actualizada correctamente');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al actualizar la infraestructura. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Infraestructura $infraestructura)
    {
        try {
            // Verificar si hay incidencias activas
            if ($infraestructura->incidencias()->whereIn('estado', ['pendiente', 'en_proceso'])->exists()) {
                return back()->with('error', 'No se puede eliminar la infraestructura porque tiene incidencias activas.');
            }

            // Eliminar imagen si existe
            if ($infraestructura->imagen) {
                Storage::disk('public')->delete($infraestructura->imagen);
            }

            $infraestructura->delete();
            
            return redirect()->route('infraestructura.index')
                ->with('success', 'Infraestructura eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la infraestructura.');
        }
    }
}
