<!-- filepath: c:\Users\RPC\Stageprojet\resources\views\dossiers\show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Détails du Dossier</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <p><strong>Numéro :</strong> {{ $dossier->numero_dossier_judiciaire }}</p>
        <p><strong>Titre :</strong> {{ $dossier->titre }}</p>
        <p><strong>Contenu :</strong> {{ $dossier->contenu }}</p>
        <p><strong>Date de création :</strong> {{ $dossier->date_creation }}</p>
        <p><strong>Statut :</strong> {{ $dossier->statut }}</p>
        <p><strong>Genre :</strong> {{ $dossier->genre }}</p>
    </div>

    <a href="{{ route('dossiers.mes_dossiers') }}" class="text-blue-500 hover:underline">Retour à la liste des dossiers</a>
</div>
@endsection