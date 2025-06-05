<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_id', // Obligatorio
        'user_id',      // Obligatorio
        'tipo_representante',
        'rol_en_comite',
        'activo'
    ];

    // Relaciones
    public function committee() {
        return $this->belongsTo(Committee::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function votes() { return $this->hasMany(Vote::class); }
    public function total_votes() { return $this->votes()->count(); }
}