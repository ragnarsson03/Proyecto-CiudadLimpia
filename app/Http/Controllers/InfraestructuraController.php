<?php

namespace App\Http\Controllers;

use App\Models\Infraestructura;
use Illuminate\Http\Request;

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
        $infraestructuras = Infraestructura::latest()->paginate(10);
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
            'descripcion' => 'required|string',
            'estado' => 'required|in:operativo,mantenimiento,reparacion,fuera_de_servicio',
        ]);

        Infraestructura::create($validated);

        return redirect()->route('infraestructura.index')
            ->with('success', 'Infraestructura creada exitosamente.');
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
            'descripcion' => 'required|string',
            'estado' => 'required|in:operativo,mantenimiento,reparacion,fuera_de_servicio',
        ]);

        $infraestructura->update($validated);

        return redirect()->route('infraestructura.index')
            ->with('success', 'Infraestructura actualizada exitosamente.');
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

        return redirect()->route('infraestructura.index')
            ->with('success', 'Infraestructura eliminada exitosamente.');
    }
}
