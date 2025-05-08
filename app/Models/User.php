<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Dossier;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $table = 'users'; // Assurez-vous que le modèle pointe vers la table `users`
    protected $fillable = [
        'name',
        'fname',
        'email',
        'password',
        'role',
        'service_id',
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation avec le modèle Service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    /**
 * Relation avec les dossiers créés par cet utilisateur.
 */
public function dossiersCreated()
{
    return $this->hasMany(Dossier::class, 'createur_id');
}
}
