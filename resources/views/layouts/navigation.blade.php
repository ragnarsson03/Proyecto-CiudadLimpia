<nav class="navbar navbar-expand-lg navbar-dark bg-gradient" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fas fa-city me-2"></i>
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    
                    @can('view-infrastructures')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('infrastructures.index') }}">
                            <i class="fas fa-building me-1"></i> Infraestructuras
                        </a>
                    </li>
                    @endcan

                    @can('view-incidents')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.index') }}">
                            <i class="fas fa-exclamation-circle me-1"></i> Incidencias
                        </a>
                    </li>
                    @endcan

                    @can('view-map')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.map') }}">
                            <i class="fas fa-map-marked-alt me-1"></i> Mapa
                        </a>
                    </li>
                    @endcan

                    @can('view-reports')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-bar me-1"></i> Reportes
                        </a>
                    </li>
                    @endcan
                @endauth
            </ul>

            @auth
            <div class="navbar-nav">
                <!-- Botón Reportar Incidencia -->
                <a href="{{ route('incidents.create') }}" class="btn btn-warning me-3">
                    <i class="fas fa-plus-circle me-1"></i> Reportar Incidencia
                </a>

                <!-- Notificaciones -->
                <div class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-count">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" id="notificationsList">
                        <!-- Las notificaciones se cargarán aquí dinámicamente -->
                    </div>
                </div>

                <!-- Usuario -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog me-1"></i> Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>
