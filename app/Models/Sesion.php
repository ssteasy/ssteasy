<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Sesion extends Model
{
    use HasFactory;

    protected $table = 'capacitacion_sesiones';

    protected $fillable = [
        'capacitacion_id',
        'created_by',
        'titulo',
        'contenido_html',
        'video_url',
        'orden',
        'prerequisite_id',
        'preguntas',
        'miniatura',
    ];

    protected $casts = [
        'preguntas' => 'array',
    ];

    /** ←———— Aquí incluimos completado_at ———— */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'sesion_user')
            ->withPivot([
                'respuesta_json',
                'score',
                'aprobado',
                'completado_at',   // ← FALTABA
            ])
            ->withTimestamps();     // añade pivot_created_at / pivot_updated_at
    }









    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }

    public function prerequisite()
    {
        return $this->belongsTo(self::class, 'prerequisite_id');
    }
    //public function preguntas()
    //{
    //    return $this->hasMany(\App\Models\Pregunta::class);
    //}

}