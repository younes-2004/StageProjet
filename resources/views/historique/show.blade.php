@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history text-primary me-2"></i>Détails de l'action
                    </h5>
                    <a href="{{ route('historique.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Informations de l'action</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">ID</label>
                                        <p class="fw-medium">{{ $historique->id }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Date</label>
                                        <p class="fw-medium">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $historique->date_action ? $historique->date_action->format('d/m/Y H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Type d'action</label>
                                        <p>
                                            <span class="badge 
                                                @if($historique->action == 'creation') bg-success 
                                                @elseif($historique->action == 'modification') bg-info 
                                                @elseif($historique->action == 'transfert') bg-primary 
                                                @elseif($historique->action == 'validation') bg-warning
                                                @elseif($historique->action == 'archivage') bg-secondary 
                                                @else bg-light text-dark @endif">
                                                {{ ucfirst($historique->action) }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <p class="fw-medium">{{ $historique->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Informations associées</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Utilisateur</label>
                                        <p class="fw-medium">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $historique->user->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Service</label>
                                        <p class="fw-medium">
                                            <i class="fas fa-building me-1"></i>
                                            {{ $historique->service->nom ?? 'N/A' }}
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Dossier</label>
                                        <p class="fw-medium">
                                            <a href="{{ route('dossiers.show', $historique->dossier_id) }}" class="text-decoration-none">
                                                <i class="fas fa-folder me-1"></i>
                                                {{ $historique->dossier->numero_dossier_judiciaire ?? 'N/A' }} - 
                                                {{ $historique->dossier->titre ?? 'N/A' }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Form label styling */
    .form-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    /* Button styling */
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
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
</style>
@endsection