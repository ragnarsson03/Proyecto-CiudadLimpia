@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-exclamation-circle"></i> 
            Incidencias
        </h2>
        @role('Ciudadano')
        <a href="{{ route('incidents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Incidencia
        </a>
        @endrole
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('incidents.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @selected(request('status') == $status->id)>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Prioridad</label>
                    <select name="priority" class="form-select">
                        <option value="">Todas</option>
                        <option value="Baja" @selected(request('priority') == 'Baja')>Baja</option>
                        <option value="Media" @selected(request('priority') == 'Media')>Media</option>
                        <option value="Alta" @selected(request('priority') == 'Alta')>Alta</option>
                        <option value="Crítica" @selected(request('priority') == 'Crítica')>Crítica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Infraestructura</label>
                    <select name="infrastructure" class="form-select">
                        <option value="">Todas</option>
                        @foreach($infrastructures as $infrastructure)
                            <option value="{{ $infrastructure->id }}" @selected(request('infrastructure') == $infrastructure->id)>
                                {{ $infrastructure->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Incidencias -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Infraestructura</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Técnico</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incidents as $incident)
                            <tr>
                                <td>{{ $incident->id }}</td>
                                <td>{{ $incident->title }}</td>
                                <td>{{ $incident->infrastructure->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $incident->status->color }}">
                                        {{ $incident->status->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $incident->priority_color }}">
                                        {{ $incident->priority }}
                                    </span>
                                </td>
                                <td>{{ $incident->assignedTo->name ?? 'Sin asignar' }}</td>
                                <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('incidents.show', $incident) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay incidencias registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $incidents->links() }}
        </div>
    </div>
</div>
@endsection