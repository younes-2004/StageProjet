@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-folder-plus text-primary me-2"></i>Créer un nouveau dossier
                    </h5>
                    
                    <!-- Lien de retour -->
                    <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i> Retour aux dossiers
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <!-- Nouvelle carte de confirmation -->
                        <div class="confirmation-card mb-4">
                            <div class="card border-0 bg-success-subtle">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="confirmation-icon bg-success text-white me-3">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="confirmation-content">
                                        <h5 class="mb-1 fw-bold">Dossier créé avec succès!</h5>
                                        <p class="mb-0">{{ session('success') }}</p>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="{{ route('dossiers.show', session('dossier_id')) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye me-1"></i> Voir le dossier
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('dossiers.store') }}" class="mt-2">
                        @csrf
                        
                        <!-- Le reste du formulaire reste inchangé -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_dossier_judiciaire" class="form-label fw-medium">
                                        <i class="fas fa-hashtag text-secondary me-1"></i>
                                        Numéro du dossier judiciaire
                                    </label>
                                    <input 
                                        id="numero_dossier_judiciaire" 
                                        type="text" 
                                        name="numero_dossier_judiciaire" 
                                        required 
                                        class="form-control rounded-3 border-light shadow-sm"
                                        placeholder="Entrez le numéro du dossier">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genre" class="form-label fw-medium">
                                        <i class="fas fa-tag text-secondary me-1"></i>
                                        Genre
                                    </label>
                                    <input 
                                        id="genre" 
                                        type="text" 
                                        name="genre" 
                                        required 
                                        class="form-control rounded-3 border-light shadow-sm"
                                        placeholder="Entrez le genre du dossier">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label fw-medium">
                                <i class="fas fa-heading text-secondary me-1"></i>
                                Titre
                            </label>
                            <input 
                                id="titre" 
                                type="text" 
                                name="titre" 
                                required 
                                class="form-control rounded-3 border-light shadow-sm"
                                placeholder="Entrez le titre du dossier">
                        </div>
                        
                        <div class="mb-4">
                            <label for="contenu" class="form-label fw-medium">
                                <i class="fas fa-file-alt text-secondary me-1"></i>
                                Contenu
                            </label>
                            <textarea 
                                id="contenu" 
                                name="contenu" 
                                rows="5" 
                                required 
                                class="form-control rounded-3 border-light shadow-sm"
                                placeholder="Entrez le contenu du dossier"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style cohérent avec les autres pages */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    .form-control {
        padding: 0.6rem 0.75rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .btn {
        font-weight: 500;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-radius: 0.375rem;
        padding: 0.425rem 0.75rem;
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
    
    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    /* Styles pour la carte de confirmation */
    .confirmation-card .card {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .confirmation-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .confirmation-content h5 {
        color: #198754;
    }
    
    .confirmation-content p {
        color: #495057;
    }
    
    /* Animation d'entrée */
    @keyframes slideIn {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .confirmation-card {
        animation: slideIn 0.3s ease forwards;
    }
    
    /* Ajustements responsifs */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .d-md-flex {
            display: grid;
            gap: 0.5rem;
        }
        
        .btn {
            width: 100%;
        }
        
        .confirmation-card .card-body {
            flex-direction: column;
            text-align: center;
        }
        
        .confirmation-icon {
            margin: 0 0 10px 0;
        }
        
        .confirmation-card .ms-auto {
            margin: 15px 0 0 0 !important;
            width: 100%;
        }
        
        .confirmation-card .ms-auto .btn {
            width: 100%;
        }
    }
</style>
@endsection