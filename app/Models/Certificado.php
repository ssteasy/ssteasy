<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $table = 'certificados';

    protected $fillable = [
        'capacitacion_id',
        'user_id',
        'codigo_unico',
        'file_path',
    ];

    /**
     * El curso asociado.
     */
    public function capacitacion(): BelongsTo
    {
        return $this->belongsTo(Capacitacion::class);
    }

    /**
     * El colaborador asociado.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
