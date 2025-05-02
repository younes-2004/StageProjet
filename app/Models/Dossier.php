<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $table = 'dossiers';

    protected $fillable = [
        'numero_dossier_judiciaire', // Corrigé pour éviter les espaces
        'titre',
        'contenu',
        'date_creation',
        'statut',
        'createur_id',
        'service_id',
        'genre', // Ajout du champ genre
        
    ];

    /**
     * Relation avec le créateur (Utilisateur).
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }

    /**
     * Relation avec le service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function historiqueActions()
    {
        return $this->hasMany(HistoriqueAction::class, 'dossier_id');
    }
}