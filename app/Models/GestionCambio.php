<?php
namespace App\Models;

use App\Enums\CambioEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GestionCambio extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'fecha', 'descripcion_cambio', 'analisis_riesgo',
        'requisitos_legales', 'requerimientos_sst', 'analisis_impacto_sst',
        'estado', 'empresa_id', 'creado_por',
    ];

    protected $casts = [
        'fecha'  => 'date',
        'estado' => CambioEstado::class,
    ];

    /* Relaciones */
    public function impactos()     { return $this->hasMany(CambioImpacto::class); }
    public function actividades()  { return $this->hasMany(CambioActividad::class); }
    public function empresa()      { return $this->belongsTo(Empresa::class); }
    public function creador()      { return $this->belongsTo(User::class, 'creado_por'); }

    /* Spatie-Activitylog */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()               // Registra cada atributo
            ->logOnlyDirty()         // Solo cambios
            ->useLogName('gestion_cambio');
    }
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty  = true;
}
