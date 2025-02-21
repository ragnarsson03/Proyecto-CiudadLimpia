@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-map-marked-alt"></i> Mapa de Incidencias</h4>
                </div>
                <div class="card-body p-0">
                    <div id="incidentsMap" style="height: 80vh;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leyenda</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Estados:</h6>
                        <div class="d-flex flex-column gap-2">
                            <span class="badge bg-warning">Pendiente</span>
                            <span class="badge bg-info">En Proceso</span>
                            <span class="badge bg-success">Resuelto</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6>Prioridades:</h6>
                        <div class="d-flex flex-column gap-2">
                            <span class="badge bg-success">Baja</span>
                            <span class="badge bg-warning">Media</span>
                            <span class="badge bg-danger">Alta</span>
                            <span class="badge bg-dark">Crítica</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Filtros</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Todos</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioridad</label>
                        <select id="priorityFilter" class="form-select">
                            <option value="">Todas</option>
                            <option value="Baja">Baja</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Crítica">Crítica</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inicializar mapa
    const map = L.map('incidentsMap').setView([4.6097, -74.0817], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Datos de incidencias
    const incidents = @json($incidents);
    let markers = [];

    function updateMarkers() {
        // Limpiar marcadores existentes
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];

        // Filtros
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;

        // Crear nuevos marcadores según filtros
        incidents.forEach(incident => {
            if ((statusFilter === '' || incident.status_id == statusFilter) &&
                (priorityFilter === '' || incident.priority === priorityFilter)) {
                
                const marker = L.marker([incident.latitude, incident.longitude])
                    .bindPopup(`
                        <strong>${incident.title}</strong><br>
                        Estado: <span class="badge bg-${incident.status.color}">${incident.status.name}</span><br>
                        Prioridad: <span class="badge bg-${incident.priority_color}">${incident.priority}</span><br>
                        <a href="/incidents/${incident.id}" class="btn btn-sm btn-info mt-2">Ver detalles</a>
                    `);
                
                marker.addTo(map);
                markers.push(marker);
            }
        });
    }

    // Eventos de filtros
    document.getElementById('statusFilter').addEventListener('change', updateMarkers);
    document.getElementById('priorityFilter').addEventListener('change', updateMarkers);

    // Actualización automática cada 30 segundos
    setInterval(() => {
        fetch('/api/incidents')
            .then(response => response.json())
            .then(data => {
                incidents = data;
                updateMarkers();
            });
    }, 30000);

    // Inicializar marcadores
    updateMarkers();
</script>
@endpush