<!-- resources/views/receptions/dossiers_valides.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dossiers validés</h1>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($dossiersValides->isEmpty())
        <div class="alert alert-info">
            Aucun dossier validé pour le moment.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date de validation</th>
                        <th>Service ID</th>
                        <th>Utilisateur expéditeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dossiersValides as $dossierValide)
                        <tr>
                            <td>{{ $dossierValide->dossier->titre }}</td>
                            <td>{{ $dossierValide->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $dossierValide->dossier->service_id }}</td>
                            <td>{{ $dossierValide->dossier->createur->name ?? 'Inconnu' }}</td>
                            <td>
                                <!-- Bouton Réaffecter -->
                                <a href="{{ route('receptions.reaffecter', $dossierValide->dossier->id) }}" class="btn btn-sm btn-primary">Réaffecter</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $dossiersValides->links() }}
        </div>
    @endif
</div>
@endsection