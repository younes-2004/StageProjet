// resources/views/dossiers/detail.blade.php

@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Informations du dossier -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-folder-open text-primary me-2"></i>{{ $dossier->titre }}
                    </h5>
                    <span class="badge 
                        @if($dossier->statut == 'Créé') bg-info 
                        @elseif($dossier->statut == 'Validé') bg-success 
                        @elseif($dossier->statut == 'En attente') bg-warning 
                        @elseif($dossier->statut == 'Archivé') bg-secondary 
                        @else bg-light text-dark @endif">
                        {{ $dossier->statut }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Numéro du dossier judiciaire</h6>
                            <p class="fs-5">{{ $dossier->numero_dossier_judiciaire }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Genre</h6>
                            <p class="fs-5">{{ $dossier->genre }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Contenu</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $dossier->contenu }}
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Date de création</h6>
                            <p><i class="far fa-calendar-alt me-1"></i> {{ $dossier->date_creation ? $dossier->date_creation->format('d/m/Y') : ($dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Créé par</h6>
                            <p><i class="far fa-user me-1"></i> {{ $dossier->createur->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Service d'origine</h6>
                            <p><i class="far fa-building me-1"></i> {{ $dossier->service->nom ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Détenteur actuel</h6>
                            <p><i class="far fa-user-circle me-1"></i> {{ $dossier->detenteurActuel()->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Service actuel</h6>
                            <p><i class="far fa-building me-1"></i> {{ $dossier->serviceActuel()->nom ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">Temps de traitement</h6>
                            <p><i class="far fa-clock me-1"></i> {{ $dossier->tempsTraitement() }} jours</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <div>
                            @if($dossier->detenteurActuel()->id == auth()->id())
                                <a href="{{ route('receptions.create-envoi', $dossier->id) }}" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i> Envoyer
                                </a>
                                
                                @if($dossier->statut != 'Archivé')
                                    <form action="{{ route('dossiers.archiver', $dossier->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary ms-2">
                                            <i class="fas fa-archive me-1"></i> Archiver
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations supplémentaires -->
        <div class="col-md-4">
            <!-- Détails du détenteur actuel -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user text-primary me-2"></i>Détenteur actuel
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $dossier->detenteurActuel()->name ?? 'N/A' }}</h6>
                            <p class="text-muted mb-0">{{ $dossier->detenteurActuel()->email ?? '' }}</p>
                            <p class="text-muted mb-0">{{ $dossier->detenteurActuel()->role == 'greffier_en_chef' ? 'Greffier en chef' : 'Greffier' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historique des actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-history text-primary me-2"></i>Historique des actions
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="timeline p-3">
                        @foreach($dossier->historiqueComplet() as $action)
                        <div class="timeline-item pb-3">
                            <div class="timeline-badge 
                                @if($action->action == 'creation') bg-success
                                @elseif($action->action == 'transfert') bg-info
                                @elseif($action->action == 'validation') bg-primary
                                @elseif($action->action == 'reassignation') bg-warning
                                @elseif($action->action == 'archivage') bg-secondary
                                @else bg-light @endif">
                                <i class="fas 
                                    @if($action->action == 'creation') fa-plus
                                    @elseif($action->action == 'transfert') fa-paper-plane
                                    @elseif($action->action == 'validation') fa-check
                                    @elseif($action->action == 'reassignation') fa-exchange-alt
                                    @elseif($action->action == 'archivage') fa-archive
                                    @else fa-cog @endif text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">{{ ucfirst($action->action) }}</h6>
                                <p class="text-muted mb-1">{{ $action->date_action->format('d/m/Y H:i') }}</p>
                                <p class="mb-0">{{ $action->description }}</p>
                                <div class="text-muted small">
                                    <span><i class="fas fa-user me-1"></i> {{ $action->user->name }}</span>
                                    <span class="ms-2"><i class="fas fa-building me-1"></i> {{ $action->service->nom }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style pour la timeline */
    .timeline {
        position: relative;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .timeline-item {
        position: relative;
        margin-left: 30px;
        border-left: 2px solid #dee2e6;
        padding-left: 20px;
    }
    
    .timeline-item:last-child {
        border-left: none;
    }
    
    .timeline-badge {
        position: absolute;
        left: -11px;
        top: 0;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        text-align: center;
        line-height: 22px;
        font-size: 0.7rem;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 10px;
        margin-bottom: 15px;
    }
</style>
@endsection