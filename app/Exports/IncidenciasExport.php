<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class IncidenciasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $incidencias;

    public function __construct(Collection $incidencias)
    {
        $this->incidencias = $incidencias;
    }

    public function collection()
    {
        return $this->incidencias;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Infraestructura',
            'Ubicación',
            'Descripción',
            'Estado',
            'Prioridad',
            'Técnico Asignado',
            'Reportado por',
            'Fecha',
            'Última Actualización'
        ];
    }

    public function map($incidencia): array
    {
        return [
            $incidencia->id,
            $incidencia->infraestructura->tipo,
            $incidencia->infraestructura->ubicacion,
            $incidencia->descripcion,
            ucfirst($incidencia->estado),
            ucfirst($incidencia->prioridad),
            $incidencia->tecnico ? $incidencia->tecnico->name : 'No asignado',
            $incidencia->ciudadano->name,
            $incidencia->fecha->format('d/m/Y H:i'),
            $incidencia->updated_at->format('d/m/Y H:i')
        ];
    }
}
