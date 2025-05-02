<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierValide extends Model
{
    use HasFactory;

    protected $table = 'dossiers_valides';

    protected $fillable = [
        'dossier_id',
        'user_id',
        'date_validation',
        'commentaire',
        'observations'
    ];

    protected $dates = [
        'date_validation'
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}