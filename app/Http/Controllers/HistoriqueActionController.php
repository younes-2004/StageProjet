<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueAction;
use App\Models\User;
use App\Models\Service;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoriqueActionController extends Controller
{
    /**
     * Constructeur avec middleware pour autoriser uniquement le greffier en chef
     */
    public function __construct()
    {
        $this->middleware('role:greffier_en_chef');
    }
    
    /**
     * Affiche la page d'historique des actions avec filtrage avancé
     */
    public function index(Request $request)
    {
        // Construction de la requête de base
        $query = HistoriqueAction::with(['dossier', 'utilisateur', 'service']);
        
        // Filtrage par action
        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }
        
        // Filtrage par utilisateur
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtrage par service
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        
        // Filtrage par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }
        
        // Filtrage par date de début
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_action', '>=', $request->date_debut);
        }
        
        // Filtrage par date de fin
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_action', '<=', $request->date_fin);
        }
        
        // Filtrage par mot-clé dans la description
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('description', 'like', "%{$request->keyword}%");
        }
        
        // Tri des résultats
        $sortField = $request->get('sort_by', 'date_action');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['date_action', 'action', 'description'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('date_action', 'desc');
        }
        
        // Récupération des actions avec pagination
        $historiques = $query->paginate(15)->appends($request->all());
        
        // Récupération des listes pour les filtres
        $users = User::all();
        $services = Service::all();
        $dossiers = Dossier::all();
        
        // Liste des types d'actions disponibles
        $actions = HistoriqueAction::select('action')->distinct()->pluck('action');
        
        // Statistiques pour le tableau de bord
        $actionsParType = HistoriqueAction::select('action', DB::raw('count(*) as total'))
            ->groupBy('action')
            ->get();
            
        $actionsParJour = HistoriqueAction::select(
                DB::raw('DATE(date_action) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(15)
            ->get();
            
        $actionsParService = HistoriqueAction::select('service_id', DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->with('service')
            ->get();
            
        $utilisateursActifs = HistoriqueAction::select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->with('utilisateur')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
            
        return view('historique.index', compact(
            'historiques',
            'users',
            'services',
            'dossiers',
            'actions',
            'actionsParType',
            'actionsParJour',
            'actionsParService',
            'utilisateursActifs'
        ));
    }
    
    /**
     * Exporte l'historique des actions au format CSV
     */
    public function export(Request $request)
    {
        // Construction de la requête avec les mêmes filtres que pour l'affichage
        $query = HistoriqueAction::with(['dossier', 'utilisateur', 'service']);
        
        // Appliquer les mêmes filtres que pour l'affichage
        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }
        
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }
        
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_action', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_action', '<=', $request->date_fin);
        }
        
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('description', 'like', "%{$request->keyword}%");
        }
        
        $sortField = $request->get('sort_by', 'date_action');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['date_action', 'action', 'description'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('date_action', 'desc');
        }
        
        $historiques = $query->get();
        
        // Préparation du fichier CSV
        $filename = 'historique_actions_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($historiques) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Dossier',
                'Utilisateur',
                'Service',
                'Action',
                'Description',
                'Date',
            ], ';');
            
            // Données
            foreach ($historiques as $historique) {
                fputcsv($file, [
                    $historique->id,
                    $historique->dossier->numero_dossier_judiciaire ?? 'N/A',
                    $historique->utilisateur->name ?? 'N/A',
                    $historique->service->nom ?? 'N/A',
                    $historique->action,
                    $historique->description,
                    $historique->date_action ? $historique->date_action->format('d/m/Y H:i') : 'N/A',
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Affiche les détails d'une action spécifique
     */
    public function show($id)
    {
        $historique = HistoriqueAction::with(['dossier', 'utilisateur', 'service'])->findOrFail($id);
        
        return view('historique.show', compact('historique'));
    }
}