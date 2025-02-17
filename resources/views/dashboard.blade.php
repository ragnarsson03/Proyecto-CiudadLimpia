<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <div class="text-gray-500 text-sm">Incidencias Pendientes</div>
                        <div class="text-3xl font-bold text-red-500">{{ $incidenciasPendientes }}</div>
                        <div class="text-gray-500 text-sm">
                            <span class="font-bold">{{ $incidenciasEnProceso }}</span> en proceso
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Gráfico de Incidencias por Estado -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Incidencias por Estado</h3>
                        <canvas id="incidenciasPorEstado" class="w-full" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Incidencias por Prioridad -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Incidencias por Prioridad</h3>
                        <canvas id="incidenciasPorPrioridad" class="w-full" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Últimas Actualizaciones -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Últimas Incidencias -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Últimas Incidencias</h3>
                        <div class="space-y-4">
                            @foreach($ultimasIncidencias as $incidencia)
                            <div class="border-l-4 
                                @if($incidencia->estado === 'pendiente') border-yellow-500
                                @elseif($incidencia->estado === 'en_proceso') border-blue-500
                                @elseif($incidencia->estado === 'resuelto') border-green-500
                                @else border-red-500
                                @endif pl-4 py-2">
                                <p class="text-sm font-medium text-gray-900">{{ $incidencia->tipo }}</p>
                                <p class="text-sm text-gray-500">{{ $incidencia->fecha->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($incidencia->descripcion, 100) }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($incidencia->prioridad === 'baja') bg-green-100 text-green-800
                                        @elseif($incidencia->prioridad === 'media') bg-yellow-100 text-yellow-800
                                        @elseif($incidencia->prioridad === 'alta') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($incidencia->prioridad) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Últimas Infraestructuras -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Infraestructuras Recientes</h3>
                        <div class="space-y-4">
                            @foreach($ultimasInfraestructuras as $infraestructura)
                            <div class="border-l-4 
                                @if($infraestructura->estado === 'operativo') border-green-500
                                @elseif($infraestructura->estado === 'mantenimiento') border-yellow-500
                                @elseif($infraestructura->estado === 'reparacion') border-orange-500
                                @else border-red-500
                                @endif pl-4 py-2">
                                <p class="text-sm font-medium text-gray-900">{{ $infraestructura->tipo }}</p>
                                <p class="text-sm text-gray-500">{{ $infraestructura->ubicacion }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($infraestructura->descripcion, 100) }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($infraestructura->estado === 'operativo') bg-green-100 text-green-800
                                        @elseif($infraestructura->estado === 'mantenimiento') bg-yellow-100 text-yellow-800
                                        @elseif($infraestructura->estado === 'reparacion') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($infraestructura->estado) }}
                                    </span>
                                </div>
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
    <script>
        // Datos para el gráfico de estados
        const ctxEstados = document.getElementById('incidenciasPorEstado').getContext('2d');
        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'En Proceso', 'Resueltas', 'Canceladas'],
                datasets: [{
                    data: [
                        {{ $incidenciasPendientes }},
                        {{ $incidenciasEnProceso }},
                        {{ $incidenciasResueltas }},
                        {{ $incidenciasCanceladas }}
                    ],
                    backgroundColor: [
                        '#FCD34D',
                        '#60A5FA',
                        '#34D399',
                        '#F87171'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Datos para el gráfico de prioridades
        const ctxPrioridades = document.getElementById('incidenciasPorPrioridad').getContext('2d');
        new Chart(ctxPrioridades, {
            type: 'bar',
            data: {
                labels: ['Baja', 'Media', 'Alta', 'Crítica'],
                datasets: [{
                    label: 'Incidencias por Prioridad',
                    data: [
                        {{ $incidenciasPrioridadBaja }},
                        {{ $incidenciasPrioridadMedia }},
                        {{ $incidenciasPrioridadAlta }},
                        {{ $incidenciasPrioridadCritica }}
                    ],
                    backgroundColor: [
                        '#34D399',
                        '#FCD34D',
                        '#F97316',
                        '#EF4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
