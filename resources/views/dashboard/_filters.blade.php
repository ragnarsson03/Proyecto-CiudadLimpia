<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
    <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
            <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos los estados</option>
                @foreach($estados as $value => $label)
                    <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="tipo_infraestructura" class="block text-sm font-medium text-gray-700">Tipo de Infraestructura</label>
            <select name="tipo_infraestructura" id="tipo_infraestructura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos los tipos</option>
                @foreach($tiposInfraestructura as $value => $label)
                    <option value="{{ $value }}" {{ request('tipo_infraestructura') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="prioridad" class="block text-sm font-medium text-gray-700">Prioridad</label>
            <select name="prioridad" id="prioridad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todas las prioridades</option>
                @foreach($prioridades as $value => $label)
                    <option value="{{ $value }}" {{ request('prioridad') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Filtrar
            </button>
        </div>
    </form>
</div> 