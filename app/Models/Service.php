<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Relation avec les utilisateurs.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'service_id'); // Assurez-vous que la colonne `service_id` existe dans la table `users`
    }

    /**
     * Relation avec les dossiers.
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'service_id');
    }
}