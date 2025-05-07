<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Service;
use App\Models\User;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DossierDashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalDossiers = Dossier::count();
        $dossiersParStatut = Dossier::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();
        
        // Dossiers récents
        $dossiersRecents = Dossier::with(['createur', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Statistiques par service
        $dossiersParService = Service::withCount('dossiers')->get();
        
        // Statistiques des transferts
        $transfertsRecents = Transfert::with(['dossier', 'userSource', 'userDestination', 'serviceSource', 'serviceDestination'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Statistiques des utilisateurs les plus actifs
        $utilisateursActifs = User::withCount(['dossiersCreated' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('dossiers_created_count', 'desc')
            ->limit(5)
            ->get();
            dd('La méthode index est bien appelée');
            
        return view('dossiers.dashboard', compact(
            'totalDossiers', 
            'dossiersParStatut', 
            'dossiersRecents', 
            'dossiersParService', 
            'transfertsRecents',
            'utilisateursActifs'
        ));

    }
}