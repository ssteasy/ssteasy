<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioActividad extends Model
{
    // Forzamos la tabla correcta si no lo has hecho ya
    protected $table = 'cambio_actividades';

    protected $fillable = [
        'gestion_cambio_id',
        'actividad',
        'responsable_id',
        'informar_a_id',
        'fecha_ejecucion',
        'fecha_seguimiento',
        // no incluimos 'realizado_por' aquí para evitar asignación masiva accidental
    ];

    protected $casts = [
        'fecha_ejecucion'   => 'date',
        'fecha_seguimiento' => 'date',
    ];

    // Relaciones...
    public function cambio()      { return $this->belongsTo(GestionCambio::class, 'gestion_cambio_id'); }
    public function responsable() { return $this->belongsTo(User::class, 'responsable_id'); }
    public function informarA()   { return $this->belongsTo(User::class, 'informar_a_id'); }
    public function autor()       { return $this->belongsTo(User::class, 'realizado_por'); }

    // ← Aquí el magic hook ↓↓↓
    protected static function booted(): void
    {
        static::creating(function (CambioActividad $actividad) {
            // Si no viene explícito, lo asignamos al usuario actual
            if (! $actividad->realizado_por) {
                $actividad->realizado_por = auth()->id();
            }
        });
    }
}
