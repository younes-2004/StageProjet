<!-- resources/views/receptions/dossiers_valides.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-check-circle text-success me-2"></i>ملفاتي المتحقق منها
                    </h5>
                    
                    <!-- Lien vers la boîte de réception -->
                    <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">
                        <i class="fas fa-inbox me-1"></i> العودة إلى صندوق الوارد
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
                    
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        عدد الملفات المتحقق منها: {{ $dossiersValides->count() }}
                    </div>
                    
                    <!-- Tableau 1: Dossiers validés non réaffectés -->
                    <div class="mb-4">
                        <div class="mb-3">
                            <h2 class="h3 section-title text-dark border-left border-dark pl-2 py-2" style="border-left: 6px solid #212529 !important; padding-left: 15px; margin-bottom: 8px;">
                                <i class="fas fa-edit me-2 text-secondary"></i>
                                الملفات المتحقق منها غير المعاد تعيينها
                            </h2>
                            <p class="text-muted ms-4 fs-6">الملفات في انتظار إعادة التعيين</p>
                        </div>
                        
                        <!-- Barre de recherche pour dossiers validés non réaffectés -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control border-start-0 search-input" id="searchNonReaffectes" placeholder="بحث...">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary filter-btn active" data-filter="all" data-target="nonReaffectes">
                                                <i class="fas fa-list-ul me-1"></i> الكل
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="numero" data-target="nonReaffectes">
                                                <i class="fas fa-hashtag me-1"></i> الرقم
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="date" data-target="nonReaffectes">
                                                <i class="fas fa-calendar-alt me-1"></i> التاريخ
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="service" data-target="nonReaffectes">
                                                <i class="fas fa-building me-1"></i> القسم
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="expediteur" data-target="nonReaffectes">
                                                <i class="fas fa-user me-1"></i> المرسل
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-bordered border-light" id="tableNonReaffectes">
                                <thead class="bg-light">
                                    <tr>
                                    <th style="width: 25%;">المصدر</th>
                                        <th style="width: 20%;">تاريخ التحقق</th>
                                        <th style="width: 15%;">القسم</th>
                                        <th style="width: 15%;">المرسل</th>
                                        <th class="text-center" style="width: 25%;">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
@php
    // Filtrer les dossiers validés qui n'ont pas encore été réaffectés ET qui ne sont pas archivés
    $dossiersNonReaffectes = $dossiersValides->filter(function($dossierValide) {
        // Exclure les dossiers archivés
        if (!$dossierValide->dossier || $dossierValide->dossier->statut === 'Archivé') {
            return false;
        }
        
        $transfert = \App\Models\Transfert::where('dossier_id', $dossierValide->dossier_id)
            ->where('user_source_id', auth()->id())
            ->where('statut', 'réaffectation')
            ->latest()
            ->first();
        
        return !$transfert; // Retourne true si aucun transfert de réaffectation
    });
