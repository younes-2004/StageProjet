<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\HistoriqueAction;
use Illuminate\Http\Request;
use App\Models\Transfert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Reception;
use App\Models\DossierValide;

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
    $userId = auth()->id();
    
    // Récupérer les IDs des dossiers qui ont été validés ET qui ne sont pas désarchivés
    $dossiersValidesIds = \App\Models\Transfert::where('user_source_id', $userId)
        ->whereNotNull('date_reception')
        ->whereHas('dossier', function($query) {
            $query->where('statut', '!=', 'Créé'); // Exclure les dossiers désarchivés (statut Créé)
        })
        ->pluck('dossier_id')
        ->toArray();
    
    // Si pas de dossiers validés, utiliser un tableau vide
    if (empty($dossiersValidesIds)) {
        $dossiersValidesIds = [0]; // Valeur impossible pour éviter l'erreur SQL
    }
    
    // Récupérer les dossiers du user avec les conditions suivantes:
    // 1. Créés par lui (y compris les désarchivés)
    // 2. Pas dans la liste des dossiers déjà transférés/validés
    // 3. Statut != 'Archivé' (pour exclure les archivés)
    $dossiers = Dossier::where('createur_id', $userId)
        ->whereNotIn('id', $dossiersValidesIds)
        ->where('statut', '!=', 'Archivé') // Exclure les dossiers archivés
        ->get();
    
    // Récupérer uniquement les transferts validés pour l'historique (exclure les désarchivés)
    $dossiersEnvoyes = \App\Models\Transfert::where('user_source_id', $userId)
        ->whereNotNull('date_reception')
        ->whereHas('dossier', function($query) {
            $query->where('statut', '!=', 'Créé'); // Exclure les dossiers désarchivés de l'historique
        })
        ->with(['dossier', 'userDestination', 'serviceDestination'])
        ->orderBy('date_reception', 'desc')
        ->get();
    
    // Log pour débogage
    \Illuminate\Support\Facades\Log::info('Données transmises à la vue', [
        'dossiers_count' => $dossiers->count(),
        'dossiersEnvoyes_count' => $dossiersEnvoyes->count(),
        'user_id' => $userId,
        'dossiers_ids' => $dossiers->pluck('id')->toArray(),
        'dossiersValidesIds' => $dossiersValidesIds
    ]);
    
    return view('dossiers.mes_dossiers', compact('dossiers', 'dossiersEnvoyes'));
}
    // Affiche le formulaire de création
public function create()
{
    $services = \App\Models\Service::all();
    $genres = $this->getEnumValues('dossiers', 'genre');
    return view('dossiers.create', compact('services', 'genres'));
}
 // Enregistre un nouveau dossier
