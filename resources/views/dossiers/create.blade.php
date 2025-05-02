@extends('layouts.app')


@section('content')
<div class="container mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center mb-6">Créer un nouveau dossier</h1>

    <form method="POST" action="{{ route('dossiers.store') }}">
        @csrf

        <div class="mb-4">
            <label for="numero_dossier_judiciaire" class="block text-sm font-medium text-gray-700">Numéro du dossier judiciaire</label>
            <input id="numero_dossier_judiciaire" type="text" name="numero_dossier_judiciaire" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
            <input id="titre" type="text" name="titre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
            <textarea id="contenu" name="contenu" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <div class="mb-4">
            <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
            <input id="genre" type="text" name="genre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-black py-2 px-4 rounded-md hover:bg-blue-600">Ajouter</button>
    </form>
</div>
@endsection