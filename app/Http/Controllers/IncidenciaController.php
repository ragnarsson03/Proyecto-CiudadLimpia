<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Infraestructura;
use App\Models\User;
use Illuminate\Http\Request;

class IncidenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,tecnico')->only(['edit', 'update', 'destroy']);
        $this->middleware('can:update,incidencia')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidencias = Incidencia::with(['infraestructura', 'tecnico', 'ciudadano'])
            ->when(auth()->user()->role === 'ciudadano', function($query) {
                return $query->where('ciudadano_id', auth()->id());
            })
            ->latest()
            ->paginate(10);
        return view('incidencia.index', compact('incidencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $infraestructuras = Infraestructura::all();
        $tecnicos = User::where('role', 'tecnico')->get();
        return view('incidencia.create', compact('infraestructuras', 'tecnicos'));
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
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'descripcion' => 'required|string|min:10|max:500',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'fecha' => 'required|date|after_or_equal:today',
            'ubicacion' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048'
        ], [
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
            'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe pesar más de 2MB'
        ]);

        try {
            $incidencia = new Incidencia($validated);
            $incidencia->ciudadano_id = auth()->id();
            $incidencia->estado = 'pendiente';
            
            if ($request->hasFile('imagen')) {
                $incidencia->imagen = $request->file('imagen')->store('incidencias', 'public');
            }
            
            $incidencia->save();
            
            return redirect()->route('incidencia.index')
                ->with('success', 'Incidencia reportada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la incidencia. Por favor, intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
     */
    public function show(Incidencia $incidencia)
    {
        return view('incidencia.show', compact('incidencia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Incidencia $incidencia)
    {
        $infraestructuras = Infraestructura::all();
        $tecnicos = User::where('role', 'tecnico')->get();
        return view('incidencia.edit', compact('incidencia', 'infraestructuras', 'tecnicos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incidencia $incidencia)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en_proceso,resuelto,cancelado',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'tecnico_id' => 'nullable|exists:users,id'
        ]);

        $incidencia->update($validated);

        return redirect()->route('incidencia.index')
            ->with('success', 'Incidencia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incidencia $incidencia)
    {
        $incidencia->delete();

        return redirect()->route('incidencia.index')
            ->with('success', 'Incidencia eliminada exitosamente.');
    }
}
