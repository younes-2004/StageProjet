<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dossier;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:greffier_en_chef');
    }

    // Tableau de bord admin
    public function index()
    {
        $dossiers = Dossier::all();
        $utilisateurs = User::all();
        return view('admin.dashboard', compact('dossiers', 'utilisateurs'));
    }

    // Gestion des utilisateurs
    public function gestionUtilisateurs()
    {
        $utilisateurs = User::where('role', '!=', 'greffier_en_chef')->get();
        return view('admin.utilisateurs', compact('utilisateurs'));
    }
}