<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportar Nueva Incidencia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('incidencia.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="tipo" :value="__('Tipo de Incidencia')" />
                            <x-text-input id="tipo" name="tipo" type="text" class="mt-1 block w-full" :value="old('tipo')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('tipo')" />
                        </div>

                        <div>
                            <x-input-label for="ubicacion" :value="__('Ubicación')" />
                            <x-text-input id="ubicacion" name="ubicacion" type="text" class="mt-1 block w-full" :value="old('ubicacion')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('ubicacion')" />
                        </div>

                        <div>
                            <x-input-label for="descripcion" :value="__('Descripción')" />
                            <textarea id="descripcion" name="descripcion" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required>{{ old('descripcion') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
                        </div>

                        <div>
                            <x-input-label for="prioridad" :value="__('Prioridad')" />
                            <select id="prioridad" name="prioridad" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                                <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                                <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="critica" {{ old('prioridad') == 'critica' ? 'selected' : '' }}>Crítica</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('prioridad')" />
                        </div>

                        <div>
                            <x-input-label for="infraestructura_id" :value="__('Infraestructura Afectada')" />
                            <select id="infraestructura_id" name="infraestructura_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione una infraestructura</option>
                                @foreach($infraestructuras as $infraestructura)
                                <option value="{{ $infraestructura->id }}" {{ old('infraestructura_id') == $infraestructura->id ? 'selected' : '' }}>
                                    {{ $infraestructura->tipo }} - {{ $infraestructura->ubicacion }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('infraestructura_id')" />
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div>
                            <x-input-label for="tecnico_id" :value="__('Asignar Técnico')" />
                            <select id="tecnico_id" name="tecnico_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Sin asignar</option>
                                @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}" {{ old('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                    {{ $tecnico->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('tecnico_id')" />
                        </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Crear Incidencia') }}</x-primary-button>
                            <a href="{{ route('incidencia.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
