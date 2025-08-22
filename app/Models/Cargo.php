<?php
// app/Models/Cargo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cargo extends Model
{
    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'activo',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Pivot EppCargo: acceso a los datos extra
     */
    public function eppCargos(): HasMany
    {
        return $this->hasMany(EppCargo::class);
    }

    /**
     * Relación a EPPs a través del pivot,
     * con todos los campos de periodicidad/reposición.
     */
    public function epps(): BelongsToMany
    {
        return $this->belongsToMany(Epp::class, 'epp_cargo')
                    ->using(EppCargo::class)
                    ->withPivot([
                        'periodicidad_valor',
                        'periodicidad_unidad',
                        'cantidad',
                        'reposicion_valor',
                        'reposicion_unidad',
                    ])
                    ->withTimestamps();
    }
}
