<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="todos" {{ $filtroEstado == 'todos' ? 'selected' : '' }}>Todos</option>
                            <option value="pendiente" {{ $filtroEstado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_proceso" {{ $filtroEstado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="resuelto" {{ $filtroEstado == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Infraestructura</label>
                        <select name="infraestructura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="todos">Todas</option>
                            @foreach($infraestructuras as $infra)
                                <option value="{{ $infra->id }}" {{ $filtroInfraestructura == $infra->id ? 'selected' : '' }}>
                                    {{ $infra->tipo }} - {{ $infra->ubicacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Período</label>
                        <select name="periodo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="7" {{ $filtroPeriodo == '7' ? 'selected' : '' }}>Última semana</option>
                            <option value="30" {{ $filtroPeriodo == '30' ? 'selected' : '' }}>Último mes</option>
                            <option value="90" {{ $filtroPeriodo == '90' ? 'selected' : '' }}>Últimos 3 meses</option>
                            <option value="365" {{ $filtroPeriodo == '365' ? 'selected' : '' }}>Último año</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>

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
                    <a href="{{ route('infraestructura.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        Ver Infraestructuras
                    </a>
                </div>
            </div>

            <!-- Resumen de Infraestructuras -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Infraestructuras</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $totalInfraestructuras }}</div>
                        <div class="text-green-500 text-sm">
                            <span class="font-bold">{{ $infraestructurasOperativas }}</span> operativas
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">En Mantenimiento</div>
                        <div class="text-3xl font-bold text-yellow-500">{{ $infraestructurasMantenimiento }}</div>
                        <div class="text-gray-500 text-sm">
                            <span class="font-bold">{{ $infraestructurasFueraServicio }}</span> fuera de servicio
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Incidencias</div>
                        <div class="text-3xl font-bold text-blue-500">{{ $totalIncidencias }}</div>
                        <div class="text-gray-500 text-sm">
                            <span class="font-bold">{{ $incidenciasHoy }}</span> reportadas hoy
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Incidencias Activas</div>
                        <div class="text-3xl font-bold text-red-500">{{ $incidenciasPorEstado['pendiente'] ?? 0 }}</div>
                        <div class="text-gray-500 text-sm">
                            <span class="font-bold">{{ $incidenciasPorEstado['en_proceso'] ?? 0 }}</span> en proceso
                        </div>
                    </div>
                </div>
            </div>

            <!-- Infraestructuras más Afectadas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Infraestructuras más Afectadas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Infraestructura</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Incidencias</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($infraestructurasMasAfectadas as $infra)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $infra->tipo }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $infra->ubicacion }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $infra->total_incidencias }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $infra->estado === 'operativo' ? 'bg-green-100 text-green-800' : 
                                                   ($infra->estado === 'mantenimiento' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($infra->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @if(empty($incidenciasPorEstado) && empty($incidenciasPorTipo))
                    <div class="col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-gray-500 text-center py-4">
                            No hay datos disponibles para el período seleccionado
                        </div>
                    </div>
                @else
                    <!-- Gráfico de Incidencias por Estado -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Incidencias por Estado</h3>
                        <canvas id="estadosChart"></canvas>
                    </div>

                    <!-- Gráfico de Incidencias por Tipo -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Incidencias por Tipo de Infraestructura</h3>
                        <canvas id="tiposChart"></canvas>
                    </div>
                @endif
            </div>

            <!-- Mapa de Incidencias -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mapa de Incidencias</h3>
                <div id="incidenciasMap" class="h-96 w-full rounded-lg"></div>
            </div>

            <!-- Últimas Actualizaciones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Últimas Incidencias -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Últimas Incidencias</h3>
                        <div class="space-y-4">
                            @foreach($ultimasIncidencias as $incidencia)
                                <div class="border-l-4 {{ $incidencia['estado'] === 'pendiente' ? 'border-yellow-500' : ($incidencia['estado'] === 'en_proceso' ? 'border-blue-500' : 'border-green-500') }} pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold">{{ $incidencia['infraestructura']['tipo'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $incidencia['infraestructura']['ubicacion'] }}</p>
                                            <p class="text-sm mt-1">{{ $incidencia['descripcion'] }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $incidencia['estado'] === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($incidencia['estado'] === 'en_proceso' ? 'bg-blue-100 text-blue-800' : 
                                                    'bg-green-100 text-green-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $incidencia['estado'])) }}
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($incidencia['fecha'])->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($incidencia['tecnico'])
                                        <p class="text-sm text-gray-600 mt-2">
                                            <span class="font-medium">Técnico:</span> {{ $incidencia['tecnico']['nombre'] }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
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