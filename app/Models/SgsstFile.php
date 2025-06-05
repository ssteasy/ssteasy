<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SgsstFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'require_signature',
        'signature_deadline',
    ];

    protected $casts = [
        'signature_deadline' => 'date',
        'require_signature'  => 'boolean',
    ];

    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }

    public function uploader() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function signatories() {
        return $this
            ->hasMany(FileUserSignature::class, 'sgsst_file_id');
    }

    // Usuarios que deben firmar
    public function assignedUsers() {
        return $this
            ->belongsToMany(User::class, 'file_user_signatures', 'sgsst_file_id', 'user_id')
            ->withPivot('signed_at')
            ->withTimestamps();
    }
}