<!-- resources/views/receptions/dossiers_valides.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-check-circle text-success me-2"></i>Mes dossiers validés
                    </h5>
                    
                    <!-- Lien vers la boîte de réception -->
                    <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">
                        <i class="fas fa-inbox me-1"></i> Retour à la boîte de réception
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Nombre de dossiers validés trouvés : {{ $dossiersValides->count() }}
                    </div>

                    @if($dossiersValides->isEmpty())
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-check-double fa-2x mb-3 text-info"></i>
                            <h5 class="alert-heading">Aucun dossier validé</h5>
                            <p class="mb-0">Vous n'avez validé aucun dossier pour le moment.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-semibold">Titre du dossier</th>
                                        <th class="fw-semibold">Date de validation</th>
                                        <th class="fw-semibold">Service</th>
                                        <th class="fw-semibold">Expéditeur</th>
                                        <th class="fw-semibold text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dossiersValides as $dossierValide)
                                        <tr class="border-top border-light">
                                            <!-- Titre du dossier -->
                                            <td>
                                                <span class="fw-medium">{{ $dossierValide->dossier->titre }}</span>
                                            </td>
                                            
                                            <!-- Date de validation -->
                                            <td class="text-muted">
                                                <i class="far fa-calendar-check me-1"></i>
                                                {{ $dossierValide->date_validation ? $dossierValide->date_validation->format('d/m/Y H:i') : 'N/A' }}
                                            </td>
                                            
                                            <!-- Service -->
                                            <td>
                                                <i class="fas fa-building me-1 text-secondary"></i>
                                                @if($dossierValide->dossier->service)
                                                    {{ $dossierValide->dossier->service->nom }}
                                                @else
                                                    Service ID: {{ $dossierValide->dossier->service_id }}
                                                @endif
                                            </td>
                                            
                                            <!-- Expéditeur -->
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    <i class="fas fa-user-circle me-1"></i>
                                                    {{ $dossierValide->dossier->createur->name ?? 'Inconnu' }}
                                                </span>
                                            </td>
                                            
                                            <!-- Actions -->
                                          <!-- Actions -->
<td class="text-end" style="white-space: nowrap; min-width: 230px;">
    <div style="display: flex; justify-content: flex-end; gap: 4px; flex-wrap: nowrap;">
        <!-- Bouton pour consulter les détails -->
        <a href="{{ route('dossiers.show', $dossierValide->dossier_id) }}" 
           class="btn btn-sm btn-primary px-2 py-1">
           <i class="fas fa-eye me-1"></i> Consulter
        </a>
        
        <!-- Bouton pour réaffecter -->
        <a href="{{ route('receptions.reaffecter', $dossierValide->dossier_id) }}" 
           class="btn btn-sm btn-warning px-2 py-1">
           <i class="fas fa-share me-1"></i> Réaffecter
        </a>
        
        <!-- Bouton pour archiver -->
        <form action="{{ route('dossiers.archiver', $dossierValide->dossier_id) }}" 
              method="POST" 
              style="margin: 0;" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir archiver ce dossier?');">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-secondary px-2 py-1">
                <i class="fas fa-archive me-1"></i> Archiver
            </button>
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

<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-color: rgba(0, 0, 0, 0.03);
    }
    
    .btn {
        font-weight: 500;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-radius: 0.375rem;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #198754, #157347);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border: none;
        border-radius: 0.5rem;
    }
    
    .pagination .page-link {
        border: none;
        color: #6c757d;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .table {
        width: 100% !important;
        table-layout: fixed;
    }
</style>
@endsection