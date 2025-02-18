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
                        <canvas id="incidenciasPorEstadoChart"></canvas>
                    </div>

                    <!-- Gráfico de Incidencias por Tipo -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Incidencias por Tipo de Infraestructura</h3>
                        <canvas id="incidenciasPorTipoChart"></canvas>
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
                                <div class="border-l-4 {{ $incidencia->estado === 'pendiente' ? 'border-yellow-500' : ($incidencia->estado === 'en_proceso' ? 'border-blue-500' : 'border-green-500') }} pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $incidencia->infraestructura->tipo }}</p>
                                            <p class="text-sm text-gray-500">{{ $incidencia->fecha->format('d/m/Y H:i') }}</p>
                                            <p class="text-sm text-gray-600">{{ Str::limit($incidencia->descripcion, 100) }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $incidencia->prioridad === 'baja' ? 'bg-green-100 text-green-800' : ($incidencia->prioridad === 'media' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($incidencia->prioridad) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    
    <script>
        // Configuración de gráficos
        const estadosData = {!! json_encode($incidenciasPorEstado) !!};
        const tiposData = {!! json_encode($incidenciasPorTipo) !!};

        // Gráfico de Incidencias por Estado
        if (document.getElementById('incidenciasPorEstadoChart')) {
            new Chart(document.getElementById('incidenciasPorEstadoChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(estadosData),
                    datasets: [{
                        data: Object.values(estadosData),
                        backgroundColor: ['#FCD34D', '#60A5FA', '#34D399', '#F87171']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        // Gráfico de Incidencias por Tipo
        if (document.getElementById('incidenciasPorTipoChart')) {
            new Chart(document.getElementById('incidenciasPorTipoChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(tiposData),
                    datasets: [{
                        label: 'Número de Incidencias',
                        data: Object.values(tiposData),
                        backgroundColor: '#4F46E5'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // Mapa
        const mapElement = document.getElementById('incidenciasMap');
        if (mapElement) {
            const map = L.map('incidenciasMap').setView([-33.4489, -70.6693], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ' OpenStreetMap contributors'
            }).addTo(map);

            const incidenciasData = {!! json_encode($ultimasIncidencias->map(function($i) {
                return [
                    'lat' => $i->latitud,
                    'lng' => $i->longitud,
                    'title' => $i->infraestructura->tipo,
                    'estado' => $i->estado,
                    'prioridad' => $i->prioridad
                ];
            })) !!};

            incidenciasData.forEach(function(i) {
                if (i.lat && i.lng) {
                    L.marker([i.lat, i.lng])
                        .bindPopup(`
                            <strong>${i.title}</strong><br>
                            Estado: ${i.estado}<br>
                            Prioridad: ${i.prioridad}
                        `)
                        .addTo(map);
                }
            });
        }
    </script>
    @endpush
</x-app-layout>