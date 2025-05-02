<!-- filepath: c:\Users\RPC\Stageprojet\resources\views\dossiers\envoyer.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Envoyer le Dossier : {{ $dossier->titre }}</h1>

    <form method="GET" action="{{ route('dossiers.envoyer', $dossier->id) }}">
        <!-- Sélection du service -->
        <div class="mb-4">
            <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
            <select id="service_id" name="service_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Choisir un service --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
<!-- Remplacez le formulaire final par : -->
    @if(request('service_id'))
<form method="POST" action="{{ route('dossiers.traiter_envoi', $dossier->id) }}">
    @csrf
    <input type="hidden" name="service_id" value="{{ request('service_id') }}">

    <!-- Sélection de l'utilisateur -->
    <div class="mb-4">
        <label for="user_id" class="block text-sm font-medium text-gray-700">Utilisateur</label>
        <select id="user_id" name="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- Choisir un utilisateur --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Commentaire -->
    <div class="mb-4">
        <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire</label>
        <textarea id="commentaire" name="commentaire" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <!-- Bouton d'envoi -->
    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
        Envoyer
    </button>
</form>
@endif
   
</div>
@endsection