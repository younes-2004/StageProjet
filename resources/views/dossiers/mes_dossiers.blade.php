@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Mes Dossiers</h1>
        <div class="space-x-4">
            <a href="{{ route('receptions.inbox') }}" class="bg-blue-500 hover:bg-blue-600 text-black py-2 px-4 rounded">
                <i class="fas fa-inbox mr-2"></i>Boîte de réception
            </a>
        </div>
    </div>

    <!-- Test : Afficher les données reçues -->
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
        <p><strong>Nombre de dossiers :</strong> {{ $dossiers->count() }}</p>
    </div>

    <!-- Affichage du message de succès -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tableau des dossiers -->
    @if($dossiers->isEmpty())
        <p>Aucun dossier trouvé.</p>
    @else
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Numéro</th>
                    <th class="border border-gray-300 px-4 py-2">Titre</th>
                    <th class="border border-gray-300 px-4 py-2">Date de création</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dossiers as $dossier)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->numero_dossier_judiciaire }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->titre }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->date_creation }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                        <a href="{{ route('dossiers.show', $dossier->id) }}" class="text-blue-500 hover:underline">Voir</a>
                        <a href="{{ route('receptions.create-envoi', $dossier->id) }}" class="text-green-500 hover:underline">Envoyer</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection