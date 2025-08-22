<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EppCargo extends Pivot
{
    protected $table = 'epp_cargo';

    protected $fillable = [
        'epp_id',
        'cargo_id',
        'periodicidad_valor',
        'periodicidad_unidad',
        'cantidad',
        'reposicion_valor',
        'reposicion_unidad',
    ];

    public function epp(): BelongsTo
    {
        return $this->belongsTo(Epp::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}
