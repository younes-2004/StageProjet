<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_id',
        'user_id',
        'date_reception',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_reception' => 'datetime',
    ];

    /**
     * Relation avec le dossier.
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    /**
     * Relation avec l'utilisateur destinataire.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}