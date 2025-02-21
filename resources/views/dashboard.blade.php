@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Tarjetas de Resumen -->
    <!-- Modifica la sección de tarjetas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">INCIDENCIAS ACTIVAS</h6>
                        <h2 class="mb-0">{{ $stats['activeIncidents'] }}</h2>
                    </div>
                    <i class="fas fa-exclamation-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">INFRAESTRUCTURAS</h6>
                    <h2 class="mb-0">{{ $stats['totalInfrastructures'] }}</h2>
                </div>
                <i class="fas fa-building fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">TÉCNICOS</h6>
                    <h2 class="mb-0">{{ $stats['totalTechnicians'] }}</h2>
                </div>
                <i class="fas fa-users-cog fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>
    </div>

    <!-- Gráficos -->
    <!-- Después de las tarjetas de resumen -->
    <div class="row mb-4">
        <!-- Mapa de Incidencias -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mapa de Incidencias Activas</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    
        <!-- Gráficos en columna derecha -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Incidencias por Estado</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Incidencias por Prioridad</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Tabla de Incidencias Recientes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Incidencias Recientes</h5>
            <div>
                <a href="{{ route('incidents.excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('incidents.pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>
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
                        @forelse($recentIncidents as $incident)
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
                                    <a href="{{ route('incidents.show', $incident) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay incidencias recientes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inicializar mapa centrado en Bogotá
    const map = L.map('map').setView([4.6097, -74.0817], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    // Agregar marcadores de incidencias
    @foreach($incidents as $incident)
        L.marker([{{ $incident->latitude }}, {{ $incident->longitude }}])
            .bindPopup(`
                <strong>{{ $incident->title }}</strong><br>
                Estado: <span class="badge bg-{{ $incident->status->color }}">{{ $incident->status->name }}</span><br>
                Prioridad: {{ $incident->priority }}
            `)
            .addTo(map);
    @endforeach

    // Gráfico de Estados
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($incidentsByStatus->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($incidentsByStatus->pluck('incidents_count')) !!},
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
            }]
        }
    });

    // Gráfico de Prioridades
    new Chart(document.getElementById('priorityChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($incidentsByPriority->pluck('priority')) !!},
            datasets: [{
                label: 'Incidencias',
                data: {!! json_encode($incidentsByPriority->pluck('total')) !!},
                backgroundColor: '#0d6efd'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush