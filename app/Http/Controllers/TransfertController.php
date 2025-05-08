<?php

namespace App\Http\Controllers;

use App\Models\Transfert;
use App\Models\User;
use App\Models\Service;
use App\Models\Dossier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransfertController extends Controller
{
    /**
     * Constructeur avec middleware pour autoriser uniquement le greffier en chef
     */
    public function __construct()
    {
        $this->middleware('role:greffier_en_chef');
    }
    
    /**
     * Affiche la liste des transferts avec filtrage avancé
     */
    public function index(Request $request)
    {
        // Construction de la requête de base
        $query = Transfert::with([
            'dossier', 
            'userSource', 
            'userDestination', 
            'serviceSource', 
            'serviceDestination'
        ]);
        
        // Filtrage par statut
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrage par utilisateur source
        if ($request->has('user_source_id') && !empty($request->user_source_id)) {
            $query->where('user_source_id', $request->user_source_id);
        }
        
        // Filtrage par utilisateur destination
        if ($request->has('user_destination_id') && !empty($request->user_destination_id)) {
            $query->where('user_destination_id', $request->user_destination_id);
        }
        
        // Filtrage par service source
        if ($request->has('service_source_id') && !empty($request->service_source_id)) {
            $query->where('service_source_id', $request->service_source_id);
        }
        
        // Filtrage par service destination
        if ($request->has('service_destination_id') && !empty($request->service_destination_id)) {
            $query->where('service_destination_id', $request->service_destination_id);
        }
        
        // Filtrage par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }
        
        // Filtrage par période (date d'envoi)
        if ($request->has('date_envoi_debut') && !empty($request->date_envoi_debut)) {
            $query->whereDate('date_envoi', '>=', $request->date_envoi_debut);
        }
        
        if ($request->has('date_envoi_fin') && !empty($request->date_envoi_fin)) {
            $query->whereDate('date_envoi', '<=', $request->date_envoi_fin);
        }
        
        // Filtrage par période (date de réception)
        if ($request->has('date_reception_debut') && !empty($request->date_reception_debut)) {
            $query->whereDate('date_reception', '>=', $request->date_reception_debut);
        }
        
        if ($request->has('date_reception_fin') && !empty($request->date_reception_fin)) {
            $query->whereDate('date_reception', '<=', $request->date_reception_fin);
        }
        
        // Filtrage par état de validation (validé ou non)
        if ($request->has('valide') && $request->valide !== null) {
            if ($request->valide == '1') {
                $query->whereNotNull('date_reception');
            } else {
                $query->whereNull('date_reception');
            }
        }
        
        // Recherche par mot-clé dans les commentaires
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('commentaire', 'like', "%{$request->keyword}%");
        }
        
        // Tri des résultats
        $sortField = $request->get('sort_by', 'date_envoi');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['date_envoi', 'date_reception', 'statut'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('date_envoi', 'desc');
        }
        
        // Récupération des transferts avec pagination
        $transferts = $query->paginate(15)->appends($request->all());
        
        // Récupération des listes pour les filtres
        $users = User::all();
        $services = Service::all();
        $dossiers = Dossier::all();
        
        // Liste des statuts disponibles
        $statuts = Transfert::select('statut')->distinct()->pluck('statut');
        
        // Statistiques pour le tableau de bord
        $transfertsParStatut = Transfert::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();
            
        $transfertsParJour = Transfert::select(
                DB::raw('DATE(date_envoi) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(15)
            ->get();
            
        $transfertsParService = Transfert::select('service_destination_id', DB::raw('count(*) as total'))
            ->groupBy('service_destination_id')
            ->with('serviceDestination')
            ->get();
            
        $tempsValidationMoyen = Transfert::selectRaw('AVG(TIMESTAMPDIFF(HOUR, date_envoi, date_reception)) as avg_validation_hours')
            ->whereNotNull('date_reception')
            ->first();
            
        $transfertsNonValides = Transfert::whereNull('date_reception')
            ->where('statut', '!=', 'réaffectation')
            ->count();
            
        return view('transferts.index', compact(
            'transferts',
            'users',
            'services',
            'dossiers',
            'statuts',
            'transfertsParStatut',
            'transfertsParJour',
            'transfertsParService',
            'tempsValidationMoyen',
            'transfertsNonValides'
        ));
    }
    
    /**
     * Exporte les transferts au format CSV
     */
    public function export(Request $request)
    {
        // Construction de la requête avec les mêmes filtres que pour l'affichage
        $query = Transfert::with([
            'dossier', 
            'userSource', 
            'userDestination', 
            'serviceSource', 
            'serviceDestination'
        ]);
        
        // Appliquer les mêmes filtres que pour l'affichage
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('user_source_id') && !empty($request->user_source_id)) {
            $query->where('user_source_id', $request->user_source_id);
        }
        
        if ($request->has('user_destination_id') && !empty($request->user_destination_id)) {
            $query->where('user_destination_id', $request->user_destination_id);
        }
        
        if ($request->has('service_source_id') && !empty($request->service_source_id)) {
            $query->where('service_source_id', $request->service_source_id);
        }
        
        if ($request->has('service_destination_id') && !empty($request->service_destination_id)) {
            $query->where('service_destination_id', $request->service_destination_id);
        }
        
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }
        
        if ($request->has('date_envoi_debut') && !empty($request->date_envoi_debut)) {
            $query->whereDate('date_envoi', '>=', $request->date_envoi_debut);
        }
        
        if ($request->has('date_envoi_fin') && !empty($request->date_envoi_fin)) {
            $query->whereDate('date_envoi', '<=', $request->date_envoi_fin);
        }
        
        if ($request->has('date_reception_debut') && !empty($request->date_reception_debut)) {
            $query->whereDate('date_reception', '>=', $request->date_reception_debut);
        }
        
        if ($request->has('date_reception_fin') && !empty($request->date_reception_fin)) {
            $query->whereDate('date_reception', '<=', $request->date_reception_fin);
        }
        
        if ($request->has('valide') && $request->valide !== null) {
            if ($request->valide == '1') {
                $query->whereNotNull('date_reception');
            } else {
                $query->whereNull('date_reception');
            }
        }
        
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('commentaire', 'like', "%{$request->keyword}%");
        }
        
        $sortField = $request->get('sort_by', 'date_envoi');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['date_envoi', 'date_reception', 'statut'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('date_envoi', 'desc');
        }
        
        $transferts = $query->get();
        
        // Préparation du fichier CSV
        $filename = 'transferts_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transferts) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Dossier',
                'Service source',
                'Service destination',
                'Utilisateur source',
                'Utilisateur destination',
                'Date d\'envoi',
                'Date de réception',
                'Statut',
                'Commentaire',
                'Délai de validation (heures)',
            ], ';');
            
            // Données
            foreach ($transferts as $transfert) {
                // Calcul du délai de validation en heures
                $delaiValidation = null;
                if ($transfert->date_envoi && $transfert->date_reception) {
                    $delaiValidation = $transfert->date_envoi->diffInHours($transfert->date_reception);
                }
                
                fputcsv($file, [
                    $transfert->id,
                    $transfert->dossier->numero_dossier_judiciaire ?? 'N/A',
                    $transfert->serviceSource->nom ?? 'N/A',
                    $transfert->serviceDestination->nom ?? 'N/A',
                    $transfert->userSource->name ?? 'N/A',
                    $transfert->userDestination->name ?? 'N/A',
                    $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A',
                    $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'En attente',
                    $transfert->statut,
                    $transfert->commentaire,
                    $delaiValidation,
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Affiche les détails d'un transfert spécifique
     */
    public function show($id)
    {
        $transfert = Transfert::with([
            'dossier', 
            'userSource', 
            'userDestination', 
            'serviceSource', 
            'serviceDestination'
        ])->findOrFail($id);
        
        return view('transferts.show', compact('transfert'));
    }
}