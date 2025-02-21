<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Infraestructura</th>
            <th>Estado</th>
            <th>Prioridad</th>
            <th>Técnico</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($incidents as $incident)
        <tr>
            <td>{{ $incident->id }}</td>
            <td>{{ $incident->title }}</td>
            <td>{{ $incident->infrastructure->name }}</td>
            <td>
                <span class="badge bg-{{ $incident->status->color }}">
                    {{ $incident->status->name }}
                </span>
            </td>
            <td>
                <span class="badge bg-{{ $incident->priority_color }}">
                    {{ $incident->priority }}
                </span>
            </td>
            <td>{{ $incident->assignedTo->name ?? 'Sin asignar' }}</td>
            <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('incidents.show', $incident) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>