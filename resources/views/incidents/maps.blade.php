@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .incident-popup {
        font-family: 'Arial', sans-serif;
    }
    .incident-popup h3 {
        color: #2c3e50;
        margin-bottom: 8px;
    }
    .incident-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        margin-top: 8px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2>Mapa de Incidencias</h2>
                </div>
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el mapa centrado en una ubicación por defecto
        const map = L.map('map').setView([-12.0464, -77.0428], 12); // Coordenadas de Lima

        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Función para cargar incidencias
        function loadIncidents() {
            fetch('/api/incidents')
                .then(response => response.json())
                .then(incidents => {
                    incidents.forEach(incident => {
                        const marker = L.marker([incident.latitude, incident.longitude])
                            .addTo(map);

                        const popupContent = `
                            <div class="incident-popup">
                                <h3>${incident.title}</h3>
                                <p>${incident.description}</p>
                                <span class="incident-status" style="background-color: ${incident.status.color}">
                                    ${incident.status.name}
                                </span>
                            </div>
                        `;

                        marker.bindPopup(popupContent);
                    });
                });
        }

        // Cargar incidencias inicialmente
        loadIncidents();

        // Actualizar cada 30 segundos
        setInterval(loadIncidents, 30000);
    });
</script>
@endsection 