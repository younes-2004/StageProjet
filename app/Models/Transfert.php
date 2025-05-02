<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;

    protected $table = 'transferts';

    protected $fillable = [
        'dossier_id',
        'service_source_id',
        'service_destination_id',
        'user_source_id',
        'user_destination_id',
        'date_envoi',
        'date_reception',
        'statut',
        'commentaire',
    ];

    /**
     * Relation avec le dossier.
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    /**
     * Relation avec le service source.
     */
    public function serviceSource()
{
    return $this->belongsTo(Service::class, 'service_source_id');
}

    // Duplicate method removed

    /**
     * Relation avec l'utilisateur source.
     */
    public function userSource()
    {
        return $this->belongsTo(Utilisateur::class, 'user_source_id');
    }

    /**
     * Relation avec l'utilisateur destination.
     */
    public function serviceDestination()
    {
        return $this->belongsTo(Service::class, 'service_destination_id');
    }
    public function userDestination()
{
    return $this->belongsTo(User::class, 'user_destination_id');
}
}