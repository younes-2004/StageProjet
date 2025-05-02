<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueAction extends Model
{
    use HasFactory;

    protected $table = 'historique_actions';

    protected $fillable = [
        'dossier_id',
        'user_id',
        'service_id',
        'action',
        'description',
        'date_action',
    ];

    /**
     * Relation avec le dossier.
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    /**
     * Relation avec l'utilisateur.
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}