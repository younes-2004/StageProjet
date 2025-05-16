<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Remplacer la route Sanctum par une route JWT
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Ajouter les routes d'authentification JWT
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

// Ajouter ici vos autres routes API protégées par JWT
Route::group([
    'middleware' => 'auth:api'
], function () {
    // Exemple:
    // Route::apiResource('dossiers', App\Http\Controllers\Api\DossierController::class);
    // Route::apiResource('services', App\Http\Controllers\Api\ServiceController::class);
});