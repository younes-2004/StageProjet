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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // app/Models/HistoriqueAction.php

protected $casts = [
    'date_action' => 'datetime',
    // autres champs...
];

// OU

protected $dates = [
    'date_action',
    'created_at',
    'updated_at',
    // autres dates...
];
}