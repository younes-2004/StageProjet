@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>Détails du transfert
                    </h5>
                    <a href="{{ route('transferts.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Informations principales du transfert -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Informations du transfert</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">ID du transfert</label>
                                        <p class="fw-medium">{{ $transfert->id }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Statut</label>
                                        <p>
                                            <span class="badge 
                                                @if($transfert->statut == 'envoyé') bg-info 
                                                @elseif($transfert->statut == 'reçu') bg-primary 
                                                @elseif($transfert->statut == 'validé') bg-success 
                                                @elseif($transfert->statut == 'refusé') bg-danger
                                                @elseif($transfert->statut == 'réaffectation') bg-warning 
                                                @else bg-secondary @endif">
                                                {{ ucfirst($transfert->statut) }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Date d'envoi</label>
                                        <p>
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Date de réception</label>
                                        <p>
                                            @if($transfert->date_reception)
                                                <span class="text-success">
                                                    <i class="far fa-calendar-check me-1"></i>
                                                    {{ $transfert->date_reception->format('d/m/Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    En attente
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Commentaire</label>
                                        <p class="fst-italic text-muted">
                                            @if($transfert->commentaire)
                                                <i class="fas fa-comment me-1"></i>
                                                {{ $transfert->commentaire }}
                                            @else
                                                Aucun commentaire
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations du dossier -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Informations du dossier</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Numéro</label>
                                        <p>
                                            <a href="{{ route('dossiers.show', $transfert->dossier_id) }}" class="text-decoration-none">
                                                <i class="fas fa-hashtag me-1"></i>
                                                {{ $transfert->dossier->numero_dossier_judiciaire ?? 'N/A' }}
                                            </a>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Titre</label>
                                        <p>{{ $transfert->dossier->titre ?? 'N/A' }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Genre</label>
                                        <p>{{ $transfert->dossier->genre ?? 'N/A' }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Date de création</label>
                                        <p>
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $transfert->dossier->date_creation ? $transfert->dossier->date_creation->format('d/m/Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations des utilisateurs et services -->
                    <div class="row mb-4">
                        <!-- Source -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Source du transfert</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Utilisateur source</label>
                                        <p>
                                            <i class="fas fa-user me-1"></i>
                                            {{ $transfert->userSource->name ?? 'N/A' }}
                                            ({{ $transfert->userSource->email ?? 'N/A' }})
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Service source</label>
                                        <p>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $transfert->serviceSource->nom ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Destination -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Destination du transfert</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Utilisateur destinataire</label>
                                        <p>
                                            <i class="fas fa-user me-1"></i>
                                            {{ $transfert->userDestination->name ?? 'N/A' }}
                                            ({{ $transfert->userDestination->email ?? 'N/A' }})
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Service destination</label>
                                        <p>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $transfert->serviceDestination->nom ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('dossiers.show', $transfert->dossier_id) }}" class="btn btn-primary">
                                            <i class="fas fa-file-alt me-1"></i> Consulter le dossier
                                        </a>
                                        
                                        @if($transfert->statut === 'envoyé')
                                            <form action="{{ route('receptions.valider', ['id' => $transfert->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check me-1"></i> Valider le transfert
                                                </button>
                                            </form>
                                        @endif
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
    
    .btn-success {
        background: linear-gradient(135deg, #198754, #157347);
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
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>
@endsection