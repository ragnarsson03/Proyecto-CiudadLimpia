<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <x-dashboard-stat-card
                    title="Incidencias Activas"
                    :value="$activeIncidents"
                    icon="exclamation-circle"
                    color="red"/>
                
                <x-dashboard-stat-card
                    title="Tareas Pendientes"
                    :value="$pendingTasks"
                    icon="clipboard-list"
                    color="yellow"/>
                
                <x-dashboard-stat-card
                    title="Personal Disponible"
                    :value="$availableStaff"
                    icon="users"
                    color="green"/>
                
                <x-dashboard-stat-card
                    title="Presupuesto Mensual"
                    :value="'$' . $monthlyBudget"
                    icon="currency-dollar"
                    color="blue"/>
            </div>

            <!-- Estadísticas por Estado -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Incidencias por Estado</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($estados as $key => $nombre)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $nombre }}</h3>
                            <p class="text-3xl font-bold mt-2">
                                {{ isset($incidenciasPorEstado[$key]) ? $incidenciasPorEstado[$key]['total'] : 0 }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Filtros -->
            @include('dashboard._filters', [
                'estados' => $estados,
                'tiposInfraestructura' => $tiposInfraestructura,
                'prioridades' => $prioridades
            ])

            <!-- Tabla de incidencias -->
            @include('dashboard._incidents_table', [
                'recentIncidents' => $recentIncidents,
                'estados' => $estados,
                'prioridades' => $prioridades
            ])

            <!-- Acciones Rápidas -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Panel de Control</h2>
                <div class="space-x-4">
                    <div class="inline-flex rounded-md shadow-sm">
                        @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
                            <a href="{{ route('export.incidencias', ['format' => 'pdf']) }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-l-md hover:bg-red-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                PDF
                            </a>
                            <a href="{{ route('export.incidencias', ['format' => 'excel']) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-r-md hover:bg-green-700 border-l border-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Excel
                            </a>
                        @endif
                    </div>
                    @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('incidencia.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Reportar Incidencia
                        </a>
                    @endif
                    <x-responsive-nav-link :href="route('infraestructura.index')" :active="request()->routeIs('infraestructura.index')">
                        {{ __('Infraestructura') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Mapa Interactivo -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <div id="map" class="h-96"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    
    <script>
        // Datos del controlador
        const estadosData = {!! json_encode($incidenciasPorEstado) !!};
        const tiposData = {!! json_encode($incidenciasPorTipo) !!};
        const incidencias = {!! json_encode($ultimasIncidencias) !!};

        // Configuración de colores para los gráficos
        const colors = {
            pendiente: '#FFA500',
            en_proceso: '#3498db',
            resuelto: '#2ecc71',
            cancelado: '#e74c3c'
        };

        // Configuración del gráfico circular
        const ctxEstados = document.getElementById('estadosChart').getContext('2d');
        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: Object.keys(estadosData),
                datasets: [{
                    data: Object.values(estadosData),
                    backgroundColor: Object.keys(estadosData).map(estado => colors[estado] || '#95a5a6'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Incidencias por Estado',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Configuración del gráfico de barras
        const ctxTipos = document.getElementById('tiposChart').getContext('2d');
        new Chart(ctxTipos, {
            type: 'bar',
            data: {
                labels: Object.keys(tiposData),
                datasets: [{
                    label: 'Total de Incidencias',
                    data: Object.values(tiposData).map(item => item.total),
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Incidencias por Tipo de Infraestructura',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Inicialización y configuración del mapa
        if (document.getElementById('incidenciasMap')) {
            const map = L.map('incidenciasMap').setView([-33.4569, -70.6483], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ' OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Función para obtener el color del marcador según el estado
            const getMarkerColor = (estado) => colors[estado] || '#95a5a6';

            // Crear marcadores personalizados para cada incidencia
            incidencias.forEach(incidencia => {
                const infraestructura = incidencia.infraestructura;
                if (infraestructura && infraestructura.latitud && infraestructura.longitud) {
                    const markerHtml = `
                        <div class="marker-pin" style="background-color: ${getMarkerColor(incidencia.estado)}">
                            <span class="marker-number">${incidencia.id}</span>
                        </div>
                    `;

                    const icon = L.divIcon({
                        html: markerHtml,
                        className: 'custom-marker',
                        iconSize: [30, 42],
                        iconAnchor: [15, 42]
                    });

                    const marker = L.marker(
                        [infraestructura.latitud, infraestructura.longitud],
                        { icon: icon }
                    ).addTo(map);

                    // Popup personalizado con información detallada
                    const popupContent = `
                        <div class="popup-content">
                            <h3 class="text-lg font-bold mb-2">${infraestructura.tipo}</h3>
                            <p class="text-sm mb-1"><strong>Estado:</strong> ${incidencia.estado}</p>
                            <p class="text-sm mb-1"><strong>Ubicación:</strong> ${infraestructura.ubicacion}</p>
                            <p class="text-sm mb-1"><strong>Fecha:</strong> ${new Date(incidencia.fecha).toLocaleDateString()}</p>
                            <p class="text-sm"><strong>Descripción:</strong> ${incidencia.descripcion}</p>
                            ${incidencia.tecnico ? `
                                <p class="text-sm mt-2"><strong>Técnico:</strong> ${incidencia.tecnico.nombre}</p>
                            ` : ''}
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                }
            });

            // Ajustar el mapa para mostrar todos los marcadores
            const markers = Object.values(map._layers).filter(layer => layer instanceof L.Marker);
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }
    </script>

    <style>
        .custom-marker {
            background: none;
            border: none;
        }

        .marker-pin {
            width: 30px;
            height: 42px;
            border-radius: 50% 50% 50% 0;
            position: relative;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .marker-number {
            transform: rotate(45deg);
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .popup-content {
            padding: 10px;
            max-width: 250px;
        }

        .popup-content h3 {
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .popup-content p {
            margin-bottom: 5px;
            color: #34495e;
        }

        #incidenciasMap {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
        }
    </style>
    @endpush
</x-app-layout>