<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\DossierDashboardController;
use App\Http\Controllers\DossierSearchController;
use App\Http\Controllers\DossierExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoriqueActionController;
use App\Http\Controllers\TransfertController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par le middleware `auth`
Route::middleware(['auth'])->group(function () {
    // Routes pour le profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== ROUTES SPÉCIFIQUES DOSSIERS (ORDRE CRITIQUE) =====
    Route::get('/dossiers/create', [DossierController::class, 'create'])->name('dossiers.create');
    Route::get('/dossiers/search', [DossierSearchController::class, 'index'])->name('dossiers.search');
    Route::get('/dossiers/export', [DossierExportController::class, 'export'])->name('dossiers.export');
    Route::get('/dossiers/archives', [DossierController::class, 'archives'])
        ->name('dossiers.archives')
        ->middleware('role:greffier_en_chef');
    Route::get('/dossiers/dashboard', [DossierDashboardController::class, 'index'])
        ->name('dossiers.dashboard')
        ->middleware('role:greffier_en_chef');
    Route::get('/mes-dossiers', [DossierController::class, 'mesDossiers'])->name('dossiers.mes_dossiers');

    // Routes POST/PUT/PATCH/DELETE
    Route::post('/dossiers', [DossierController::class, 'store'])->name('dossiers.store');
    Route::put('/dossiers/{dossier}', [DossierController::class, 'update'])->name('dossiers.update');
    Route::patch('/dossiers/{dossier}/archiver', [DossierController::class, 'archiver'])
        ->name('dossiers.archiver')
        ->middleware('role:greffier_en_chef');
    Route::delete('/dossiers/{dossier}', [DossierController::class, 'destroy'])
        ->name('dossiers.destroy')
        ->middleware('role:greffier_en_chef');

    // ===== ROUTES DYNAMIQUES (EN DERNIER) =====
    Route::get('/dossiers/{dossier}/detail', [DossierController::class, 'detail'])->name('dossiers.detail');
    Route::get('/dossiers/{dossier}/edit', [DossierController::class, 'edit'])->name('dossiers.edit');
    Route::get('/dossiers/{dossier_id}/envoi', [ReceptionController::class, 'createEnvoi'])
        ->name('receptions.create-envoi');
    Route::get('/dossiers/{dossier}', [DossierController::class, 'show'])->name('dossiers.show');
    
    // Routes pour l'envoi et la réception de dossiers
    Route::post('/dossiers/envoi', [ReceptionController::class, 'storeEnvoi'])
        ->name('receptions.store-envoi');
    Route::get('/inbox', [ReceptionController::class, 'inbox'])
        ->name('receptions.inbox');
    Route::patch('/receptions/{id}/mark-as-read', [ReceptionController::class, 'markAsRead'])
        ->name('receptions.mark-as-read');
    
    // Routes pour validation et réaffectation
    Route::patch('/receptions/{id}/valider', [ReceptionController::class, 'validerReception'])->name('receptions.valider');
    Route::post('/receptions/reassigner', [ReceptionController::class, 'reassignerDossier'])->name('receptions.reassigner');
    
    // Routes pour afficher les dossiers validés
    Route::get('/dossiers-valides', [ReceptionController::class, 'dossiersValides'])
        ->name('receptions.dossiers_valides');
    
    // Routes pour réaffecter un dossier
    Route::get('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'reaffecter'])
        ->name('receptions.reaffecter');
    Route::patch('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'storeReaffectation'])
        ->name('dossiers.reaffecter.store');
    
    // Route pour annuler transfert
    Route::delete('/receptions/annuler-transfert/{transfert}', 
        [ReceptionController::class, 'annulerTransfert'])
        ->name('receptions.annuler-transfert');
});

// API pour récupérer les utilisateurs d'un service
Route::get('/api/services/{service}/users', function (\App\Models\Service $service) {
    return $service->users;
})->middleware('auth');

// Routes admin (greffier en chef)
Route::prefix('admin')->middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'gestionUtilisateurs'])->name('admin.utilisateurs');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Routes pour la gestion des utilisateurs et services (greffier en chef)
Route::middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    
    // Gestion des services
    Route::resource('services', ServiceController::class);
    
    // Historique des actions
    Route::get('/historique', [HistoriqueActionController::class, 'index'])->name('historique.index');
    Route::get('/historique/export', [HistoriqueActionController::class, 'export'])->name('historique.export');
    Route::get('/historique/{id}', [HistoriqueActionController::class, 'show'])->name('historique.show');
    
    // Transferts
    Route::get('/transferts', [TransfertController::class, 'index'])->name('transferts.index');
    Route::get('/transferts/export', [TransfertController::class, 'export'])->name('transferts.export');
    Route::get('/transferts/{id}', [TransfertController::class, 'show'])->name('transferts.show');
    Route::delete('/transferts/annuler-transfert/{transfert}', 
        [TransfertController::class, 'annulerTransfert'])
        ->name('transferts.annuler-transfert');
});

require __DIR__.'/auth.php';