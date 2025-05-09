<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du dossier</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .whitespace-pre-line {
            white-space: pre-line;
        }
        .card {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
        }
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        }
        .btn-success {
            background: linear-gradient(135deg, #198754, #157347);
        }
        .btn-warning {
            background: linear-gradient(135deg, #ffc107,rgb(253, 241, 20));
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        .text-muted {
            color: #6c757d !important;
        }
        body {
            background-color: #f5f7fa;
        }
        .detail-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-size: 1rem;
            color: #212529;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3">
                <i class="fas fa-chevron-left me-1"></i> Retour
            </a>
            <h1 class="h4 mb-0 text-dark fw-bold">
                <i class="fas fa-folder-open text-primary me-2"></i>Détails du dossier
            </h1>
        </div>

        <!-- Dossier Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0 text-dark fw-bold">{{ $dossier->titre }}</h2>
                    <span class="badge rounded-pill 
                        @if($dossier->statut === 'En cours') bg-primary @endif
                        @if($dossier->statut === 'Validé') bg-success @endif
                        @if($dossier->statut === 'Rejeté') bg-danger @endif">
                        <i class="fas 
                            @if($dossier->statut === 'En cours') fa-spinner @endif
                            @if($dossier->statut === 'Validé') fa-check-circle @endif
                            @if($dossier->statut === 'Rejeté') fa-times-circle @endif
                            me-1"></i>
                        {{ $dossier->statut }}
                    </span>
                </div>
                <small class="text-muted">
                    <i class="fas fa-hashtag me-1"></i>{{ $dossier->numero_dossier_judiciaire }}
                </small>
            </div>

            <div class="card-body">
                <!-- Détails du dossier -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="detail-label">
                                <i class="fas fa-hashtag me-1"></i>Numéro du dossier
                            </div>
                            <div class="detail-value">
                                {{ $dossier->numero_dossier_judiciaire }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="detail-label">
                                <i class="far fa-calendar-alt me-1"></i>Date de création
                            </div>
                            <div class="detail-value">
                                {{ $dossier->date_creation }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="detail-label">
                                <i class="fas fa-tag me-1"></i>Genre
                            </div>
                            <div class="detail-value">
                                {{ $dossier->genre }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="detail-label">
                        <i class="fas fa-align-left me-1"></i>Contenu
                    </div>
                    <div class="mt-2 text-dark whitespace-pre-line bg-light p-3 rounded">
                        {{ $dossier->contenu }}
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <a href="javascript:history.back()" class="btn btn-warning">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
                <div>
                    @if($dossier->statut !== 'Validé')
                    <a href="{{ route('receptions.create-envoi', $dossier->id) }}" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le dossier
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script pour gérer les problèmes potentiels avec history.back() -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer tous les liens de retour
            var backLinks = document.querySelectorAll('a[href="javascript:history.back()"]');
            
            // Parcourir tous les liens et ajouter un écouteur d'événements
            backLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Si l'historique est vide ou si c'est la première page visitée
                    if (window.history.length <= 1 || document.referrer === '') {
                        // Redirection vers la liste des dossiers (solution de repli)
                        window.location.href = "{{ route('dossiers.mes_dossiers') }}";
                    } else {
                        // Sinon, utiliser l'historique du navigateur
                        window.history.back();
                    }
                });
            });
        });
    </script>
</body>
</html>