@component('mail::message')
# Nueva Incidencia Reportada

Se ha reportado una nueva incidencia en el sistema:

**Tipo:** {{ $incidencia->tipo }}  
**Ubicación:** {{ $incidencia->ubicacion }}  
**Prioridad:** {{ ucfirst($incidencia->prioridad) }}  
**Fecha:** {{ $incidencia->fecha->format('d/m/Y H:i') }}

## Detalles de la Infraestructura
**Tipo:** {{ $infraestructura->tipo }}  
**Estado:** {{ ucfirst($infraestructura->estado) }}

## Descripción
{{ $incidencia->descripcion }}

## Reportado por
{{ $ciudadano->name }}

Para ver más detalles, ingrese al sistema.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
