<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Capacitacion;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ProgresoColaboradoresWidget extends Widget
{
    protected static string $view = 'filament.widgets.progreso-colaboradores-widget';

    /**
     * Solo los admins o superadmins podrán ver este widget.
     */
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    /**
     * Cantidad total de colaboradores en la empresa del admin.
     * Filtramos por la relación 'roles' para encontrar a los que tienen rol 'colaborador'.
     */
    public function getCantidadColaboradoresProperty(): int
    {
        $empresaId = Auth::user()->empresa_id;

        return User::query()
            ->where('empresa_id', $empresaId)
            ->whereHas('roles', fn($q) => $q->where('name', 'colaborador'))
            ->count();
    }

    /**
     * Devuelve una colección de colaboradores (rol 'colaborador') de esta empresa
     * con su porcentaje de avance (promedio sobre todos sus cursos).
     */
    protected function getColaboradoresConAvance(): Collection
    {
        $empresaId = Auth::user()->empresa_id;

        // 1) Obtiene todos los usuarios que son 'colaborador' en esta empresa
        $colabs = User::query()
            ->where('empresa_id', $empresaId)
            ->whereHas('roles', fn($q) => $q->where('name', 'colaborador'))
            ->get();

        // 2) Para cada colaborador, calcula su avance promedio
        return $colabs->map(function (User $u) {
            // Ahora sí existe el método capacitaciones()
            $cursosInscritos = $u->capacitaciones()->get();

            if ($cursosInscritos->isEmpty()) {
                return [
                    'id'         => $u->id,
                    'nombre'     => "{$u->primer_nombre} {$u->primer_apellido}",
                    'porcentaje' => 0,
                    'cursos'     => 0,
                ];
            }

            // Calcula el porcentaje para cada curso individual
            $avances = $cursosInscritos->map(function (Capacitacion $curso) use ($u) {
                $totalSesiones = $curso->sesiones()->count();
                if ($totalSesiones === 0) {
                    return 0;
                }

                $aprobadas = $curso->sesiones()
                    ->whereHas(
                        'usuarios',
                        fn($q) =>
                        $q->where('user_id', $u->id)
                            ->where('aprobado', true)
                    )
                    ->count();

                return intval(($aprobadas / $totalSesiones) * 100);
            });

            // Promedio de todos los porcentajes de sus cursos
            $promedio = intval($avances->avg());

            return [
                'id'         => $u->id,
                'nombre'     => "{$u->primer_nombre} {$u->primer_apellido}",
                'porcentaje' => $promedio,
                'cursos'     => $cursosInscritos->count(),
            ];
        });
    }


    /**
     * Progreso promedio de todos los colaboradores en la empresa.
     */
    public function getPromedioEmpresaProperty(): int
    {
        $colabsConAvance = $this->getColaboradoresConAvance();

        if ($colabsConAvance->isEmpty()) {
            return 0;
        }

        return intval($colabsConAvance->avg('porcentaje'));
    }

    /**
     * Los 3 colaboradores con menor avance para mostrar en detalle.
     */
    public function getTresConMenorAvanceProperty(): Collection
    {
        return $this->getColaboradoresConAvance()
            ->sortBy('porcentaje')
            ->take(3)
            ->values();
    }

    /**
     * Cantidad total de cursos activos en la empresa.
     */
    public function getCantidadCursosActivosProperty(): int
    {
        $empresaId = Auth::user()->empresa_id;

        return Capacitacion::query()
            ->where('empresa_id', $empresaId)
            ->where('activa', true)
            ->count();
    }
}
