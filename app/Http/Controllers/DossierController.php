<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\HistoriqueAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DossierController extends Controller
{
    // Affiche la liste des dossiers
    public function index()
    {
        $dossiers = Dossier::where('createur_id', auth()->id())->get();
        return view('dossiers.mes_dossiers', compact('dossiers'));
    }
    public function mesDossiers()
    {
        $dossiers = Dossier::where('createur_id', auth()->id())->get();
        return view('dossiers.mes_dossiers', compact('dossiers'));
    }

    // Affiche le formulaire de création
    public function create()
{
    $services = \App\Models\Service::all(); // Récupère les services pour le formulaire
    return view('dossiers.create', compact('services'));
}

    // Enregistre un nouveau dossier
    public function store(Request $request)
    {
        $request->validate([
            'numero_dossier_judiciaire' => 'required|string|max:255|unique:dossiers,numero_dossier_judiciaire',
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'genre' => 'required|string|max:255',
        ]);

        // Création du dossier
        $dossier = Dossier::create([
            'numero_dossier_judiciaire' => $request->numero_dossier_judiciaire,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'createur_id' => auth()->id(),
            'service_id' => auth()->user()->service_id, // Récupération automatique du service_id
            'statut' => 'Créé',
            'date_creation' => now(),
            'genre' => $request->genre,
        ]);

        // Enregistrement dans l'historique des actions
        HistoriqueAction::create([
            'dossier_id' => $dossier->id,
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'creation',
            'description' => 'Création du dossier avec le numéro ' . $dossier->numero_dossier_judiciaire,
            'date_action' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Dossier créé avec succès et enregistré dans l\'historique des actions !');
    }

  /**
 * Affiche un dossier spécifique
 *
 * @param Dossier $dossier
 * @return \Illuminate\View\View
 */
public function show(Dossier $dossier)
{
    $currentUserId = auth()->id();
    
    // Vérifier si l'utilisateur est le créateur du dossier
    $isCreator = ($dossier->createur_id === $currentUserId);
    
    // Vérifier si l'utilisateur est un récepteur du dossier
    $isReceiver = \App\Models\Reception::where('dossier_id', $dossier->id)
        ->where('user_id', $currentUserId)
        ->exists();
        
    // Vérifier si l'utilisateur a validé ce dossier
    $hasValidated = \App\Models\DossierValide::where('dossier_id', $dossier->id)
        ->where('user_id', $currentUserId)
        ->exists();
    
    // Autoriser l'accès si l'utilisateur est le créateur, un récepteur ou a validé le dossier
    if (!$isCreator && !$isReceiver && !$hasValidated) {
        abort(403, 'Accès non autorisé. Vous n\'êtes ni le créateur ni un destinataire de ce dossier.');
    }

    // Retourner la vue avec les détails du dossier
    return view('dossiers.show', compact('dossier'));
}

}