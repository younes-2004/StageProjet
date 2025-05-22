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
        'type_contenu',
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
    // app/Models/Dossier.php - ajoutez ces méthodes

/**
 * Obtient le détenteur actuel du dossier
 */
public function detenteurActuel()
{
    // Cherche le transfert le plus récent avec réception validée
    $dernierTransfert = Transfert::where('dossier_id', $this->id)
        ->whereNotNull('date_reception') // Transfert validé
        ->orderBy('date_reception', 'desc')
        ->first();
    
    if ($dernierTransfert) {
        return User::find($dernierTransfert->user_destination_id);
    }
    
    // Si aucun transfert validé, le créateur est toujours le détenteur
    return $this->createur;
}

/**
 * Obtient le service actuel qui possède le dossier
 */
public function serviceActuel()
{
    // Cherche le transfert le plus récent avec réception validée
    $dernierTransfert = Transfert::where('dossier_id', $this->id)
        ->whereNotNull('date_reception') // Transfert validé
        ->orderBy('date_reception', 'desc')
        ->first();
    
    if ($dernierTransfert) {
        return Service::find($dernierTransfert->service_destination_id);
    }
    
    // Si aucun transfert validé, le service du créateur est toujours le détenteur
    return $this->service;
}

/**
 * Obtient l'historique complet du dossier
 */
public function historiqueComplet()
{
    return $this->historiqueActions()
        ->with(['user', 'service'])
        ->orderBy('date_action', 'desc')
        ->get();
}

/**
 * Obtient le temps de traitement total du dossier (en jours)
 */
public function tempsTraitement()
{
    $dateCreation = $this->date_creation ?? $this->created_at;
    
    if ($this->statut === 'Archivé') {
        // Si le dossier est archivé, on calcule entre création et archivage
        $derniereAction = $this->historiqueActions()
            ->where('action', 'archivage')
            ->orderBy('date_action', 'desc')
            ->first();
            
        if ($derniereAction) {
            $dateArchivage = $derniereAction->date_action;
            return $dateCreation->diffInDays($dateArchivage);
        }
    }
    
    // Sinon on calcule jusqu'à maintenant
    return $dateCreation->diffInDays(now());
}
// Dans app/Models/Dossier.php
protected $casts = [
    'date_creation' => 'datetime',
    // autres champs...
];

// Ou
protected $dates = [
    'date_creation',
    'created_at',
    'updated_at',
    // autres dates...
];

}