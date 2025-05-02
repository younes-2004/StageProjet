<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Reception;
use App\Models\User;
use App\Models\Service;
use App\Models\DossierValide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReceptionController extends Controller
{
    /**
     * Affiche le formulaire d'envoi d'un dossier
     *
     * @param int $dossier_id
     * @return \Illuminate\View\View
     */
    public function createEnvoi($dossier_id)
    {
        $dossier = Dossier::findOrFail($dossier_id);
        $users = User::all();
        $services = Service::all();

        return view('receptions.envoi', compact('dossier', 'users', 'services'));
    }

    /**
     * Traite l'envoi du dossier vers un utilisateur/service
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeEnvoi(Request $request)
    {
        Log::info('Début de storeEnvoi', ['request' => $request->all()]);
        
        $validated = $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // Enregistrer l'envoi dans la table Reception
        $reception = new Reception();
        $reception->dossier_id = $validated['dossier_id'];
        $reception->user_id = $validated['user_id'];
        $reception->date_reception = Carbon::now();
        $reception->traite = false;
        $reception->save();

        Log::info('Réception créée', ['reception_id' => $reception->id]);

        // Ajouter l'action dans la table historique_actions
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $validated['dossier_id'],
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'transfert',
            'description' => 'Dossier transféré à l\'utilisateur ID ' . $validated['user_id'],
            'date_action' => now(),
        ]);
        
        // Récupérer les informations nécessaires
        $userDestination = User::findOrFail($validated['user_id']);
        $userSource = auth()->user();
    
        // Enregistrer dans la table transferts
        \App\Models\Transfert::create([
            'dossier_id' => $validated['dossier_id'],
            'service_source_id' => $userSource->service_id,
            'service_destination_id' => $userDestination->service_id,
            'user_source_id' => $userSource->id,
            'user_destination_id' => $validated['user_id'],
            'date_envoi' => Carbon::now(),
            'date_reception' => null,
            'statut' => 'envoyé',
        ]);

        return redirect()->route('dossiers.index')
            ->with('success', 'Le dossier a été envoyé avec succès et l\'action a été enregistrée.');
    }

/**
 * Affiche la liste des dossiers reçus par l'utilisateur connecté
 * Ne montre que les dossiers non validés
 *
 * @return \Illuminate\View\View
 */
public function inbox()
{
    // Récupérer uniquement les réceptions non traitées
    $receptions = Reception::where('user_id', Auth::id())
        ->where('traite', false) // Seulement les non traitées
        ->with(['dossier', 'dossier.createur']) // Chargement eager des relations
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Pour débogage, enregistrer dans les logs
    Log::info('Inbox: Récupération des réceptions non traitées', [
        'user_id' => Auth::id(),
        'count' => $receptions->count(),
        'ids' => $receptions->pluck('id')->toArray()
    ]);

    // Récupérer les utilisateurs pour la vue
    $users = User::where('id', '!=', Auth::id())->get();
    
    return view('receptions.inbox', compact('receptions', 'users'));
}

   /**
 * Valide la réception d'un dossier
 * 
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function validerReception(Request $request, $id)
{
    try {
        // Trouver la réception
        $reception = Reception::findOrFail($id);
        
        // Log pour débogage
        Log::info('Début de validation de la réception', [
            'reception_id' => $id,
            'user_id' => Auth::id(),
            'dossier_id' => $reception->dossier_id
        ]);
        
        // Vérifier que l'utilisateur connecté est bien le destinataire
        if ($reception->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }
        
        // Vérifier si ce dossier est déjà validé
        $existingValidation = DossierValide::where('dossier_id', $reception->dossier_id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingValidation) {
            return redirect()->back()->with('error', 'Ce dossier a déjà été validé.');
        }
        
        DB::beginTransaction();
        
        try {
            // 1. Créer un enregistrement dans la table dossiers_valides
            DossierValide::create([
                'dossier_id' => $reception->dossier_id,
                'user_id' => Auth::id(),
                'date_validation' => now(),
                'commentaire' => $request->input('commentaire_reception'),
                'observations' => $request->input('observations')
            ]);
            
            // 2. Marquer CETTE réception comme traitée
            $affected = $reception->update([
                'traite' => true,
                'date_traitement' => now()
            ]);
            
            Log::info('Mise à jour de la réception', [
                'reception_id' => $reception->id,
                'affected' => $affected
            ]);
            
            // 3. Ajouter l'action dans la table historique_actions
            \App\Models\HistoriqueAction::create([
                'dossier_id' => $reception->dossier_id,
                'user_id' => Auth::id(),
                'service_id' => Auth::user()->service_id,
                'action' => 'validation',
                'description' => 'Dossier validé',
                'date_action' => now(),
            ]);
            
            DB::commit();
            
            // Vérification après validation que le statut a bien été mis à jour
            $receptionUpdated = Reception::find($id);
            Log::info('État de la réception après mise à jour', [
                'reception_id' => $id,
                'traite' => $receptionUpdated->traite
            ]);
            
            return redirect()->route('receptions.inbox')
                ->with('success', 'Dossier validé avec succès et déplacé vers les dossiers validés.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la validation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation : ' . $e->getMessage());
        }
    } catch (\Exception $e) {
        Log::error('Erreur générale lors de la validation', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    }
}

    /**
     * Réaffecter un dossier reçu à un autre service
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reassignerDossier(Request $request)
    {
        $validated = $request->validate([
            'transfert_id' => 'required|exists:transferts,id',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Trouver le transfert original
        $transfertOriginal = \App\Models\Transfert::findOrFail($validated['transfert_id']);
        
        // Vérifier que l'utilisateur connecté est bien le destinataire du transfert original
        if ($transfertOriginal->user_destination_id != Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }
        
        // Marquer le transfert original comme validé
        $transfertOriginal->update([
            'date_reception' => Carbon::now(),
            'statut' => 'validé'
        ]);
        
        // Récupérer les informations nécessaires
        $userDestination = User::findOrFail($validated['user_id']);
        $userSource = auth()->user();
        
        // Créer un nouveau transfert
        \App\Models\Transfert::create([
            'dossier_id' => $transfertOriginal->dossier_id,
            'service_source_id' => $userSource->service_id,
            'service_destination_id' => $userDestination->service_id,
            'user_source_id' => $userSource->id,
            'user_destination_id' => $validated['user_id'],
            'date_envoi' => Carbon::now(),
            'date_reception' => null,
            'statut' => 'envoyé',
            'commentaire' => $request->input('commentaire')
        ]);
        
        // Ajouter l'action dans la table historique_actions
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $transfertOriginal->dossier_id,
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'reassignation',
            'description' => 'Dossier réaffecté à l\'utilisateur ID ' . $validated['user_id'],
            'date_action' => now(),
        ]);
        
        return redirect()->route('receptions.inbox')
          ->with('success', 'Dossier réaffecté avec succès.');
    }

    /**
     * Affiche la liste des dossiers validés par l'utilisateur connecté
     *
     * @return \Illuminate\View\View
     */
    public function dossiersValides()
    {
        $dossiersValides = DossierValide::where('user_id', Auth::id())
            ->with('dossier')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('receptions.dossiers_valides', compact('dossiersValides', 'users'));
    }

    public function reaffecter(Dossier $dossier)
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $services = Service::all(); // Récupérer tous les services

        return view('receptions.reaffecter', compact('dossier', 'users','services'));
    }

    public function archiver(Dossier $dossier)
    {
        // Mettre à jour le statut du dossier en "Archivé"
        $dossier->update(['statut' => 'Archivé']);

        return redirect()->route('receptions.dossiers_valides')->with('success', 'Le dossier a été archivé avec succès.');
    }

    public function storeReaffectation(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        // Récupérer le service_id de l'utilisateur connecté (émetteur)
        $serviceId = Auth::user()->service_id;

        // Enregistrer l'action dans la table historique_actions
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $dossier->id,
            'user_id' => Auth::id(), // Utilisateur qui effectue la réaffectation
            'service_id' => $serviceId, // Service de l'émetteur
            'action' => 'réaffectation',
            'description' => 'Dossier réaffecté à l\'utilisateur ID ' . $validated['user_id'],
            'date_action' => now(),
        ]);

        // Enregistrer le transfert dans la table transferts
        \App\Models\Transfert::create([
            'dossier_id' => $dossier->id,
            'service_source_id' => $serviceId, // Service source de l'utilisateur connecté
            'service_destination_id' => $serviceId, // Service destination (même service pour cet exemple)
            'user_source_id' => Auth::id(), // Utilisateur source
            'user_destination_id' => $validated['user_id'], // Utilisateur destination
            'date_envoi' => now(),
            'statut' => 'réaffectation', // Statut du transfert
        ]);

        // Redirection avec un message de succès
        return redirect()->route('receptions.dossiers_valides')->with('success', 'Le dossier a été réaffecté avec succès.');
    } 
}