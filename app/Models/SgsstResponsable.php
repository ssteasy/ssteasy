<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SgsstResponsable extends Model
{
    protected $fillable = [
        'user_id',
        'empresa_id',  // <-- Aquí
        'fecha_inicio',
        'fecha_fin',
        'funciones',
        'documentos',
        'activo',
    ];

    protected $casts = [
    'fecha_inicio' => 'date',
    'fecha_fin'    => 'date',
    'documentos'   => 'array',
    'activo'       => 'boolean',
    ];
    
    protected static function booted()
    {
        static::creating(function ($responsable) {
            if (!auth()->user()->hasRole('superadmin')) {
                $responsable->empresa_id = auth()->user()->empresa_id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
