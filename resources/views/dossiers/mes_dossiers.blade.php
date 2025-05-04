@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Mes Dossiers</h1>
        <div class="space-x-4">
            <a href="{{ route('receptions.inbox') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <i class="fas fa-inbox mr-2"></i>Boîte de réception
            </a>
        </div>
    </div>

    <!-- Tableau des dossiers actifs (non validés) -->
    <h2 class="text-xl font-semibold mb-4">Mes dossiers actifs</h2>
    @if($dossiers->isEmpty())
        <p class="mb-6 text-gray-600">Tous vos dossiers ont été validés.</p>
    @else
        <table class="table-auto w-full border-collapse border border-gray-300 mb-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Numéro</th>
                    <th class="border border-gray-300 px-4 py-2">Titre</th>
                    <th class="border border-gray-300 px-4 py-2">Date de création</th>
                    <th class="border border-gray-300 px-4 py-2">Statut</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dossiers as $dossier)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->numero_dossier_judiciaire }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->titre }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $dossier->date_creation }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @php
                            $transfert = \App\Models\Transfert::where('dossier_id', $dossier->id)
                                ->where('user_source_id', auth()->id())
                                ->latest()
                                ->first();
                        @endphp
                        
                        @if($transfert)
                            <span class="px-2 py-1 rounded text-white bg-blue-500">
                                Envoyé (En attente de validation)
                            </span>
                        @else
                            <span class="px-2 py-1 rounded text-white bg-gray-500">
                                Non envoyé
                            </span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                        <a href="{{ route('dossiers.show', $dossier->id) }}" class="text-blue-500 hover:underline">Voir</a>
                        @if(!$transfert)
                            <a href="{{ route('receptions.create-envoi', $dossier->id) }}" class="text-green-500 hover:underline">Envoyer</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Tableau des dossiers validés (historique) -->
    <h2 class="text-xl font-semibold mb-4">Historique des dossiers validés</h2>
    @if(!isset($dossiersEnvoyes) || $dossiersEnvoyes->isEmpty())
        <p class="text-gray-600">Aucun dossier validé.</p>
    @else
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Numéro Dossier</th>
                    <th class="border border-gray-300 px-4 py-2">Titre</th>
                    <th class="border border-gray-300 px-4 py-2">Validé par</th>
                    <th class="border border-gray-300 px-4 py-2">Service</th>
                    <th class="border border-gray-300 px-4 py-2">Date Envoi</th>
                    <th class="border border-gray-300 px-4 py-2">Date Validation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dossiersEnvoyes as $transfert)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transfert->dossier->numero_dossier_judiciaire }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transfert->dossier->titre }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transfert->userDestination->name ?? 'N/A' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transfert->serviceDestination->nom ?? 'N/A' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <span class="text-green-600 font-semibold">
                            {{ $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'Non validé' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection