<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Infraestructuras') }}
            </h2>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
            <a href="{{ route('infraestructura.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Nueva Infraestructura
            </a>
            @endif
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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Revisión</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($infraestructuras as $infraestructura)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $infraestructura->tipo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $infraestructura->ubicacion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($infraestructura->estado === 'operativo') bg-green-100 text-green-800
                                            @elseif($infraestructura->estado === 'mantenimiento') bg-yellow-100 text-yellow-800
                                            @elseif($infraestructura->estado === 'reparacion') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($infraestructura->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $infraestructura->ultima_revision ? $infraestructura->ultima_revision->format('d/m/Y H:i') : 'No registrada' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('infraestructura.show', $infraestructura) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                                        <a href="{{ route('infraestructura.edit', $infraestructura) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('infraestructura.destroy', $infraestructura) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que deseas eliminar esta infraestructura?')">
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
                        {{ $infraestructuras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
