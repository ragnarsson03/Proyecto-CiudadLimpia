<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de Incidencia') }}
            </h2>
            <div class="flex space-x-4">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico' || auth()->user()->id === $incidencia->ciudadano_id)
                <a href="{{ route('incidencia.edit', $incidencia) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                @endif
                <a href="{{ route('incidencia.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <h3 class="text-lg font-medium text-gray-900">Información de la Incidencia</h3>
                            <dl class="mt-4 space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $incidencia->tipo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $incidencia->ubicacion }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $incidencia->descripcion }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($incidencia->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($incidencia->estado === 'en_proceso') bg-blue-100 text-blue-800
                                            @elseif($incidencia->estado === 'resuelto') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($incidencia->estado) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Prioridad</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($incidencia->prioridad === 'baja') bg-green-100 text-green-800
                                            @elseif($incidencia->prioridad === 'media') bg-yellow-100 text-yellow-800
                                            @elseif($incidencia->prioridad === 'alta') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($incidencia->prioridad) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Reporte</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $incidencia->fecha->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Información Adicional</h3>
                            <dl class="mt-4 space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Infraestructura Afectada</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('infraestructura.show', $incidencia->infraestructura) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $incidencia->infraestructura->tipo }} - {{ $incidencia->infraestructura->ubicacion }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reportado por</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $incidencia->ciudadano->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Técnico Asignado</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $incidencia->tecnico ? $incidencia->tecnico->name : 'Sin asignar' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900">Actualización de Estado</h3>
                        <form method="POST" action="{{ route('incidencia.update', $incidencia) }}" class="mt-4">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <select name="estado" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="pendiente" {{ $incidencia->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en_proceso" {{ $incidencia->estado === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="resuelto" {{ $incidencia->estado === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                        <option value="cancelado" {{ $incidencia->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                                <x-primary-button>{{ __('Actualizar Estado') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
