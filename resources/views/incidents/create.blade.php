@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Reportar Nueva Incidencia</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Infraestructura</label>
                            <select name="infrastructure_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($infrastructures as $infrastructure)
                                    <option value="{{ $infrastructure->id }}">{{ $infrastructure->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prioridad</label>
                            <select name="priority" class="form-select" required>
                                <option value="Baja">Baja</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ubicación</label>
                            <div id="map" style="height: 300px"></div>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fotos</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Puede seleccionar múltiples fotos (máx. 2MB cada una)</small>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Reportar Incidencia</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const map = L.map('map').setView([4.6097, -74.0817], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    let marker;
    map.on('click', function(e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });
</script>
@endpush