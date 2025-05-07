<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Constructeur avec middleware pour autoriser uniquement le greffier en chef
     */
    public function __construct()
    {
        $this->middleware('role:greffier_en_chef');
    }

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $utilisateurs = User::with('service')->paginate(10);
        $services = Service::all();
        return view('users.index', compact('utilisateurs', 'services'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $services = Service::all();
        return view('users.create', compact('services'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:greffier,greffier_en_chef'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'fname' => $request->fname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'service_id' => $request->service_id,
        ]);

        if ($user) {
            return redirect()->route('users.index')
                ->with('success', 'L\'utilisateur a été créé avec succès.');
        }

        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur.')
            ->withInput();
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur
     */
    public function edit(User $user)
    {
        $services = Service::all();
        return view('users.edit', compact('user', 'services'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:greffier,greffier_en_chef'],
            'service_id' => ['required', 'exists:services,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mise à jour des informations de l'utilisateur
        $userData = [
            'name' => $request->name,
            'fname' => $request->fname,
            'email' => $request->email,
            'role' => $request->role,
            'service_id' => $request->service_id,
        ];

        // Mettre à jour le mot de passe uniquement si fourni
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'L\'utilisateur a été modifié avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Vérification des dossiers associés
        $dossiers = $user->dossiers ?? [];
        if (count($dossiers) > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Impossible de supprimer cet utilisateur car il possède des dossiers.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }
}