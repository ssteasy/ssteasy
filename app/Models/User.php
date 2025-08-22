<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        // acceso
        'email',
        'password',

        // relaciones
        'empresa_id',
        'cargo_id',
        'rol_personalizado_id',

        // datos básicos
        'profile_photo_path',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_documento',
        'numero_documento',
        'sexo',

        // contacto
        'telefono',
        'direccion',
        'pais_dane',
        'departamento_dane',
        'municipio_dane',
        'zona',

        // contrato
        'tipo_contrato',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'nivel_riesgo',
        'sede',
        'centro_trabajo',
        'sede_id',

        // cuentas
        'eps',
        'ips',
        'arl',
        'afp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Relaciones

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }



    public function getNameAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_dane', 'codigo');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_dane', 'codigo');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_dane', 'codigo');
    }
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cargo::class);
    }
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path
            ? \Storage::url($this->profile_photo_path)
            : null;
    }
    public function sede()
    {
        return $this->belongsTo(\App\Models\Sede::class);
    }
    public function responsableSstActivo()
    {
        return $this->hasOne(SgsstResponsable::class, 'user_id')
            ->whereNull('fecha_fin');
    }
    public function rolPersonalizado()
    {
        return $this->belongsTo(Rol::class, 'rol_personalizado_id');
    }
    public function capacitaciones()
    {
        return $this->belongsToMany(Capacitacion::class, 'capacitacion_user')
            ->withPivot('estado')   // Solo 'estado' (y si quieres, ->withTimestamps())
            ->withTimestamps();
    }
    public function certificados()
    {
        return $this->hasMany(Certificado::class);
    }
    // app/Models/User.php
    public function getFullNameAttribute(): string
    {
        $nombre = trim("{$this->primer_nombre} {$this->primer_apellido}");

        return $nombre !== '' ? $nombre : "Usuario {$this->id}";
    }
    public function encuestas(): BelongsToMany
    {
        return $this
            ->belongsToMany(Encuesta::class, 'encuesta_user')
            ->withPivot(['respuestas', 'respondida', 'respondido_at'])
            ->withTimestamps();
    }
    
}
