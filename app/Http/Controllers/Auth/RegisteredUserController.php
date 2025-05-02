<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Service;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Passer les services à la vue pour le champ service_id
        $services = Service::all();
        return view('auth.register', compact('services'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation des champs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:greffier,greffier_en_chef'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'fname' => $request->fname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'service_id' => $request->service_id,
        ]);

        // Événement d'enregistrement
        event(new Registered($user));

        // Connexion automatique de l'utilisateur
        Auth::login($user);

        // Redirection après inscription
        return redirect(RouteServiceProvider::HOME);
    }
}
