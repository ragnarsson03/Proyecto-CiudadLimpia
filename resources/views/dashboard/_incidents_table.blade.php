<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Últimas Incidencias</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentIncidents as $incident)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $incident->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $incident->tipo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $incident->ubicacion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $incident->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $incident->estado === 'en_proceso' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $incident->estado === 'completada' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $incident->estado === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $estados[$incident->estado] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $prioridades[$incident->prioridad] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $incident->technician ? $incident->technician->name : 'Sin asignar' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 