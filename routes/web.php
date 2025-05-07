<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\DossierDashboardController;
use App\Http\Controllers\DossierSearchController;
use App\Http\Controllers\DossierExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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

    // Routes pour les dossiers
    Route::get('/dossiers/create', [DossierController::class, 'create'])->name('dossiers.create');
    Route::post('/dossiers', [DossierController::class, 'store'])->name('dossiers.store');
    Route::get('/mes-dossiers', [DossierController::class, 'mesDossiers'])->name('dossiers.mes_dossiers');
    Route::get('/dossiers/{dossier}', [DossierController::class, 'show'])->name('dossiers.show');
    Route::get('/dossiers', [DossierController::class, 'index'])->name('dossiers.index');
    
    // Routes pour l'envoi et la réception de dossiers
    Route::get('/dossiers/{dossier_id}/envoi', [ReceptionController::class, 'createEnvoi'])
        ->name('receptions.create-envoi');
    Route::post('/dossiers/envoi', [ReceptionController::class, 'storeEnvoi'])
        ->name('receptions.store-envoi');
    Route::get('/inbox', [ReceptionController::class, 'inbox'])
        ->name('receptions.inbox');
    Route::patch('/receptions/{id}/mark-as-read', [ReceptionController::class, 'markAsRead'])
        ->name('receptions.mark-as-read');
        
    // Route pour afficher le formulaire d'envoi d'un dossier
    Route::get('/dossiers/{dossier}/envoyer', [DossierController::class, 'envoyer'])->name('dossiers.envoyer');
    
    // Tableau de bord des dossiers - CORRIGÉ
    Route::get('/dossiers/dashboard', [DossierDashboardController::class, 'index'])
        ->name('dossiers.dashboard');
    
    // Recherche avancée - CORRIGÉ
    Route::get('/dossiers/search', [DossierSearchController::class, 'index'])
        ->name('dossiers.search');
    
    // Export de dossiers
    Route::get('/dossiers/export', [DossierExportController::class, 'export'])
        ->name('dossiers.export');
    
    // Vue détaillée améliorée
    Route::get('/dossiers/{dossier}/detail', [DossierController::class, 'detail'])
        ->name('dossiers.detail');
    
    // Routes pour l'édition de dossier
    Route::get('/dossiers/{dossier}/edit', [DossierController::class, 'edit'])
        ->name('dossiers.edit');
    Route::put('/dossiers/{dossier}', [DossierController::class, 'update'])
        ->name('dossiers.update');
    
    // Archivage d'un dossier
    Route::patch('/receptions/{dossier}/archiver', [ReceptionController::class, 'archiver'])
        ->name('dossiers.archiver');
    
    // Routes pour afficher les dossiers validés
    Route::get('/dossiers-valides', [ReceptionController::class, 'dossiersValides'])
        ->name('receptions.dossiers_valides');
    
    // Routes pour réaffecter un dossier
    Route::get('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'reaffecter'])
        ->name('receptions.reaffecter');
    Route::patch('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'storeReaffectation'])
        ->name('dossiers.reaffecter.store');
    
    // Route pour test du dashboard
    Route::get('/test-dashboard', function() {
        return view('dossiers.dashboard', [
            'totalDossiers' => 0,
            'dossiersParStatut' => collect(),
            'dossiersRecents' => collect(),
            'dossiersParService' => collect(),
            'transfertsRecents' => collect(),
            'utilisateursActifs' => collect()
        ]);
    })->name('test.dashboard');
});

// API pour récupérer les utilisateurs d'un service
Route::get('/api/services/{service}/users', function (\App\Models\Service $service) {
    return $service->users; // Assurez-vous que la relation `users` est définie dans le modèle Service
})->middleware('auth');

// Routes admin (greffier en chef)
Route::prefix('admin')->middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'gestionUtilisateurs'])->name('admin.utilisateurs');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Routes pour validation et réaffectation
Route::patch('/receptions/{id}/valider', [ReceptionController::class, 'validerReception'])->name('receptions.valider');
Route::post('/receptions/reassigner', [ReceptionController::class, 'reassignerDossier'])->name('receptions.reassigner');

// Route pour réaffecter un dossier validé
Route::post('/dossiers-valides/reassigner', [ReceptionController::class, 'reassignerDossierValide'])
    ->name('receptions.reassigner-valide');

// Routes pour la gestion des utilisateurs et services (greffier en chef)
Route::middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    
    // Gestion des services
    Route::resource('services', ServiceController::class);
});

Route::get('/test-route', function() {
    return 'Test route fonctionne !';
});

require __DIR__.'/auth.php';