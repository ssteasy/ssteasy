<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
};
use Illuminate\Support\Facades\Auth;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $empresaId;

    public function __construct(int|null $empresaId = null)
    {
        // Si es superadmin, $empresaId puede venir null (exporta todos).
        // Si es admin, le pasamos su propia empresa_id.
        $this->empresaId = $empresaId;
    }

    public function collection()
    {
        $query = User::with(['empresa', 'cargo', 'sede', 'roles']);

        if ($this->empresaId) {
            $query->where('empresa_id', $this->empresaId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Primer Nombre',
            'Segundo Nombre',
            'Primer Apellido',
            'Segundo Apellido',
            'Tipo de Documento',
            'Número de Documento',
            'Sexo',
            'Correo Electrónico',
            'Teléfono',
            'Dirección',
            'País',
            'Departamento',
            'Municipio',
            'Zona',
            'Empresa',
            'Cargo',
            'Sede',
            'Tipo de Contrato',
            'Fecha de Inicio',
            'Fecha de Finalización',
            'Modalidad',
            'Nivel de Riesgo',
            'EPS',
            'IPS',
            'ARL',
            'AFP',
            'Roles',
        ];
    }

    public function map($user): array
    {
        return [
            $user->primer_nombre,
            $user->segundo_nombre,
            $user->primer_apellido,
            $user->segundo_apellido,
            $user->tipo_documento,
            $user->numero_documento,
            $user->sexo,
            $user->email,
            $user->telefono,
            $user->direccion,
            $user->pais_dane,
            $user->departamento_dane,
            $user->municipio_dane,
            $user->zona,
            $user->empresa?->nombre,
            $user->cargo?->nombre,
            $user->sede?->nombre,
            $user->tipo_contrato,
            optional($user->fecha_inicio)?->format('Y-m-d'),
            optional($user->fecha_fin)?->format('Y-m-d'),
            $user->modalidad,
            $user->nivel_riesgo,
            $user->eps,
            $user->ips,
            $user->arl,
            $user->afp,
            $user->roles->pluck('name')->implode(', '),
        ];
    }
}
