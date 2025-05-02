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
                        <div class="alert alert-info">
                            Vous n'avez reçu aucun dossier pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre du dossier</th>
                                        <th>Service ID</th>
                                        <th>Utilisateur expéditeur</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receptions as $reception)
                                        @if(!$reception->traite)
                                        <tr>
                                            <!-- Titre du dossier -->
                                            <td>{{ $reception->dossier->titre }}</td>

                                            <!-- Service ID -->
                                            <td>{{ $reception->dossier->service_id }}</td>

                                            <!-- Utilisateur expéditeur -->
                                            <td>
                                                @if($reception->dossier->createur)
                                                    {{ $reception->dossier->createur->name }}
                                                @else
                                                    Inconnu
                                                @endif
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td>
                                                <form action="{{ route('receptions.valider', $reception->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="commentaire_reception" value="">
                                                    <input type="hidden" name="observations" value="">
                                                    <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                                </form>
                                            </td>
                                        </tr>
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