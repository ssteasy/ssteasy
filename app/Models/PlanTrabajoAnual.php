<?php
// app/Models/PlanTrabajoAnual.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanTrabajoAnual extends Model
{
    use HasFactory;

    protected $table = 'plan_trabajo_anual';

    protected $fillable = [
        'empresa_id',
        'year',
        'roles_responsabilidades',
        'recursos',
        'objetivo',
        'alcance',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function actividades()
    {
        return $this->hasMany(PlanActividad::class);
    }
}
