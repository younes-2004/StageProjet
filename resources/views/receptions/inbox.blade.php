<!-- resources/views/receptions/inbox.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes dossiers reçus</h5>
                    
                    <!-- Lien vers les dossiers validés -->
                    <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-primary">Voir les dossiers validés</a>
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
                    
                    <!-- Affichage du nombre de dossiers trouvés pour le débogage -->
                    <div class="alert alert-info mb-3">
                        Nombre de dossiers reçus trouvés : {{ $receptions->count() }}
                    </div>

                    @if($receptions->isEmpty())
                        <div class="alert alert-warning">
                            Vous n'avez reçu aucun dossier pour le moment ou tous vos dossiers ont été validés.
                            <p class="mt-2">Les dossiers validés sont disponibles dans la section <a href="{{ route('receptions.dossiers_valides') }}" class="font-weight-bold">Dossiers validés</a>.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre du dossier</th>
                                        <th>Date de réception</th>
                                        <th>Expéditeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receptions as $reception)
                                        <tr>
                                            <!-- ID de la réception pour débogage -->
                                            <td>{{ $reception->id }}</td>
                                            
                                            <!-- Titre du dossier avec vérification -->
                                            <td>
                                                @if($reception->dossier)
                                                    {{ $reception->dossier->titre }}
                                                @else
                                                    <span class="text-danger">Dossier non trouvé (ID: {{ $reception->dossier_id }})</span>
                                                @endif
                                            </td>

                                            <!-- Date de réception -->
                                            <td>{{ $reception->date_reception ? $reception->date_reception->format('d/m/Y H:i') : 'N/A' }}</td>

                                            <!-- Expéditeur avec vérification -->
                                            <td>
                                                @if($reception->dossier && $reception->dossier->createur)
                                                    {{ $reception->dossier->createur->name }}
                                                @else
                                                    Inconnu
                                                @endif
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group">
                                                    <!-- Vérifier si le dossier existe avant d'afficher le lien -->
                                                    @if($reception->dossier)
                                                        <!-- Consulter -->
                                                        <a href="{{ route('dossiers.show', $reception->dossier_id) }}" class="btn btn-info btn-sm mr-1">
                                                            Consulter
                                                        </a>
                                                        
                                                        <!-- Valider -->
                                                        <form action="{{ route('receptions.valider', $reception->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="commentaire_reception" value="">
                                                            <input type="hidden" name="observations" value="">
                                                            <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                                        </form>
                                                    @else
                                                        <span class="text-danger">Dossier indisponible</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Message explicatif -->
                        <div class="mt-3 alert alert-info">
                            <p><strong>Note:</strong> Les dossiers validés disparaîtront de cette liste et seront disponibles dans la section 
                            <a href="{{ route('receptions.dossiers_valides') }}">Dossiers validés</a>.</p>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $receptions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection