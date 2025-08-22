<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambioImpacto extends Model
{
    protected $fillable = [
        'gestion_cambio_id', 'peligro_riesgo', 'requisitos_legales',
        'sistema_gestion', 'procedimiento', 'otros',
    ];

    public function cambio()
    {
        return $this->belongsTo(GestionCambio::class, 'gestion_cambio_id');
    }
}