public function store(Request $request)
{
    ini_set('memory_limit', '2G');
    set_time_limit(600); // 10 minutes
    
    \Illuminate\Support\Facades\Log::info('Données de formulaire reçues:', $request->all());
    \Illuminate\Support\Facades\Log::info('Fichiers reçus:', $request->allFiles());
    
    $request->validate([
        'numero_dossier_judiciaire' => 'required|string|max:255|unique:dossiers,numero_dossier_judiciaire',
        'titre' => 'required|string|max:255',
        'type_contenu' => 'required|in:texte,pdf',
        'contenu_texte' => 'required_if:type_contenu,texte',
        'contenu_pdf' => 'required_if:type_contenu,pdf|file|mimes:pdf|max:1048576', // 1 Go (en Ko)
        'genre' => 'required|string|max:255',
    ]);
    
    $contenu = '';
    $typeContenu = $request->type_contenu;

    if ($typeContenu === 'texte') {
        $contenu = $request->contenu_texte;
    } elseif ($typeContenu === 'pdf' && $request->hasFile('contenu_pdf')) {
        $fichier = $request->file('contenu_pdf');
        
        // Log la taille du fichier pour déboguer
        \Illuminate\Support\Facades\Log::info('Téléchargement de fichier volumineux:', [
            'name' => $fichier->getClientOriginalName(),
            'size_bytes' => $fichier->getSize(),
            'size_mb' => round($fichier->getSize() / (1024 * 1024), 2) . ' Mo',
            'size_gb' => round($fichier->getSize() / (1024 * 1024 * 1024), 4) . ' Go'
        ]);
        
        // Utiliser un stockage par morceaux pour les gros fichiers
        try {
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $chemin = $fichier->storeAs('contenus_pdf', $nomFichier, 'public');
            $contenu = $chemin;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur de téléchargement:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->withErrors(['contenu_pdf' => 'فشل تحميل الملف: ' . $e->getMessage()]);
        }
    } elseif ($typeContenu === 'pdf') {
        // Si le type est pdf mais qu'aucun fichier n'a été fourni
        return redirect()->back()->withInput()->withErrors(['contenu_pdf' => 'الملف PDF مطلوب لهذا النوع من المحتوى.']);
    }
    
    // Création du dossier
    $dossier = Dossier::create([
        'numero_dossier_judiciaire' => $request->numero_dossier_judiciaire,
        'titre' => $request->titre,
        'contenu' => $contenu,
        'type_contenu' => $typeContenu,
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
        'description' => 'إنشاء مجلد برقم' . $dossier->numero_dossier_judiciaire,
        'date_action' => now(),
    ]);
    
    if ($dossier) {
        return redirect()->route('dossiers.mes_dossiers')
            ->with('success', 'تم إنشاء ملف "' . $dossier->titre . '" بنجاح برقم ' . $dossier->numero_dossier_judiciaire);
    }
    
    // En cas d'échec, rediriger avec un message d'erreur
    return redirect()->route('dossiers.create')
        ->with('error', 'حدث عطل أثناء محاولة إنشاء المجلد.');
}
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
    
    

    // Retourner la vue avec les détails du dossier
    return view('dossiers.show', compact('dossier'));
}
// app/Http/Controllers/DossierController.php - méthodes à ajouter

/**
 * Affiche la vue détaillée d'un dossier
 *
 * @param Dossier $dossier
 * @return \Illuminate\View\View
 */
public function detail(Dossier $dossier)
{
    // Vérifier que l'utilisateur a accès au dossier
    $this->authorizeView($dossier);
    
    return view('dossiers.detail', compact('dossier'));
}

/**
 * Affiche le formulaire d'édition d'un dossier
 *
 * @param Dossier $dossier
 * @return \Illuminate\View\View
 */
public function edit(Dossier $dossier)
{
    // Vérifier que l'utilisateur peut éditer le dossier
    $this->authorizeEdit($dossier);

    $services = \App\Models\Service::all();
    $genres = $this->getEnumValues('dossiers', 'genre');

    return view('dossiers.edit', compact('dossier', 'services', 'genres'));
}

