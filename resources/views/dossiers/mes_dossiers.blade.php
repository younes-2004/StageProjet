@extends('layouts.app')
@section('content')


<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark">ملفاتي</h1>
            <p class="text-muted mb-0">إدارة كاملة لملفاتك القضائية</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">
                <i class="fas fa-inbox me-2"></i>صندوق الوارد
                <span class="badge bg-danger ms-2">
                                    {{ \App\Models\Reception::where('user_id', Auth::id())->where('traite', false)->count() }}
                                </span>
            </a>
        </div>
    </div>

    <!-- Non Envoyé Dossiers Section -->
    @php
        $nonEnvoyes = $dossiers->filter(function($dossier) {
            $transfert = \App\Models\Transfert::where('dossier_id', $dossier->id)
                ->where('user_source_id', auth()->id())
                ->latest()
                ->first();
            return !$transfert;
        });
    @endphp

    @if($nonEnvoyes->isNotEmpty())
    <div class="mb-5">
        <div class="mb-3">
            <h2 class="h4 fw-bold text-dark border-left border-dark py-1" style="border-left: 4px solid #212529 !important; padding-left: 10px;">
                <i class="fas fa-edit me-2 text-secondary"></i>
                الملفات غير المرسلة
            </h2>
            <p class="text-muted ms-4">ملفات قيد الإعداد</p>
        </div>

        <!-- Barre de recherche pour dossiers non envoyés -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                          
                            <input type="text" class="form-control border-start-0 search-input" id="searchNonEnvoyes" placeholder="بحث...">
                              <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="all" data-target="nonEnvoyes">
                                <i class="fas fa-list-ul me-1"></i> الكل
                            </button>
                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="titre" data-target="nonEnvoyes">
                                <i class="fas fa-heading me-1"></i> العنوان
                            </button>
                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="numero" data-target="nonEnvoyes">
                                <i class="fas fa-hashtag me-1"></i> الرقم
                            </button>
                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="date" data-target="nonEnvoyes">
                                <i class="fas fa-calendar-alt me-1"></i> التاريخ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tableNonEnvoyes">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 15%;">الرقم</th>
                            <th style="width: 25%;">العنوان</th>
                            <th style="width: 20%;">تاريخ الإنشاء</th>
                            <th style="width: 15%;">الحالة</th>
                            <th style="width: 25%;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nonEnvoyes as $dossier)
                        <tr>
                            <td>
                                <span class="d-inline-block rounded-circle bg-secondary me-2" style="width:10px;height:10px"></span>
                                {{ $dossier->numero_dossier_judiciaire }}
                            </td>
                            <td>{{ $dossier->titre }}</td>
                            <td class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $dossier->created_at ? $dossier->created_at->format('d/m/Y H:i') : ($dossier->date_creation ? $dossier->date_creation->format('d/m/Y H:i') : 'N/A') }}
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark">
                                    <i class="fas fa-edit me-1 text-dark"></i> غير مرسل
                                </span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('dossiers.show', $dossier->id) }}" 
                                       class="btn btn-primary btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i> عرض
                                    </a>
                                    <a href="{{ route('receptions.create-envoi', $dossier->id) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-paper-plane me-1"></i> إرسال
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- En Attente Dossiers Section -->
    @php
        $enAttente = $dossiers->filter(function($dossier) {
            $transfert = \App\Models\Transfert::where('dossier_id', $dossier->id)
                ->where('user_source_id', auth()->id())
                ->latest()
                ->first();
            return $transfert && !$transfert->date_reception;
        });
    @endphp

    @if($enAttente->isNotEmpty())
    <div class="mb-5">
        <div class="mb-3">
            <h2 class="h4 fw-bold text-dark border-left border-warning py-1" style="border-left: 4px solid #ffc107 !important; padding-left: 10px;">
                <i class="fas fa-clock me-2 text-warning"></i>
                الملفات قيد الانتظار
            </h2>
            <p class="text-muted ms-4">ملفات مرسلة في انتظار التحقق</p>
        </div>

        <!-- Barre de recherche pour dossiers en attente -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                           
                            <input type="text" class="form-control border-start-0 search-input" id="searchEnAttente" placeholder="بحث..."> <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="all" data-target="enAttente">
                                <i class="fas fa-list-ul me-1"></i> الكل
                            </button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="titre" data-target="enAttente">
                                <i class="fas fa-heading me-1"></i> العنوان
                            </button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="numero" data-target="enAttente">
                                <i class="fas fa-hashtag me-1"></i> الرقم
                            </button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="destinataire" data-target="enAttente">
                                <i class="fas fa-user me-1"></i> المستلم
                            </button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="date" data-target="enAttente">
                                <i class="fas fa-calendar-alt me-1"></i> التاريخ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tableEnAttente">
                    <thead class="bg-warning-light">
                        <tr>
                            <th style="width: 15%;">الرقم</th>
                            <th style="width: 25%;">العنوان</th>
                            <th style="width: 20%;">المستلم</th>
                            <th style="width: 20%;">تاريخ الإرسال</th>
                            <th style="width: 20%;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enAttente as $dossier)
                        @php
                            $transfert = \App\Models\Transfert::where('dossier_id', $dossier->id)
                                ->where('user_source_id', auth()->id())
                                ->latest()
                                ->first();
                        @endphp
                        <tr class="bg-warning-light">
                            <td>
                                <span class="d-inline-block rounded-circle bg-warning me-2" style="width:10px;height:10px"></span>
                                {{ $dossier->numero_dossier_judiciaire }}
                            </td>
                            <td>{{ $dossier->titre }}</td>
                            <td>{{ $transfert->userDestination->name ?? 'N/A' }}</td>
                            <td class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('dossiers.show', $dossier->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($nonEnvoyes->isEmpty() && $enAttente->isEmpty())
    <div class="alert alert-info text-center mb-5">
        <i class="fas fa-check-circle fa-3x text-info mb-3"></i>
        <h4 class="alert-heading">تم التحقق من جميع ملفاتك</h4>
        <p class="mb-0">لا توجد ملفات في انتظار المعالجة</p>
    </div>
    @endif

 <!-- History Dossiers Section -->
<div class="mb-4">
    <div class="mb-3">
        <h2 class="h4 fw-bold text-dark border-left border-success py-1" style="border-left: 4px solid #198754 !important; padding-left: 10px;">
            <i class="fas fa-archive me-2 text-success"></i>
            سجل الملفات المصادق عليها
        </h2>
        <p class="text-muted ms-4">الملفات المنقولة والمصادق عليها</p>
    </div>
    @php
        // Get the data directly in the view for testing purposes
        $directDossiersEnvoyes = \App\Models\Transfert::where('user_source_id', auth()->id())
            ->whereNotNull('date_reception')
            ->with(['dossier', 'userDestination', 'serviceDestination'])
            ->orderBy('date_reception', 'desc')
            ->get();
    @endphp

    @if($directDossiersEnvoyes->isEmpty())
        <div class="alert alert-success text-center">
            <i class="fas fa-history fa-3x text-success mb-3"></i>
            <h4 class="alert-heading">لا توجد ملفات مصادق عليها حاليًا</h4>
            <p class="mb-0">سيظهر السجل هنا بعد المصادقة على الملفات</p>
        </div>
    @else
        <!-- Barre de recherche pour l'historique -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                           
                            <input type="text" class="form-control border-start-0 search-input" id="searchHistorique" placeholder="بحث..."> <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="all" data-target="historique">
                                <i class="fas fa-list-ul me-1"></i> الكل
                            </button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="titre" data-target="historique">
                                <i class="fas fa-heading me-1"></i> العنوان
                            </button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="numero" data-target="historique">
                                <i class="fas fa-hashtag me-1"></i> الرقم
                            </button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="validateur" data-target="historique">
                                <i class="fas fa-user-check me-1"></i> صادق عليه
                            </button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="service" data-target="historique">
                                <i class="fas fa-building me-1"></i> القسم
                            </button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="date" data-target="historique">
                                <i class="fas fa-calendar-alt me-1"></i> التاريخ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tableHistorique">
                    <thead class="bg-success-light">
                        <tr>
                            <th style="width: 13%;">الرقم</th>
                            <th style="width: 22%;">العنوان</th>
                            <th style="width: 15%;">صادق عليه</th>
                            <th style="width: 15%;">القسم</th>
                            <th style="width: 17.5%;">تاريخ الإرسال</th>
                            <th style="width: 17.5%;">تاريخ المصادقة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($directDossiersEnvoyes as $transfert)
                        <tr class="bg-success-light">
                            <td>
                                <span class="d-inline-block rounded-circle bg-success me-2" style="width:10px;height:10px"></span>
                                {{ $transfert->dossier->numero_dossier_judiciaire ?? 'N/A' }}
                            </td>
                            <td>{{ $transfert->dossier->titre ?? 'N/A' }}</td>
                            <td>{{ $transfert->userDestination->name ?? 'N/A' }}</td>
                            <td>{{ $transfert->serviceDestination->nom ?? 'N/A' }}</td>
                            <td class="text-muted date-cell">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="date-cell">
                                <span style="color: green; font-size: 0.875rem;">
                                    <i class="fas fa-check-circle me-1" style="color: green;"></i>
                                    {{ $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'غير مصادق عليه' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour filtrer les tableaux
    function setupSearchAndFilter(tableId, searchId, filterTarget) {
        const searchInput = document.getElementById(searchId);
        const table = document.getElementById(tableId);
        if (!searchInput || !table) return;
        
        const rows = table.querySelectorAll('tbody tr');
        
        // Fonction de recherche
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const activeFilter = document.querySelector(`.filter-btn[data-target="${filterTarget}"].active`);
            const filterType = activeFilter ? activeFilter.dataset.filter : 'all';
            
            filterTable(rows, searchText, filterType);
        });
        
        // Configuration des boutons de filtre
        const filterButtons = document.querySelectorAll(`.filter-btn[data-target="${filterTarget}"]`);
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Retirer la classe active de tous les boutons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Ajouter la classe active au bouton cliqué
                this.classList.add('active');
                
                // Appliquer le filtre
                const searchText = searchInput.value.toLowerCase();
                const filterType = this.dataset.filter;
                
                filterTable(rows, searchText, filterType);
            });
        });
        
        // Activer le filtre "Tous" par défaut
        const defaultFilter = document.querySelector(`.filter-btn[data-target="${filterTarget}"][data-filter="all"]`);
        if (defaultFilter) defaultFilter.classList.add('active');
    }
    
    function filterTable(rows, searchText, filterType) {
        rows.forEach(row => {
            let showRow = false;
            const cells = row.querySelectorAll('td');
            
            if (filterType === 'all') {
                // Rechercher dans toutes les cellules
                showRow = Array.from(cells).some(cell => 
                    cell.textContent.toLowerCase().includes(searchText)
                );
            } else if (filterType === 'titre') {
                // Rechercher dans la colonne Titre (index 1)
                showRow = cells[1].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'numero') {
                // Rechercher dans la colonne Numéro (index 0)
                showRow = cells[0].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'date') {
                // Rechercher dans les colonnes de date
                const dateCells = Array.from(cells).filter(cell => 
                    cell.textContent.toLowerCase().includes('/')
                );
                showRow = dateCells.some(cell => 
                    cell.textContent.toLowerCase().includes(searchText)
                );
            } else if (filterType === 'destinataire' || filterType === 'validateur') {
                // Rechercher dans la colonne Destinataire/Validateur (index 2)
                showRow = cells[2].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'service') {
                // Rechercher dans la colonne Service (index 3)
                showRow = cells[3].textContent.toLowerCase().includes(searchText);
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    // Initialiser les recherches pour chaque tableau
    setupSearchAndFilter('tableNonEnvoyes', 'searchNonEnvoyes', 'nonEnvoyes');
    setupSearchAndFilter('tableEnAttente', 'searchEnAttente', 'enAttente');
    setupSearchAndFilter('tableHistorique', 'searchHistorique', 'historique');
});
</script>

<style>
    /* Custom styles */
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .border-right {
        border-right: 4px solid !important;
    }
    
    /* Style des boutons */
    .btn-primary {
        background: linear-gradient(to left, #0d6efd, #0b5ed7);
        border: none;
    }
    .btn-success {
        background: linear-gradient(to left, #198754, #157347);
        border: none;
    }
    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Style spécifique pour les boutons de filtre */
    .filter-btn {
        border-width: 1px;
        font-size: 0.875rem;
        border-radius: 0 !important;
    }
    
    /* Suppression de la bordure arrondie pour les btn-group */
    .btn-group > .btn:first-child,
    .btn-group > .btn:last-child,
    .btn-group > .btn {
        border-radius: 0 !important;
    }
    
    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 0 0 0.2rem rgba(102, 109, 116, 0.25);
    }
    
    .btn-outline-secondary.active {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-outline-warning.active {
        background-color: linear-gradient(to left, rgb(238, 235, 21), rgb(240, 225, 58));
        color: #212529;
    }
    
    .btn-outline-success.active {
        background-color: #198754;
        color: white;
    }
    
    /* Style des tableaux */
    .table {
        width: 100% !important;
        table-layout: fixed;
    }
    
    .table th {
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 0.75rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    /* Style spécifique pour les cellules de date */
    .date-cell {
        background-color: rgba(40, 167, 69, 0.05);
        white-space: nowrap;
        font-weight: 500;
    }
    
    /* Aligner les icônes */
    .text-muted i, 
    span i {
        width: 16px;
        text-align: center;
    }
    
    /* Style au survol des lignes */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    .bg-success-light tr:hover {
        background-color: rgba(40, 167, 69, 0.15) !important;
    }
    
    .bg-warning-light tr:hover {
        background-color: rgba(255, 193, 7, 0.15) !important;
    }
    
    /* Styles de la carte */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    /* Styles pour la barre de recherche */
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    .search-input {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    /* Styles responsifs */
    @media (max-width: 768px) {
        .table {
            width: 100%;
            min-width: 700px; /* Pour garantir que toutes les colonnes sont visibles */
        }
        
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .btn-group {
            overflow-x: auto;
            flex-wrap: nowrap;
            margin-top: 8px;
        }
        
        .filter-btn {
            white-space: nowrap;
        }
    }
</style>
@endsection