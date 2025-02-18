<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,supervisor']);
    }

    public function index(Request $request)
    {
        $query = Personal::with(['user']);

        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        if ($request->filled('disponibilidad')) {
            $query->where('disponibilidad', $request->disponibilidad);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $personal = $query->paginate(10);

        return view('personal.index', compact('personal'));
    }

    public function create()
    {
        $especialidades = [
            'electricista' => 'Electricista',
            'plomero' => 'Plomero',
            'carpintero' => 'Carpintero',
            'albañil' => 'Albañil',
            'jardinero' => 'Jardinero',
            'técnico_general' => 'Técnico General'
        ];

        return view('personal.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'especialidad' => 'required|string',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'habilidades' => 'nullable|array',
            'notas' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'tecnico'
            ]);

            $personal = Personal::create([
                'user_id' => $user->id,
                'especialidad' => $validated['especialidad'],
                'disponibilidad' => 'disponible',
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'],
                'habilidades' => $validated['habilidades'] ?? [],
                'notas' => $validated['notas']
            ]);

            DB::commit();

            return redirect()->route('personal.index')
                ->with('success', 'Personal creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el personal. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Personal $personal)
    {
        $personal->load(['user', 'ordenesAsignadas', 'rutas']);
        
        return view('personal.show', compact('personal'));
    }

    public function edit(Personal $personal)
    {
        $especialidades = [
            'electricista' => 'Electricista',
            'plomero' => 'Plomero',
            'carpintero' => 'Carpintero',
            'albañil' => 'Albañil',
            'jardinero' => 'Jardinero',
            'técnico_general' => 'Técnico General'
        ];

        return view('personal.edit', compact('personal', 'especialidades'));
    }

    public function update(Request $request, Personal $personal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $personal->user_id,
            'especialidad' => 'required|string',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
            'habilidades' => 'nullable|array',
            'notas' => 'nullable|string',
            'disponibilidad' => 'required|in:disponible,ocupado,ausente'
        ]);

        try {
            DB::beginTransaction();

            $personal->user->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            $personal->update([
                'especialidad' => $validated['especialidad'],
                'disponibilidad' => $validated['disponibilidad'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'],
                'habilidades' => $validated['habilidades'] ?? [],
                'notas' => $validated['notas']
            ]);

            DB::commit();

            return redirect()->route('personal.index')
                ->with('success', 'Personal actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el personal. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Personal $personal)
    {
        try {
            DB::beginTransaction();

            // Verificar si tiene órdenes de trabajo pendientes
            if ($personal->ordenesAsignadas()->where('estado', 'pendiente')->exists()) {
                throw new \Exception('No se puede eliminar personal con órdenes de trabajo pendientes.');
            }

            $personal->delete();
            $personal->user->delete();

            DB::commit();

            return redirect()->route('personal.index')
                ->with('success', 'Personal eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el personal. ' . $e->getMessage());
        }
    }
}
