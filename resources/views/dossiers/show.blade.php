<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الملف</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
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
        /* Fix pour les boutons RTL */
        .btn i.me-1, .btn i.me-2 {
            margin-right: 0 !important;
            margin-left: 0.25rem !important;
        }
        .btn i.me-2 {
            margin-left: 0.5rem !important;
        }
        /* Pour corriger l'ordre des icônes */
        .detail-label i.me-1 {
            margin-right: 0 !important;
            margin-left: 0.25rem !important;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm ms-3">
                <i class="fas fa-chevron-right me-1"></i> رجوع
            </a>
            <h1 class="h4 mb-0 text-dark fw-bold">
                <i class="fas fa-folder-open text-primary me-2"></i>تفاصيل الملف
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
                        @if($dossier->statut === 'En cours') قيد التنفيذ @endif
                        @if($dossier->statut === 'Validé') تمت الموافقة @endif
                        @if($dossier->statut === 'Rejeté') مرفوض @endif
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
                                <i class="fas fa-hashtag me-1"></i>رقم الملف
                            </div>
                            <div class="detail-value">
                                {{ $dossier->numero_dossier_judiciaire }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="detail-label">
                                <i class="far fa-calendar-alt me-1"></i>تاريخ الإنشاء
                            </div>
                            <div class="detail-value">
                                {{ $dossier->date_creation }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="detail-label">
                                <i class="fas fa-tag me-1"></i>النوع
                            </div>
                            <div class="detail-value">
                                {{ $dossier->genre }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
    <div class="detail-label">
        <i class="fas fa-align-left me-1"></i>المحتوى
    </div>
    
    @if($dossier->type_contenu === 'texte')
        <!-- Afficher le texte normalement -->
        <div class="mt-2 text-dark whitespace-pre-line bg-light p-3 rounded">
            {{ $dossier->contenu }}
        </div>
    @elseif($dossier->type_contenu === 'pdf')
        <!-- Afficher le lien vers le PDF avec des boutons d'action -->
        <div class="mt-2 bg-light p-3 rounded">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-file-pdf text-danger fs-1"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">ملف PDF</h6>
                    <p class="text-muted mb-2 small">
                        {{ basename($dossier->contenu) }}
                    </p>
                    <div>
                        <a href="{{ asset('storage/' . $dossier->contenu) }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="fas fa-eye me-1"></i> عرض الملف
                        </a>
                        <a href="{{ asset('storage/' . $dossier->contenu) }}" class="btn btn-sm btn-secondary me-2" download>
                            <i class="fas fa-download me-1"></i> تحميل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

            <!-- Boutons d'action -->
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <a href="javascript:history.back()" class="btn btn-warning">
                        <i class="fas fa-arrow-right me-2"></i>العودة إلى القائمة
                    </a>
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