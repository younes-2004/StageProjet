<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ReceptionController;
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
      // Ajoutez cette ligne à votre fichier routes/web.php
Route::patch('/receptions/{id}/mark-as-read', [ReceptionController::class, 'markAsRead'])
->name('receptions.mark-as-read');
});
Route::middleware(['auth'])->group(function () {
    // Autres routes...

    // Route pour afficher le formulaire d'envoi d'un dossier
    Route::get('/dossiers/{dossier}/envoyer', [DossierController::class, 'envoyer'])->name('dossiers.envoyer');
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
Route::patch('/receptions/{id}/valider', [App\Http\Controllers\ReceptionController::class, 'validerReception'])->name('receptions.valider');
Route::post('/receptions/reassigner', [App\Http\Controllers\ReceptionController::class, 'reassignerDossier'])->name('receptions.reassigner');
// Route pour afficher les dossiers validés
Route::get('/dossiers-valides', [ReceptionController::class, 'dossiersValides'])
    ->name('receptions.dossiers-valides');
    Route::middleware(['auth'])->group(function () {
        // Route pour archiver un dossier
    Route::patch('/receptions/{dossier}/archiver', [ReceptionController::class, 'archiver'])->name('dossiers.archiver');
  
    });         
// 
// Route pour réaffecter un dossier validé
Route::post('/dossiers-valides/reassigner', [ReceptionController::class, 'reassignerDossierValide'])
    ->name('receptions.reassigner-valide');
    Route::middleware(['auth'])->group(function () {
        // Autres routes...
    
        // Route pour afficher les dossiers validés
        Route::get('/dossiers-valides', [ReceptionController::class, 'dossiersValides'])->name('receptions.dossiers_valides');
    });
    
    Route::middleware(['auth'])->group(function () {
        // Route pour afficher le formulaire de réaffectation
        Route::get('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'reaffecter'])->name('receptions.reaffecter');
        Route::patch('/receptions/{dossier}/reaffecter', [ReceptionController::class, 'storeReaffectation'])->name('dossiers.reaffecter.store');
        
    });
// Routes pour la gestion des utilisateurs
Route::middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    // Routes pour la gestion des utilisateurs
Route::middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
});
// Routes pour la gestion des services (à ajouter dans le groupe middleware greffier_en_chef)
Route::middleware(['auth', 'role:greffier_en_chef'])->group(function () {
    // Routes existantes...
    
    // Routes pour la gestion des services
    Route::resource('services', App\Http\Controllers\ServiceController::class);
});
});
// Auth routes
require __DIR__.'/auth.php';