@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalles de la Incidencia</h5>
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información Principal -->
                <div class="col-md-8">
                    <h4>{{ $incident->title }}</h4>
                    <p class="text-muted">
                        Reportado el {{ $incident->created_at->format('d/m/Y H:i') }}
                        por {{ $incident->user->name }}
                    </p>
                    <div class="mb-4">
                        <h6>Descripción:</h6>
                        <p>{{ $incident->description }}</p>
                    </div>
                    
                    <!-- Estado y Prioridad -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Estado:</h6>
                            <span class="badge bg-{{ $incident->status->color }}">
                                {{ $incident->status->name }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6>Prioridad:</h6>
                            <span class="badge bg-{{ $incident->priority_color }}">
                                {{ $incident->priority }}
                            </span>
                        </div>
                    </div>

                    <!-- Fotos -->
                    @if($incident->photos->count() > 0)
                    <div class="mb-4">
                        <h6>Fotos:</h6>
                        <div class="row">
                            @foreach($incident->photos as $photo)
                            <div class="col-md-4 mb-3">
                                <img src="{{ Storage::url($photo->path) }}" class="img-fluid rounded" alt="Foto de incidencia">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Historial -->
                    <div class="mb-4">
                        <h6>Historial de Cambios:</h6>
                        <div class="timeline">
                            @foreach($incident->history as $history)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <p class="mb-1">
                                        <strong>{{ $history->user->name }}</strong>
                                        cambió el estado a
                                        <span class="badge bg-{{ $history->status->color }}">
                                            {{ $history->status->name }}
                                        </span>
                                    </p>
                                    <p class="text-muted mb-0">
                                        {{ $history->comment }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $history->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Mapa -->
                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <div id="map" style="height: 300px;"></div>
                        </div>
                    </div>

                    <!-- Información de Infraestructura -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Infraestructura:</h6>
                            <p class="mb-0">{{ $incident->infrastructure->nombre }}</p>
                        </div>
                    </div>

                    <!-- Técnico Asignado -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Técnico Asignado:</h6>
                            <p class="mb-0">{{ $incident->assignedTo->name ?? 'Sin asignar' }}</p>
                        </div>
                    </div>

                    <!-- Actualizar Estado (solo para técnicos y administradores) -->
                    @if(auth()->user()->hasRole(['Técnico', 'Administrador']))
                    <div class="card">
                        <div class="card-body">
                            <h6>Actualizar Estado</h6>
                            <form action="{{ route('incidents.updateStatus', $incident) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <select name="status_id" class="form-select">
                                        @foreach(\App\Models\Status::all() as $status)
                                            <option value="{{ $status->id }}" @selected($incident->status_id == $status->id)>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Comentario del cambio" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Actualizar Estado
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const map = L.map('map').setView([{{ $incident->latitude }}, {{ $incident->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}])
        .bindPopup(`<strong>{{ $incident->title }}</strong>`)
        .addTo(map);
</script>
@endpush

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}
.timeline-item:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 2px;
    background-color: #e9ecef;
}
.timeline-item:after {
    content: '';
    position: absolute;
    left: -4px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #007bff;
}
.timeline-content {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 4px;
}
</style>
@endsection