/**
 * Met à jour un dossier existant
 *
 * @param \Illuminate\Http\Request $request
 * @param Dossier $dossier
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(Request $request, Dossier $dossier)
{
    // Vérifier que l'utilisateur peut éditer le dossier
    $this->authorizeEdit($dossier);
    
    // Validation des données
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'type_contenu' => 'required|in:texte,pdf',
        'contenu_texte' => 'required_if:type_contenu,texte',
        'contenu_pdf' => 'required_if:type_contenu,pdf,remplacer_pdf|file|mimes:pdf|max:1048576', // 1 Go (en Ko)
        'genre' => 'required|string|max:255',
        'remplacer_pdf' => 'nullable|in:1',
    ]);
    
    // Augmenter les limites pour les gros fichiers
    ini_set('memory_limit', '2G');
    set_time_limit(600);
    
    $contenu = $dossier->contenu;
    $typeContenu = $request->type_contenu;
    
    try {
        // Gérer le contenu selon son type
        if ($typeContenu === 'texte') {
            $contenu = $request->contenu_texte;
            
            // Si on change de type (PDF -> texte), supprimer l'ancien fichier PDF
            if ($dossier->type_contenu === 'pdf') {
                Storage::disk('public')->delete($dossier->contenu);
            }
        } 
        // Si le type est PDF et qu'on veut remplacer par un nouveau PDF
        else if ($typeContenu === 'pdf' && $request->hasFile('contenu_pdf')) {
            // Si on avait déjà un PDF, on le supprime
            if ($dossier->type_contenu === 'pdf') {
                Storage::disk('public')->delete($dossier->contenu);
            }
            
            // Traiter le nouveau fichier PDF
            $fichier = $request->file('contenu_pdf');
            
            // Log pour débogage
            \Illuminate\Support\Facades\Log::info('Mise à jour PDF:', [
                'name' => $fichier->getClientOriginalName(),
                'size' => $fichier->getSize(),
                'size_mb' => round($fichier->getSize() / (1024 * 1024), 2) . ' Mo'
            ]);
            
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $chemin = $fichier->storeAs('contenus_pdf', $nomFichier, 'public');
            
            $contenu = $chemin;
        }
        // Si on garde le PDF existant, ne rien changer au contenu
        
        // Mise à jour du dossier
        $dossier->update([
            'titre' => $validated['titre'],
            'contenu' => $contenu,
            'type_contenu' => $typeContenu,
            'genre' => $validated['genre']
        ]);
        
        // Enregistrement dans l'historique
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $dossier->id,
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'modification',
            'description' => 'تعديل الدليل ذي الرقم' . $dossier->numero_dossier_judiciaire,
            'date_action' => now(),
        ]);
        
        return redirect()->route('dossiers.detail', $dossier->id)
            ->with('success', 'تم تعديل المجلد بنجاح.');
            
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Erreur lors de la mise à jour:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'حدث عطل أثناء تعديل المجلد: ' . $e->getMessage());
    }
}

/**
 * Vérifier si l'utilisateur peut voir le dossier
 *
 * @param Dossier $dossier
 * @return void
 */
private function authorizeView(Dossier $dossier)
{
    $currentUserId = auth()->id();
    
    // L'utilisateur est le créateur du dossier
    $isCreator = ($dossier->createur_id === $currentUserId);
    
    // L'utilisateur est un récepteur du dossier
    $isReceiver = \App\Models\Reception::where('dossier_id', $dossier->id)
        ->where('user_id', $currentUserId)
        ->exists();
        
    // L'utilisateur a validé ce dossier
    $hasValidated = \App\Models\DossierValide::where('dossier_id', $dossier->id)
        ->where('user_id', $currentUserId)
        ->exists();
        
    // L'utilisateur est greffier en chef (peut voir tous les dossiers)
    $isChiefClerk = (auth()->user()->role === 'greffier_en_chef');
    
    // Autoriser l'accès si l'utilisateur remplit au moins une des conditions
    if (!$isCreator && !$isReceiver && !$hasValidated && !$isChiefClerk) {
        abort(403, ' غير مسموح بالوصول: ليس لديك صلاحية كمُنشئ أو مستلم معتمد لهذا المجلد');
    }
}

/**
 * Vérifier si l'utilisateur peut éditer le dossier
 *
 * @param Dossier $dossier
 * @return void
 */
private function authorizeEdit(Dossier $dossier)
{
    $currentUserId = auth()->id();
    
    // L'utilisateur est le créateur du dossier
    $isCreator = ($dossier->createur_id === $currentUserId);
    
    // L'utilisateur est greffier en chef (peut éditer tous les dossiers)
    $isChiefClerk = (auth()->user()->role === 'greffier_en_chef');
    
    // Autoriser l'édition si l'utilisateur est créateur ou greffier en chef
    if (!$isCreator && !$isChiefClerk) {
        abort(403, 'غير مسموح بالوصول: فقط المنشئ أو كاتب الضبط الرئيسي يمكنه تعديل هذا المجلد.');
    }
}
public function destroy(Dossier $dossier)
{
    // Vérifier que l'utilisateur a le rôle de greffier en chef
    if (auth()->user()->role !== 'greffier_en_chef') {
        abort(403, 'ليست لديك صلاحية حذف الملفات');
    }

    try {
        if ($dossier->type_contenu === 'pdf') {
            Storage::disk('public')->delete($dossier->contenu);
        }
        // Supprimer les enregistrements associés
        \App\Models\HistoriqueAction::where('dossier_id', $dossier->id)->delete();
        \App\Models\Transfert::where('dossier_id', $dossier->id)->delete();
        \App\Models\Reception::where('dossier_id', $dossier->id)->delete();
        \App\Models\DossierValide::where('dossier_id', $dossier->id)->delete();

        // Supprimer le dossier
        $dossier->delete();

        // Redirection avec message de succès
        return redirect()->route('dossiers.search')
            ->with('success','تم حذف المجلد بنجاح');
    } catch (\Exception $e) {
        // Gérer les erreurs de suppression
        return redirect()->route('dossiers.search')
            ->with('حدث خطأ أثناء محاولة مسح المجلد' . $e->getMessage());
    }
}
// Ajoutez cette méthode à la fin de votre DossierController.php

