<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanActividad extends Model
{
    use HasFactory;

    protected $table = 'plan_actividades';

    protected $fillable = [
        'plan_trabajo_anual_id',
        'actividad','responsable','alcance',
        'criterio','observacion','frecuencia',
        'mes_ene','mes_feb','mes_mar','mes_abr','mes_may','mes_jun',
        'mes_jul','mes_ago','mes_sep','mes_oct','mes_nov','mes_dic',
    ];

    public function plan()
    {
        return $this->belongsTo(PlanTrabajoAnual::class, 'plan_trabajo_anual_id');
    }
}
