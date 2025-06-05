<?php

namespace App\Filament\Widgets;

use App\Models\Capacitacion;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class MisCursosPendientesWidget extends Widget
{
    protected static string $view = 'filament.widgets.mis-cursos-pendientes-widget';

    /**
     * Solo usuarios con rol "colaborador" pueden ver el widget.
     */
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasRole('colaborador');
    }

    /**
     * Calcula cuántos cursos en progreso tiene el colaborador.
     */
    public function getCantidadPendientesProperty(): int
    {
        $user = Auth::user();

        return Capacitacion::query()
            ->where('empresa_id', $user->empresa_id)
            ->whereHas(
                'participantes',
                fn ($q) => $q
                    ->where('users.id', $user->id)
                    ->where('capacitacion_user.estado', 'en_progreso')
            )
            ->count();
    }

    /**
     * Devuelve la colección de cursos en progreso con su avance.
     */
    protected function getCursosConAvance(): Collection
    {
        $user = Auth::user();

        return Capacitacion::query()
            ->where('empresa_id', $user->empresa_id)
            ->whereHas(
                'participantes',
                fn ($q) => $q
                    ->where('users.id', $user->id)
                    ->where('capacitacion_user.estado', 'en_progreso')
            )
            ->get()
            ->map(function (Capacitacion $curso) use ($user) {
                $totalSesiones = $curso->sesiones()->count();
                $aprobadas = $curso->sesiones()
                    ->whereHas('usuarios', fn ($q) =>
                        $q->where('user_id', $user->id)
                          ->where('aprobado', true)
                    )
                    ->count();

                $porcentaje = $totalSesiones
                    ? intval(($aprobadas / $totalSesiones) * 100)
                    : 0;

                return [
                    'id'            => $curso->id,
                    'nombre'        => $curso->nombre_capacitacion,
                    'progreso'      => $porcentaje,
                    'totalSesiones' => $totalSesiones,
                    'aprobadas'     => $aprobadas,
                ];
            });
    }

    /**
     * Calcula el progreso promedio entre todos los cursos en progreso.
     */
    public function getPromedioGlobalProperty(): int
    {
        $cursosConAvance = $this->getCursosConAvance();

        return $cursosConAvance->isNotEmpty()
            ? intval($cursosConAvance->avg('progreso'))
            : 0;
    }

    /**
     * Devuelve los tres cursos con menor avance.
     */
    public function getTresConMenorAvanceProperty(): Collection
    {
        return $this->getCursosConAvance()
            ->sortBy('progreso')
            ->take(3)
            ->values();
    }
}
