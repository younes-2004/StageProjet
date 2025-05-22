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
use App\Models\HistoriqueAction; // Ajoutez cette ligne
use App\Models\Transfert; // Ajoutez cette ligne

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
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string',
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
            'description' => 'تم تحويل الملف إلى المستخدم ذو الرقم التعريفي ' . $validated['user_id'],
            'date_action' => now(),
        ]);
        
        // Récupérer les informations nécessaires
        $userDestination = User::findOrFail($validated['user_id']);
        $userSource = auth()->user();
        
        // Enregistrer dans la table transferts
        $transfert = \App\Models\Transfert::create([
            'dossier_id' => $validated['dossier_id'],
            'service_source_id' => $userSource->service_id,
            'service_destination_id' => $validated['service_id'],
            'user_source_id' => $userSource->id,
            'user_destination_id' => $validated['user_id'],
            'date_envoi' => Carbon::now(),
            'date_reception' => null,
            'statut' => 'envoyé',
           'commentaire' => $validated['message'] ?? null, // Changement de 'message' à 'commentaire'
        ]);
        
        // Récupérer les informations du dossier pour le message de confirmation
        $dossier = \App\Models\Dossier::findOrFail($validated['dossier_id']);
         // AJOUT: Mettre à jour le statut du dossier à "Transmis"
         $dossier->update([
            'statut' => 'Transmis'
        ]);
        $destinataire = User::findOrFail($validated['user_id']);
        $message = 'تم إرسال الملف "' . $dossier->titre . '" بنجاح إلى ' . $destinataire->name . ' (' . $destinataire->email . ').';
        
        // Rediriger vers la même page avec un message de confirmation
        return redirect()->route('dossiers.mes_dossiers', $dossier->id)
            ->with('success',$message)
            ->with('transfert_id', $transfert->id);
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
      // Trouver la réception
    $reception = Reception::findOrFail($id);
    
    // Vérifier que l'utilisateur connecté est bien le destinataire
    if ($reception->user_id != Auth::id()) {
        return redirect()->back()->with('غير مصرح لك بتنفيذ هذا الإجراء.');
    }
    
    // Récupérer le dossier associé
    $dossier = $reception->dossier;
    
    // Créer un enregistrement dans la table dossiers_valides
    DossierValide::create([
        'dossier_id' => $dossier->id,
        'user_id' => Auth::id(),
        'date_validation' => now(),
        'commentaire' => $request->input('commentaire_reception', ''),
        'observations' => $request->input('observations', '')
    ]);
    
    // Marquer le dossier comme traité dans la table reception
    $reception->update([
        'traite' => true,
        'date_traitement' => now()
    ]);
    
    // Mettre à jour le statut du dossier
    $dossier->update([
        'statut' => 'Validé'
    ]);
    
    // IMPORTANT: Mettre à jour le transfert avec la date de réception
    $transfert = \App\Models\Transfert::where('dossier_id', $dossier->id)
        ->where('user_destination_id', Auth::id())
        ->whereNull('date_reception') // Chercher uniquement les transferts sans date de réception
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($transfert) {
        $transfert->update([
            'date_reception' => now(),
            'statut' => 'validé'
        ]);
        
        // Log pour vérifier
        Log::info('Transfert mis à jour', [
            'transfert_id' => $transfert->id,
            'dossier_id' => $dossier->id,
            'date_reception' => $transfert->date_reception,
            'statut' => $transfert->statut
        ]);
    } else {
        Log::warning('Aucun transfert trouvé pour le dossier', [
            'dossier_id' => $dossier->id,
            'user_destination_id' => Auth::id()
        ]);
    }
    
    // Supprimer toutes les réceptions pour ce dossier
    Reception::where('dossier_id', $dossier->id)->delete();
    
    // Ajouter l'action dans la table historique_actions
    HistoriqueAction::create([
        'dossier_id' => $dossier->id,
        'user_id' => Auth::id(),
        'service_id' => Auth::user()->service_id,
        'action' => 'validation',
        'description' => 'تمت المصادقة على الملف بنجاح',
        'date_action' => now(),
    ]);
    
    return redirect()->route('receptions.inbox')
        ->with('success','تمت المصادقة على الملف بنجاح.');
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
            return redirect()->back()->with('غير مصرح لك بتنفيذ هذا الإجراء.');
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
            'description' => 'تمت إعادة تعيين الملف إلى المستخدم ' . $validated['user_id'],
            'date_action' => now(),
        ]);
        
        return redirect()->route('receptions.inbox')
          ->with('success','تمت إعادة تعيين الملف بنجاح.');
    }

    /**
     * Affiche la liste des dossiers validés par l'utilisateur connecté
     *
     * @return \Illuminate\View\View
     */
    public function dossiersValides()
    {
        // Récupérer les dossiers validés par l'utilisateur
        $dossiersValides = DossierValide::where('user_id', Auth::id())
            ->with('dossier')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filtrer pour exclure les dossiers qui ont été réaffectés après validation
        $dossiersValidesFiltered = $dossiersValides->filter(function($dossierValide) {
            // Vérifier s'il y a eu une réaffectation APRÈS la validation
            $reaffectation = \App\Models\Transfert::where('dossier_id', $dossierValide->dossier_id)
                ->where('user_source_id', Auth::id())
                ->where('created_at', '>', $dossierValide->created_at)
                ->where('statut', 'réaffectation')
                ->first();
            
            // Si pas de réaffectation après validation, on garde le dossier
            return !$reaffectation;
        });
        
        // Paginer manuellement les résultats filtrés
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $currentItems = $dossiersValidesFiltered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $dossiersValides = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $dossiersValidesFiltered->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
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
            'description' => 'تمت إعادة تعيين الملف إلى المستخدم برقم التعريف' . $validated['user_id'],
            'date_action' => now(),
        ]);
    
        // Enregistrer le transfert dans la table transferts
        \App\Models\Transfert::create([
            'dossier_id' => $dossier->id,
            'service_source_id' => $serviceId, // Service source de l'utilisateur connecté
            'service_destination_id' => User::find($validated['user_id'])->service_id,
            'user_source_id' => Auth::id(), // Utilisateur source
            'user_destination_id' => $validated['user_id'], // Utilisateur destination
            'date_envoi' => now(),
            'statut' => 'réaffectation', // Statut du transfert
        ]);
    
        // IMPORTANT: Créer une nouvelle réception pour que le dossier apparaisse dans la boîte de réception
        $reception = new \App\Models\Reception();
        $reception->dossier_id = $dossier->id;
        $reception->user_id = $validated['user_id'];
        $reception->date_reception = now();
        $reception->traite = false; // Important: mettre à false pour que le dossier apparaisse dans la boîte de réception
        $reception->save();
    
        // Mettre à jour le statut du dossier
        $dossier->update([
            'statut' => 'Transmis'
        ]);
    
        // Redirection avec un message de succès
        return redirect()->route('receptions.dossiers_valides')->with('success','تمت إعادة تعيين الملف بنجاح.');
    }
        /**
         * Annuler un transfert de dossier
         *
         * @param Request $request
         * @param int $transfertId
         * @return \Illuminate\Http\RedirectResponse
         */
        public function annulerTransfert(Request $request, $transfertId)
        {
            try {
                // Log des informations de débogage
                Log::info('Tentative d\'annulation de transfert', [
                    'transfert_id' => $transfertId,
                    'user_id' => Auth::id(),
                    'timestamp' => now(),
                ]);
    
                // Récupérer le transfert avec ses relations
                $transfert = Transfert::with(['dossier', 'userSource', 'userDestination'])
                    ->findOrFail($transfertId);
                
                // Vérifier que le transfert appartient à l'utilisateur connecté
                if ($transfert->user_source_id !== Auth::id()) {
                    Log::warning('Tentative d\'annulation non autorisée', [
                        'transfert_id' => $transfertId,
                        'source_user_id' => $transfert->user_source_id,
                        'current_user_id' => Auth::id(),
                    ]);
                    return redirect()->back()->with('error', 'غير مصرح لك بإلغاء هذا التحويل');
                }
                
                // Vérifier le délai (moins de 24 heures)
                $heuresCoulees = now()->diffInHours($transfert->date_envoi);
                if ($heuresCoulees > 24) {
                    Log::warning('Tentative d\'annulation après 24 heures', [
                        'transfert_id' => $transfertId,
                        'heures_coulees' => $heuresCoulees,
                    ]);
                    return redirect()->back()->with('error', 'لا يمكن إلغاء التحويل بعد 24 ساعة');
                }
                
                // Vérifier qu'aucune validation n'a été faite
                $validationExiste = DossierValide::where('dossier_id', $transfert->dossier_id)->exists();
                if ($validationExiste) {
                    Log::warning('Tentative d\'annulation d\'un transfert déjà validé', [
                        'transfert_id' => $transfertId,
                        'dossier_id' => $transfert->dossier_id,
                    ]);
                    return redirect()->back()->with('error', 'لا يمكن إلغاء تحويل تم التحقق منه');
                }
                
                // Début de la transaction
                DB::beginTransaction();
                
                // Supprimer le transfert
                $transfertDeleted = $transfert->delete();
                Log::info('Suppression du transfert', [
                    'transfert_id' => $transfertId,
                    'deleted' => $transfertDeleted,
                ]);
                
                // Supprimer les réceptions associées
                $receptionsDeleted = Reception::where('dossier_id', $transfert->dossier_id)
                    ->where('user_id', $transfert->user_destination_id)
                    ->delete();
                Log::info('Suppression des réceptions', [
                    'dossier_id' => $transfert->dossier_id,
                    'user_destination_id' => $transfert->user_destination_id,
                    'deleted' => $receptionsDeleted,
                ]);
                
                // Ajouter une entrée dans l'historique des actions
                $historiqueCreated = HistoriqueAction::create([
                    'dossier_id' => $transfert->dossier_id,
                    'user_id' => Auth::id(),
                    'service_id' => Auth::user()->service_id,
                    'action' => 'annulation', // Utiliser l'action ajoutée à l'ENUM
                    'description' => 'إلغاء تحويل الملف قبل التحقق',
                    'date_action' => now(),
                ]);
                Log::info('Création de l\'historique', [
                    'historique_id' => $historiqueCreated->id,
                ]);
                
                // Commit de la transaction
                DB::commit();
                
                // Rediriger avec un message de succès
                return redirect()->route('dossiers.mes_dossiers')
                    ->with('success', 'تم إلغاء التحويل بنجاح');
            } catch (\Exception $e) {
                // Rollback en cas d'erreur
                DB::rollBack();
                
                // Log détaillé de l'erreur
                Log::error('Erreur lors de l\'annulation du transfert', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'transfert_id' => $transfertId,
                    'user_id' => Auth::id(),
                ]);
                
                // Rediriger avec un message d'erreur détaillé
                return redirect()->back()->with('error', 'حدث خطأ أثناء إلغاء التحويل: ' . $e->getMessage());
            }
        }
    }


 
