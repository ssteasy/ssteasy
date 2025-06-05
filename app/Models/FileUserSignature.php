<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUserSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'sgsst_file_id',
        'user_id',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function file() {
        return $this->belongsTo(SgsstFile::class, 'sgsst_file_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}