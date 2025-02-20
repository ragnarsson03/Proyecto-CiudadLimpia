<?php

namespace App\Http\Controllers;

use App\Models\Infraestructura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\InfraestructuraRequest;

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
    public function index(Request $request)
    {
        $query = Infraestructura::query();

        // Filtros
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $infraestructuras = $query->paginate(10);

        return view('infraestructura.index', compact('infraestructuras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('infraestructuras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InfraestructuraRequest $request)
    {
        $infraestructura = Infraestructura::create($request->validated());

        return redirect()
            ->route('infraestructura.show', $infraestructura)
            ->with('success', 'Infraestructura creada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function show(Infraestructura $infraestructura)
    {
        $infraestructura->load(['incidencias', 'mantenimientosPreventivos', 'ordenesTrabajos']);
        
        return view('infraestructuras.show', compact('infraestructura'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function edit(Infraestructura $infraestructura)
    {
        return view('infraestructuras.edit', compact('infraestructura'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function update(InfraestructuraRequest $request, Infraestructura $infraestructura)
    {
        $infraestructura->update($request->validated());

        return redirect()
            ->route('infraestructura.show', $infraestructura)
            ->with('success', 'Infraestructura actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Infraestructura  $infraestructura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Infraestructura $infraestructura)
    {
        $infraestructura->delete();

        return redirect()
            ->route('infraestructura.index')
            ->with('success', 'Infraestructura eliminada exitosamente');
    }

    public function mapa()
    {
        $infraestructuras = Infraestructura::all();
        return view('infraestructuras.mapa', compact('infraestructuras'));
    }
}
