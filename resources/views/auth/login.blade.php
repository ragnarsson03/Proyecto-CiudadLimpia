<x-guest-layout>
    <div class="text-center mb-8">
        <!-- Logo Container -->
        <div class="city-logo-container mb-4">
            <div class="city-logo">
                <div class="buildings">
                    <div class="building building-1"></div>
                    <div class="building building-2"></div>
                    <div class="building building-3"></div>
                    <div class="building building-4"></div>
                </div>
                <div class="leaf"></div>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Ciudad Limpia</h1>
        <p class="text-gray-600 mt-2">Gestión de Infraestructura Urbana</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="relative">
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" 
                         class="block mt-1 w-full pl-10" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autofocus 
                         autocomplete="username" />
            <span class="absolute left-3 top-9">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
            </span>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" 
                         class="block mt-1 w-full pl-10" 
                         type="password"
                         name="password" 
                         required 
                         autocomplete="current-password" />
            <span class="absolute left-3 top-9">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </span>
            <button type="button" 
                    class="absolute right-3 top-9 text-gray-400 hover:text-gray-600"
                    onclick="togglePassword()">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eye-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-800 font-medium" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700">
            {{ __('Iniciar Sesión') }}
        </x-primary-button>

        <p class="text-center text-sm text-gray-600 mt-4">
            {{ __('¿No tienes cuenta?') }}
            <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">
                {{ __('Regístrate aquí') }}
            </a>
        </p>
    </form>
</x-guest-layout>
