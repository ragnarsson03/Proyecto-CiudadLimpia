<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de Infraestructura') }}
            </h2>
            <div class="flex space-x-4">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                <a href="{{ route('infraestructura.edit', $infraestructura) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                @endif
                <a href="{{ route('infraestructura.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                            <dl class="mt-4 space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $infraestructura->tipo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $infraestructura->ubicacion }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $infraestructura->descripcion }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($infraestructura->estado === 'operativo') bg-green-100 text-green-800
                                            @elseif($infraestructura->estado === 'mantenimiento') bg-yellow-100 text-yellow-800
                                            @elseif($infraestructura->estado === 'reparacion') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($infraestructura->estado) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Última Revisión</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $infraestructura->ultima_revision ? $infraestructura->ultima_revision->format('d/m/Y H:i') : 'No registrada' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Historial de Mantenimiento</h3>
                            @if($infraestructura->historial_mantenimiento)
                            <div class="mt-4 space-y-4">
                                @foreach($infraestructura->historial_mantenimiento as $mantenimiento)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <p class="text-sm text-gray-600">{{ $mantenimiento['fecha'] }}</p>
                                    <p class="text-sm text-gray-900">{{ $mantenimiento['descripcion'] }}</p>
                                    <p class="text-sm text-gray-600">Realizado por: {{ $mantenimiento['tecnico'] }}</p>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="mt-4 text-sm text-gray-500">No hay registros de mantenimiento</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900">Incidencias Relacionadas</h3>
                        @if($infraestructura->incidencias->count() > 0)
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioridad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($infraestructura->incidencias as $incidencia)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $incidencia->fecha->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $incidencia->tipo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($incidencia->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                                @elseif($incidencia->estado === 'en_proceso') bg-blue-100 text-blue-800
                                                @elseif($incidencia->estado === 'resuelto') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($incidencia->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($incidencia->prioridad === 'baja') bg-green-100 text-green-800
                                                @elseif($incidencia->prioridad === 'media') bg-yellow-100 text-yellow-800
                                                @elseif($incidencia->prioridad === 'alta') bg-orange-100 text-orange-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($incidencia->prioridad) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('incidencia.show', $incidencia) }}" class="text-blue-600 hover:text-blue-900">Ver Detalles</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="mt-4 text-sm text-gray-500">No hay incidencias registradas para esta infraestructura</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