public function archives()
{
    $currentUserId = auth()->id();
    
    // Tous les utilisateurs (greffier et greffier_en_chef) ne voient que leurs propres dossiers archivés
    // Soit les dossiers qu'ils ont créés, soit les dossiers qu'ils ont validés
    $dossiersArchives = Dossier::where('statut', 'Archivé')
        ->with('service')
        ->where(function($query) use ($currentUserId) {
            $query->where('createur_id', $currentUserId)
                  ->orWhereHas('dossiersValides', function($subQuery) use ($currentUserId) {
                      $subQuery->where('user_id', $currentUserId);
                  });
        })
        ->orderBy('updated_at', 'desc')
        ->paginate(15);
    
    return view('dossiers.archives', compact('dossiersArchives'));
}

public function archiver(Request $request, Dossier $dossier)
{

    try {
        // Mettre à jour le statut du dossier à "Archivé"
        $dossier->update([
            'statut' => 'Archivé'
        ]);

        // Ajouter une entrée dans l'historique des actions
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $dossier->id,
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'archivage',
            'description' => 'تم أرشفة الملف',
            'date_action' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'تم أرشفة الملف بنجاح');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء أرشفة الملف: ' . $e->getMessage());
    }
}
public function desarchiver(Request $request, Dossier $dossier)
{
    try {
        // Vérifier que l'utilisateur peut désarchiver le dossier
        $currentUserId = auth()->id();
        $userRole = auth()->user()->role;
        
        // Autoriser si : créateur du dossier OU greffier en chef OU a validé le dossier
        $isAuthorized = (
            $dossier->createur_id === $currentUserId ||
            $userRole === 'greffier_en_chef' ||
            \App\Models\DossierValide::where('dossier_id', $dossier->id)
                ->where('user_id', $currentUserId)
                ->exists()
        );

        if (!$isAuthorized) {
            return redirect()->back()->with('error', 'غير مسموح لك بإلغاء أرشفة هذا الملف');
        }

        // Vérifier que le dossier est bien archivé
        if ($dossier->statut !== 'Archivé') {
            return redirect()->back()->with('error', 'هذا الملف ليس مؤرشفاً');
        }

        // Le dossier désarchivé retourne toujours au statut "Créé"
        $nouveauStatut = 'Créé';

        // Supprimer complètement les anciens transferts/réceptions pour éviter les conflits
        // L'historique sera préservé dans historique_actions pour l'administrateur
        \App\Models\Transfert::where('dossier_id', $dossier->id)->delete();
        \App\Models\Reception::where('dossier_id', $dossier->id)->delete();
        \App\Models\DossierValide::where('dossier_id', $dossier->id)->delete();

        // Mettre à jour le statut du dossier ET changer le créateur
        $dossier->update([
            'statut' => $nouveauStatut,
            'createur_id' => auth()->id() // La personne qui désarchive devient le nouveau créateur
        ]);

        // Ajouter une entrée dans l'historique des actions
        \App\Models\HistoriqueAction::create([
            'dossier_id' => $dossier->id,
            'user_id' => auth()->id(),
            'service_id' => auth()->user()->service_id,
            'action' => 'modification',
            'description' => 'إلغاء أرشفة الملف وإعادة تعيينه كملف جديد - تم حذف جميع المعاملات السابقة',
            'date_action' => now(),
        ]);

        return redirect()->route('dossiers.archives')
            ->with('success', 'تم إلغاء أرشفة الملف بنجاح وإعادته إلى ملفاتك');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء إلغاء أرشفة الملف: ' . $e->getMessage());
    }
}
}