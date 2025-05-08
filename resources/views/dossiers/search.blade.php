@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-search text-primary me-2"></i>Recherche avancée de dossiers
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dossiers.search') }}" method="GET" id="searchForm">
                        <div class="row mb-3">
                            <!-- Recherche par mot-clé -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keyword" class="form-label">Mot-clé</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="keyword" name="keyword" 
                                               value="{{ request('keyword') }}" placeholder="Rechercher par titre, contenu ou numéro...">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filtrage par statut -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="">Tous les statuts</option>
                                        @foreach($statuts as $statut)
                                            <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                                                {{ $statut }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Filtrage par genre -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="genre" class="form-label">Genre</label>
                                    <select class="form-select" id="genre" name="genre">
                                        <option value="">Tous les genres</option>
                                        @foreach($genres as $genre)
                                            <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                                                {{ $genre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <!-- Filtrage par service -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="service_id" class="form-label">Service</label>
                                    <select class="form-select" id="service_id" name="service_id">
                                        <option value="">Tous les services</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Filtrage par créateur -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="createur_id" class="form-label">Créateur</label>
                                    <select class="form-select" id="createur_id" name="createur_id">
                                        <option value="">Tous les créateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('createur_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} {{ $user->fname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Filtrage par date de début -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                           value="{{ request('date_debut') }}">
                                </div>
                            </div>
                            
                            <!-- Filtrage par date de fin -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                           value="{{ request('date_fin') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <!-- Options de tri -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_by" class="form-label">Trier par</label>
                                    <select class="form-select" id="sort_by" name="sort_by">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                                        <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>Titre</option>
                                        <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}>Statut</option>
                                        <option value="numero_dossier_judiciaire" {{ request('sort_by') == 'numero_dossier_judiciaire' ? 'selected' : '' }}>Numéro de dossier</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_direction" class="form-label">Ordre</label>
                                    <select class="form-select" id="sort_direction" name="sort_direction">
                                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-1"></i> Rechercher
                                    </button>
                                    <button type="button" id="resetButton" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-redo me-1"></i> Réinitialiser
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Résultats de recherche -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-list text-primary me-2"></i>Résultats de la recherche
                        <span class="badge bg-primary ms-2">{{ $dossiers->total() }} dossier(s)</span>
                    </h5>
                    
                    @if($dossiers->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-1"></i> Exporter
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item" href="{{ route('dossiers.export', ['format' => 'csv'] + request()->all()) }}">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('dossiers.export', ['format' => 'xlsx'] + request()->all()) }}">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('dossiers.export', ['format' => 'pdf'] + request()->all()) }}">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                @if($dossiers->count() > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Numéro</th>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Genre</th>
                                    <th>Créateur</th>
                                    <th>Service</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->numero_dossier_judiciaire }}</td>
                                    <td>{{ $dossier->titre }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($dossier->statut == 'Créé') bg-info 
                                            @elseif($dossier->statut == 'Validé') bg-success 
                                            @elseif($dossier->statut == 'En attente') bg-warning 
                                            @elseif($dossier->statut == 'Archivé') bg-secondary 
                                            @else bg-light text-dark @endif">
                                            {{ $dossier->statut }}
                                        </span>
                                    </td>
                                    <td>{{ $dossier->genre }}</td>
                                    <td>{{ $dossier->createur->name ?? 'N/A' }}</td>
                                    <td>{{ $dossier->service->nom ?? 'N/A' }}</td>
                                    <td>{{ $dossier->date_creation ? $dossier->date_creation->format('d/m/Y') : ($dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                             <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-eye"></i>consulter
        </a>
        <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i>modifier
        </a>
                                         
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $dossiers->links() }}
                    </div>
                </div>
                @else
                <div class="card-body text-center p-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Aucun dossier trouvé</h5>
                    <p class="text-muted">Modifiez vos critères de recherche pour trouver des dossiers</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Réinitialiser le formulaire
    document.getElementById('resetButton').addEventListener('click', function() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.value = '';
        });
        
        form.submit();
    });
});
</script>
@endsection