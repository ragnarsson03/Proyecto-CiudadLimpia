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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidencias = Incidencia::with(['infraestructura', 'tecnico', 'ciudadano'])
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
            'tipo' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta,critica',
            'infraestructura_id' => 'required|exists:infraestructuras,id',
            'tecnico_id' => 'nullable|exists:users,id'
        ]);

        $validated['estado'] = 'pendiente';
        $validated['fecha'] = now();
        $validated['ciudadano_id'] = auth()->id();

        $incidencia = Incidencia::create($validated);

        return redirect()->route('incidencia.index')
            ->with('success', 'Incidencia registrada exitosamente.');
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
