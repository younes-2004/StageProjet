<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <!-- Ajoutez le bouton ici -->
        <a href="{{ route('dossiers.create') }}" class="btn btn-primary">Créer un nouveau dossier</a>
        <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-primary">Consulter mes dossiers</a>
        <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-primary">Voir les dossiers validés</a>
          <!-- Lien vers la boîte de réception -->
          <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">
                        Voir mes dossiers reçus
                    </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
