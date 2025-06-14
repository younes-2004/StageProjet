<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class DossierSearchController extends Controller
{
    private function getEnumValues($table, $column)
{
    $type = \DB::select(\DB::raw("SHOW COLUMNS FROM {$table} WHERE Field = '{$column}'"))[0]->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $enum = [];
    foreach (explode(',', $matches[1]) as $value) {
        $v = trim($value, "'");
        $enum[] = $v;
    }
    return $enum;
}
    public function index(Request $request)
    {
        $services = Service::all();
        $users = User::all();
        
        $query = Dossier::query()
            ->with(['createur', 'service']);
            
        // Recherche par mot-clé
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('titre', 'like', "%{$keyword}%")
                  ->orWhere('contenu', 'like', "%{$keyword}%")
                  ->orWhere('numero_dossier_judiciaire', 'like', "%{$keyword}%");
            });
        }
        
        // Filtrage par statut
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrage par service
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        
        // Filtrage par créateur
        if ($request->has('createur_id') && !empty($request->createur_id)) {
            $query->where('createur_id', $request->createur_id);
        }
        
       if ($request->has('genre') && !empty($request->genre)) {
    $query->where('genre', 'like', "%{$request->genre}%");
}
if ($request->has('observation') && !empty($request->observation)) {
    $query->where('observation', 'like', '%' . $request->observation . '%');
}

// Filtrage par numéro dans le genre
if ($request->has('genre_numero') && !empty($request->genre_numero)) {
    $numero = $request->genre_numero;
    $query->where('genre', 'like', '%[' . $numero . ']%');
}
        
        // Filtrage par date
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_creation', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_creation', '<=', $request->date_fin);
        }
        
        // Tri
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['titre', 'numero_dossier_judiciaire', 'statut', 'created_at', 'date_creation'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $dossiers = $query->paginate(15)->appends($request->all());
        
        // Liste des statuts disponibles
        $statuts = Dossier::select('statut')->distinct()->pluck('statut');
        
        // Liste des genres disponibles
       $genres = $this->getEnumValues('dossiers', 'genre');
return view('dossiers.search', compact('dossiers', 'services', 'users', 'statuts', 'genres'));
    }
}