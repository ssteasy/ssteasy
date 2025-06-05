<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_id',
        'committee_member_id',
        'voter_id',
    ];

    /* Relaciones */
    public function committee()        { return $this->belongsTo(Committee::class); }
    public function candidate()        { return $this->belongsTo(CommitteeMember::class, 'committee_member_id'); }
    public function voter()            { return $this->belongsTo(User::class, 'voter_id'); }
}