@endphp 

                                    @if($dossiersNonReaffectes->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                لم يتم العثور على ملفات متحقق منها غير معاد تعيينها
                                            </td>
                                        </tr>
                                    @else
                                    @foreach($dossiersNonReaffectes as $dossierValide)
    @if($dossierValide->dossier && $dossierValide->dossier->statut !== 'Archivé')
        <tr>
            <!-- Titre du dossier -->
            <td>
                <span class="fw-medium">{{ $dossierValide->dossier->titre }}</span>
            </td>
                                                
                                                <!-- Date de validation -->
                                                <td class="text-muted">
                                                    <i class="far fa-calendar-check me-1"></i>
                                                    {{ $dossierValide->date_validation ? $dossierValide->date_validation->format('d/m/Y H:i') : 'غير متوفر' }}
                                                </td>
                                                
                                                <!-- Service -->
                                                <td>
                                                    <i class="fas fa-building me-1 text-secondary"></i>
                                                    @if($dossierValide->dossier->service)
                                                        {{ $dossierValide->dossier->service->nom }}
                                                    @else
                                                        رقم القسم: {{ $dossierValide->dossier->service_id }}
                                                    @endif
                                                </td>
                                                
                                                <!-- Expéditeur -->
                                                <td>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                                        <i class="fas fa-user-circle me-1"></i>
                                                        {{ $dossierValide->dossier->createur->name ?? 'غير معروف' }}
                                                    </span>
                                                </td>
                                                
                                                <!-- Actions -->
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="btn-group btn-group-spaced" role="group">
                                                            <!-- Bouton pour consulter les détails -->
                                                            <a href="{{ route('dossiers.show', $dossierValide->dossier_id) }}" 
                                                            class="btn btn-primary">
                                                                <i class="fas fa-eye"></i> عرض
                                                            </a>
                                                            
                                                            <!-- Bouton pour réaffecter -->
                                                            <a href="{{ route('receptions.reaffecter', $dossierValide->dossier_id) }}" 
                                                            class="btn btn-warning">
                                                                <i class="fas fa-share"></i> إعادة تعيين
                                                            </a>
                                                            
                                                            <!-- Bouton pour archiver -->
                                                            <form action="{{ route('dossiers.archiver', $dossierValide->dossier_id) }}" 
                                                                method="POST" 
                                                                style="display: inline;" 
                                                                onsubmit="return confirm('هل أنت متأكد من أنك تريد أرشفة هذا الملف؟');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-secondary">
                                                                    <i class="fas fa-archive"></i> أرشفة
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tableau 2: Dossiers réaffectés mais non validés -->
                    <div class="mb-4">
                        <div class="mb-3">
                            <h2 class="h3 section-title text-dark border-left border-warning pl-2 py-2" style="border-left: 6px solid #ffc107 !important; padding-left: 15px; margin-bottom: 8px;">
                                <i class="fas fa-clock me-2 text-warning"></i>
                                الملفات المعاد تعيينها غير المتحقق منها
                            </h2>
                            <p class="text-muted ms-4 fs-6">الملفات المعاد تعيينها في انتظار التحقق</p>
                        </div>
                        
                        <!-- Barre de recherche pour dossiers réaffectés non validés -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                           
                                            <input type="text" class="form-control border-start-0 search-input" id="searchReaffectes" placeholder="بحث..."> <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-warning filter-btn active" data-filter="all" data-target="reaffectes">
                                                <i class="fas fa-list-ul me-1"></i> الكل
                                            </button>
                                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="numero" data-target="reaffectes">
                                                <i class="fas fa-hashtag me-1"></i> الرقم
                                            </button>
                                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="titre" data-target="reaffectes">
    <i class="fas fa-heading me-1"></i> المصدر
</button>
                                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="destinataire" data-target="reaffectes">
                                                <i class="fas fa-user me-1"></i> المستلم
                                            </button>
                                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="date" data-target="reaffectes">
                                                <i class="fas fa-calendar-alt me-1"></i> التاريخ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-bordered border-light" id="tableReaffectes">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 15%;">الرقم</th>
                                        <th style="width: 25%;">المصدر</th>
                                        <th style="width: 20%;">المستلم</th>
                                        <th style="width: 20%;">تاريخ إعادة التعيين</th>
                                        <th class="text-center" style="width: 20%;">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Filtrer les dossiers qui ont été réaffectés mais pas encore validés
                                        $dossiersReaffectesNonValides = \App\Models\Transfert::where('user_source_id', auth()->id())
                                            ->where('statut', 'réaffectation')
                                            ->whereNull('date_reception') // Pas encore validé
                                            ->with(['dossier', 'userDestination'])
                                            ->latest('date_envoi')
                                            ->get();
                                    @endphp

                                    @if($dossiersReaffectesNonValides->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                لا توجد ملفات معاد تعيينها في انتظار التحقق
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($dossiersReaffectesNonValides as $transfert)
                                            @php
                                                $heuresCoulees = now()->diffInHours($transfert->date_envoi);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="d-inline-block rounded-circle bg-warning me-2" style="width:10px;height:10px"></span>
                                                    {{ $transfert->dossier->numero_dossier_judiciaire ?? 'غير متوفر' }}
                                                </td>
                                                <td class="fw-medium">{{ $transfert->dossier->titre ?? 'بدون عنوان' }}</td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-user-circle me-1"></i
                                                        {{ $transfert->userDestination->name ?? 'غير متوفر' }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'غير متوفر' }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="btn-group btn-group-spaced" role="group">
                                                            <!-- Bouton pour consulter les détails -->
                                                            <a href="{{ route('dossiers.show', $transfert->dossier_id) }}" 
                                                               class="btn btn-primary">
                                                                <i class="fas fa-eye me-1"></i> عرض
                                                            </a>
                                                            
                                                            <!-- Bouton d'annulation de la réaffectation (dans les 24h) -->
                                                            @if($heuresCoulees <= 24)
                                                                <form action="{{ route('receptions.annuler-transfert', $transfert->id) }}" 
                                                                      method="POST" 
                                                                      style="display: inline;"
                                                                      onsubmit="return confirm('هل أنت متأكد من إلغاء إعادة التعيين؟ سيعود الملف إلى قائمة الملفات المتحقق منها غير المعاد تعيينها.');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-times me-1"></i> إلغاء إعادة التعيين
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <button type="button" 
                                                                        class="btn btn-outline-secondary" 
                                                                        disabled
                                                                        title="لا يمكن إلغاء إعادة التعيين بعد 24 ساعة">
                                                                    <i class="fas fa-clock me-1"></i> انتهت المهلة
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $dossiersValides->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour configurer la recherche et le filtrage
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
    }
    
    function filterTable(rows, searchText, filterType) {
        rows.forEach(row => {
            let showRow = false;
            
            // Ignorer les lignes "aucun dossier trouvé"
            if (row.cells.length === 1 && row.cells[0].hasAttribute('colspan')) {
                row.style.display = '';
                return;
            }
            
            const cells = row.querySelectorAll('td');
            
            if (filterType === 'all') {
                // Rechercher dans toutes les cellules
                showRow = Array.from(cells).some(cell => 
                    cell.textContent.toLowerCase().includes(searchText)
                );
            } else if (filterType === 'titre') {
                // Rechercher dans la colonne Titre
                showRow = cells[0].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'date') {
                // Rechercher dans la colonne Date
                const dateColumn = (cells.length === 5) ? cells[1] : cells[3]; // Ajuster selon la table
                showRow = dateColumn.textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'service') {
                // Rechercher dans la colonne Service
                showRow = cells[2].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'expediteur') {
                // Rechercher dans la colonne Expéditeur
                showRow = cells[3].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'numero') {
                // Rechercher dans la colonne Numéro (première table)
                showRow = cells[0].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'destinataire') {
                // Rechercher dans la colonne Destinataire (deuxième table)
                showRow = cells[2].textContent.toLowerCase().includes(searchText);
            }
            
            row.style.display = showRow ? '' : 'none';
        });
        
        // Vérifier si toutes les lignes sont cachées pour afficher un message
        checkEmptyTable(rows);
    }
    
    function checkEmptyTable(rows) {
        // Pour chaque tableau, vérifier si toutes les lignes sont cachées
        let allHidden = true;
        let hasColspanRow = false;
        
        rows.forEach(row => {
            // Ignorer les lignes "aucun dossier trouvé"
            if (row.cells.length === 1 && row.cells[0].hasAttribute('colspan')) {
                hasColspanRow = true;
                return;
            }
            
            if (row.style.display !== 'none') {
                allHidden = false;
            }
        });
        
        // S'il n'y a pas déjà une ligne colspan et que toutes les lignes sont cachées
        if (!hasColspanRow && allHidden && rows.length > 0) {
            // Trouver le corps du tableau parent
            const tbody = rows[0].parentNode;
            const table = tbody.parentNode;
            const colCount = table.querySelector('thead tr').cells.length;
            
            // Créer une nouvelle ligne avec un message "Aucun résultat"
            const noResultsRow = document.createElement('tr');
            noResultsRow.classList.add('no-results-row');
            noResultsRow.innerHTML = `<td colspan="${colCount}" class="text-center py-3">
                <i class="fas fa-search text-muted me-2"></i>
                لم يتم العثور على نتائج لهذا البحث
            </td>`;
            
            // Ajouter la ligne au tableau
            tbody.appendChild(noResultsRow);
        } else if (!allHidden) {
            // Supprimer les lignes "Aucun résultat" si des résultats sont affichés
            document.querySelectorAll('.no-results-row').forEach(row => row.remove());
        }
    }
    
    // Initialiser les recherches pour chaque tableau
    setupSearchAndFilter('tableNonReaffectes', 'searchNonReaffectes', 'nonReaffectes');
    setupSearchAndFilter('tableReaffectes', 'searchReaffectes', 'reaffectes');
});
</script>

