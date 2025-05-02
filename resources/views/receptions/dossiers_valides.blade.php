<!-- resources/views/receptions/dossiers_valides.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes dossiers validés</h5>
                    
                    <!-- Lien vers la boîte de réception -->
                    <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">Retour à la boîte de réception</a>
                </div>

                <div class="card-body">
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
                            Vous n'avez validé aucun dossier pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre du dossier</th>
                                        <th>Date de validation</th>
                                        <th>Service</th>
                                        <th>Expéditeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiersValides as $dossierValide)
                                        <tr>
                                            <!-- Titre du dossier -->
                                            <td>{{ $dossierValide->dossier->titre }}</td>
                                            
                                            <!-- Date de validation -->
                                            <td>{{ $dossierValide->date_validation ? $dossierValide->date_validation->format('d/m/Y H:i') : 'N/A' }}</td>
                                            
                                            <!-- Service -->
                                            <td>
                                                @if($dossierValide->dossier->service)
                                                    {{ $dossierValide->dossier->service->nom }}
                                                @else
                                                    Service ID: {{ $dossierValide->dossier->service_id }}
                                                @endif
                                            </td>
                                            
                                            <!-- Expéditeur -->
                                            <td>
                                                @if($dossierValide->dossier->createur)
                                                    {{ $dossierValide->dossier->createur->name }}
                                                @else
                                                    Inconnu
                                                @endif
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group">
                                                    <!-- Bouton pour consulter les détails -->
                                                    <a href="{{ route('dossiers.show', $dossierValide->dossier_id) }}" class="btn btn-info btn-sm mr-1">
                                                        Consulter
                                                    </a>
                                                    
                                                    <!-- Bouton pour réaffecter -->
                                                    <a href="{{ route('receptions.reaffecter', $dossierValide->dossier_id) }}" class="btn btn-warning btn-sm mr-1">
                                                        Réaffecter
                                                    </a>
                                                    
                                                    <!-- Bouton pour archiver -->
                                                    <form action="{{ route('dossiers.archiver', $dossierValide->dossier_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir archiver ce dossier?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-secondary btn-sm">Archiver</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $dossiersValides->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection