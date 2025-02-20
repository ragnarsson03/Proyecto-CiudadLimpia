<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Infraestructuras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Lista de Infraestructuras</h2>
                        <a href="{{ route('infraestructura.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nueva Infraestructura
                        </a>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-4">
                        <form action="{{ route('infraestructura.index') }}" method="GET" class="flex gap-4">
                            <select name="tipo" class="rounded-md border-gray-300">
                                <option value="">Todos los tipos</option>
                                <option value="Semáforo" {{ request('tipo') == 'Semáforo' ? 'selected' : '' }}>Semáforo</option>
                                <option value="Contenedor" {{ request('tipo') == 'Contenedor' ? 'selected' : '' }}>Contenedor</option>
                                <option value="Luminaria" {{ request('tipo') == 'Luminaria' ? 'selected' : '' }}>Luminaria</option>
                            </select>
                            <select name="estado" class="rounded-md border-gray-300">
                                <option value="">Todos los estados</option>
                                <option value="operativo" {{ request('estado') == 'operativo' ? 'selected' : '' }}>Operativo</option>
                                <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                                <option value="fuera_de_servicio" {{ request('estado') == 'fuera_de_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                            </select>
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                        </form>
                    </div>

                    <!-- Tabla -->
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
                                            {{ $infraestructura->estado === 'operativo' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $infraestructura->estado === 'mantenimiento' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $infraestructura->estado === 'fuera_de_servicio' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($infraestructura->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $infraestructura->ultima_revision ? $infraestructura->ultima_revision->format('d/m/Y H:i') : 'No registrada' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('infraestructura.show', $infraestructura) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                        <a href="{{ route('infraestructura.edit', $infraestructura) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('infraestructura.destroy', $infraestructura) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Está seguro de eliminar esta infraestructura?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $infraestructuras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