<style>
    /* Style de base */
    .container-fluid {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        width: 100%;
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    /* Style pour les tableaux */
    .table {
        width: 100% !important;
        table-layout: fixed;
    }
    
    .table th {
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.8rem !important;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
        padding: 8px !important;
    }
    
    .table td {
        vertical-align: middle;
        padding: 8px !important;
        font-size: 0.9rem !important;
    }
    
    .table.table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table.table-bordered td, 
    .table.table-bordered th {
        border: 1px solid #dee2e6;
    }
    
    .table thead th {
        padding: 8px !important;
        font-size: 0.8rem !important;
    }
    
    /* Style pour les boutons */
    .btn {
        font-weight: 500;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-radius: 0.375rem;
        padding: 0.3rem 0.6rem !important;
        font-size: 0.9rem !important;
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
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    /* Style pour les badges et alertes */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    
    /* Style pour la pagination */
    .pagination .page-link {
        border: none;
        color: #6c757d;
        margin: 0 0.25rem;
        border-radius: 0.375rem;
        padding: 0.4rem 0.75rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Style pour les titres de section */
    .section-title {
        font-weight: 700 !important;
        font-size: 1.3rem !important;
        margin-bottom: 0.5rem;
    }
    
    /* Style pour les groupes de boutons dans les actions */
    .btn-group {
        display: inline-flex;
        white-space: nowrap;
    }
    
    .btn-group .btn {
        padding: 0.3rem 0.6rem !important;
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    
       
    }
    
    .btn-group form {
        display: inline-flex;
    }
    
    /* Style pour les groupes de boutons avec espacement */
    .btn-group-spaced {
        display: inline-flex;
        white-space: nowrap;
        gap: 3px;
    }
    
    .btn-group-spaced .btn {
        padding: 0.3rem 0.6rem !important;
        border-radius: 0;
        font-size: 0.85rem !important;
    }
    
    .btn-group-spaced form {
        display: inline-flex;
    }
    
    /* Style pour la barre de recherche */
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    .search-input {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    /* Style pour les boutons de filtre */
    .filter-btn {
        border-width: 1px;
        font-size: 0.875rem;
    }
    
    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.15);
    }
    
    .btn-outline-secondary.active {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-outline-warning.active {
        background-color: #ffc107;
        color: #212529;
    }
    
    /* Ajustements pour les textes descriptifs */
    .fs-6 {
        font-size: 0.9rem !important;
    }
    
    /* Ajustements pour responsive */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto;
    }
    
    /* Espacement réduit */
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .mb-4 {
        margin-bottom: 1.25rem !important;
    }
    
    /* Style pour les messages "aucun résultat" */
    .no-results-row td {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    /* Style pour les boutons désactivés */
    .btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .btn[disabled]:hover {
        transform: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .btn-group {
            overflow-x: auto;
            flex-wrap: nowrap;
            margin-top: 8px;
        }
        
        .filter-btn {
            white-space: nowrap;
        }
        
        .btn-group-spaced {
            flex-direction: column;
            gap: 2px;
        }
        
        .btn-group-spaced .btn {
            width: 100%;
            margin-bottom: 2px;
        }
    }
</style>
@endsection