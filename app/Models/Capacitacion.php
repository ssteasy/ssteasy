<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class Capacitacion extends Model
{
    /* ---------- Config básica ---------- */
    protected $table = 'capacitaciones';

    protected $fillable = [
        'empresa_id',
        'created_by',
        'codigo_capacitacion',
        'nombre_capacitacion',
        'miniatura',
        'objetivo',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'tipo_asignacion',          // ← manual | abierta | obligatoria
        'categoria' 
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_inicio' => 'date',   // acepta null
        'fecha_fin' => 'date',   // acepta null

    ];

    /* ---------- Relaciones -------------- */

    /** Clases / sesiones del curso */
    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class)->orderBy('orden');
    }

    /** Usuario que creó la capacitación */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Pivot con colaboradores asignados */
    public function participantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'capacitacion_user')
            ->withPivot([
                'estado',         // <-- este es el campo que quieres mostrar
                'fecha_finalizado', // si lo necesitas
            ])
            ->withTimestamps();
    }

    /* ---------- Eventos de modelo -------- */

    protected static function booted(): void
    {
        /** Si la capacitación es obligatoria, se asigna a todos los colaboradores de la empresa */
        static::created(function (self $c) {
            if ($c->tipo_asignacion === 'obligatoria') {
                $userIds = User::where('empresa_id', $c->empresa_id)->pluck('id');

                $c->participantes()->sync(
                    $userIds->mapWithKeys(fn($id) => [$id => ['estado' => 'pendiente']])->toArray()
                );
            }
        });
    }
    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class);
    }
    public function getCategoriasListAttribute()
    {
        return collect($this->categorias ?? []);
    }
}
