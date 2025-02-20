@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-33.4489, -70.6693], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const infraestructuras = @json($infraestructuras);
    
    infraestructuras.forEach(infra => {
        L.marker([infra.latitud, infra.longitud])
            .bindPopup(`
                <strong>${infra.tipo}</strong><br>
                ${infra.ubicacion}<br>
                Estado: ${infra.estado}
            `)
            .addTo(map);
    });
</script>
@endsection 