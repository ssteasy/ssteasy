<?php
namespace App\Enums;

enum CambioEstado: string
{
    case PLANIFICADO   = 'planificado';
    case EN_EJECUCION  = 'en_ejecucion';
    case EJECUTADO     = 'ejecutado';

    public static function labels(): array
    {
        return [
            self::PLANIFICADO->value   => 'Planificado',
            self::EN_EJECUCION->value  => 'En ejecución',
            self::EJECUTADO->value     => 'Ejecutado',
        ];
    }
}
