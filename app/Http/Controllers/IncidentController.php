<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Infrastructure;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['infrastructure', 'status', 'assignedTo'])
            ->latest();

        // Filtros
        if ($request->status) {
            $query->where('status_id', $request->status);
        }
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }
        if ($request->infrastructure) {
            $query->where('infrastructure_id', $request->infrastructure);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $incidents = $query->paginate(10);
        $statuses = Status::all();
        $infrastructures = Infrastructure::all();

        return view('incidents.index', compact('incidents', 'statuses', 'infrastructures'));
    }

    public function create()
    {
        $infrastructures = Infrastructure::all();
        return view('incidents.create', compact('infrastructures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'infrastructure_id' => 'required|exists:infrastructures,id',
            'priority' => 'required|in:Baja,Media,Alta',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $incident = Incident::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'infrastructure_id' => $validated['infrastructure_id'],
            'priority' => $validated['priority'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status_id' => Status::where('name', 'Pendiente')->first()->id,
            'user_id' => auth()->id()
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('incidents', 'public');
                $incident->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incidencia reportada correctamente');
    }

    public function show(Incident $incident)
    {
        $incident->load(['infrastructure', 'status', 'assignedTo', 'photos', 'history']);
        return view('incidents.show', compact('incident'));
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'status_id' => 'required|exists:status,id',
            'comment' => 'required|string'
        ]);

        $incident->update(['status_id' => $validated['status_id']]);
        
        // Registrar en el historial
        $incident->history()->create([
            'user_id' => auth()->id(),
            'status_id' => $validated['status_id'],
            'comment' => $validated['comment']
        ]);

        return back()->with('success', 'Estado actualizado correctamente');
    }
}