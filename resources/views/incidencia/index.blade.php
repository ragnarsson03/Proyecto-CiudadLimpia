<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Incidencias') }}
            </h2>
            <a href="{{ route('incidencia.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Reportar Nueva Incidencia
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="px-3 py-1 text-sm rounded-full {{ request('estado') == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                Pendientes
                            </button>
                            <button type="button" class="px-3 py-1 text-sm rounded-full {{ request('estado') == 'en_proceso' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                En Proceso
                            </button>
                            <button type="button" class="px-3 py-1 text-sm rounded-full {{ request('estado') == 'resuelto' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                Resueltas
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="px-3 py-1 text-sm rounded-full {{ request('prioridad') == 'alta' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                Alta Prioridad
                            </button>
                            <button type="button" class="px-3 py-1 text-sm rounded-full {{ request('prioridad') == 'media' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                Media Prioridad
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($incidencias as $incidencia)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $incidencia->fecha->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $incidencia->tipo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $incidencia->ubicacion }}</td>
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
                                        {{ $incidencia->tecnico ? $incidencia->tecnico->name : 'Sin asignar' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('incidencia.show', $incidencia) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico' || auth()->user()->id === $incidencia->ciudadano_id)
                                        <a href="{{ route('incidencia.edit', $incidencia) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        @endif
                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('incidencia.destroy', $incidencia) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que deseas eliminar esta incidencia?')">
                                                Eliminar
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $incidencias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
