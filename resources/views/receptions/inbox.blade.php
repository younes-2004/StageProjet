@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-inbox text-primary me-2"></i>ملفاتي المستلمة
                    </h5>
                    <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-primary">
                        <i class="fas fa-check-circle me-1"></i> عرض الملفات المتحقق منها
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
                        عدد الملفات المستلمة: {{ $receptions->count() }}
                    </div>

                    @if($receptions->isEmpty())
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-inbox-open fa-2x mb-3 text-info"></i>
                            <h5 class="alert-heading">لا توجد ملفات مستلمة</h5>
                            <p class="mb-0">لم تستلم أي ملفات حتى الآن.</p>
                        </div>
                    @else
                        <!-- Barre de recherche pour dossiers reçus -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            
                                            <input type="text" class="form-control border-start-0 search-input" id="searchReceptions" placeholder="البحث عن ملف..."><span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all" data-target="receptions">
                                                <i class="fas fa-list-ul me-1"></i> الكل
                                            </button>
                                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="titre" data-target="receptions">
    <i class="fas fa-heading me-1"></i> المصدر
</button>
                                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="service" data-target="receptions">
                                                <i class="fas fa-building me-1"></i> القسم
                                            </button>
                                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="expediteur" data-target="receptions">
                                                <i class="fas fa-user me-1"></i> المرسل
                                            </button>
                                            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="numero" data-target="receptions">
    <i class="fas fa-hashtag me-1"></i> رقم الملف القضائي
</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="tableReceptions">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-semibold" style="width: 15%;">رقم الملف القضائي</th>
                                        <th class="fw-semibold" style="width: 25%;">المصدر</th>
                                        <th class="fw-semibold" style="width: 15%;">القسم</th>
                                        <th class="fw-semibold" style="width: 15%;">المرسل</th>
                                        <th class="fw-semibold" style="width: 20%;">تعليق</th>
                                        <th class="fw-semibold text-end" style="width: 25%;">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receptions as $reception)
                                        @if(!$reception->traite)
                                        <tr class="border-top border-light">
                                            <td>
                                                <span class="fw-medium">{{ $reception->dossier->numero_dossier_judiciaire ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $reception->dossier->titre }}</span>
                                            </td>
                                            <td class="text-muted">
                                                <i class="fas fa-building me-1 text-secondary"></i>
                                                {{ $reception->dossier->service->nom ?? $reception->dossier->service_id }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    <i class="fas fa-user-circle me-1"></i>
                                                    {{ $reception->dossier->createur->name ?? 'غير معروف' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    // Récupérer le transfert correspondant à cette réception
                                                    $transfert = \App\Models\Transfert::where('dossier_id', $reception->dossier_id)
                                                        ->where('user_destination_id', auth()->id())
                                                        ->whereNull('date_reception')
                                                        ->latest('created_at')
                                                        ->first();
                                                @endphp
                                                @if($transfert && $transfert->commentaire)
                                                    <span class="text-muted fst-italic">
                                                        <i class="fas fa-comment me-1"></i>
                                                        {{ $transfert->commentaire }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">لا يوجد تعليق</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('dossiers.show', $reception->dossier_id) }}" 
                                                    class="btn btn-sm btn-primary px-3 py-1 me-2">
                                                        <i class="fas fa-eye me-1"></i> عرض
                                                    </a>
                                                    
                                                    <form action="{{ route('receptions.valider', $reception->id) }}" method="POST" style="display: inline-block; margin: 0;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="commentaire_reception" value="">
                                                        <input type="hidden" name="observations" value="">
                                                        <button type="submit" class="btn btn-sm btn-success px-3 py-1">
                                                            <i class="fas fa-check me-1"></i> مصادقة
                                                        </button>
                                                    </form>

                                                    <!-- Bouton pour ouvrir le modal d'édition de l'observation -->
<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#observationModal{{ $reception->id }}">
    <i class="fas fa-edit"></i> تعديل الملاحظة
</button>

<!-- Modal d'édition de l'observation -->
<div class="modal fade" id="observationModal{{ $reception->id }}" tabindex="-1" aria-labelledby="observationModalLabel{{ $reception->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('receptions.observation', $reception->id) }}">
        @csrf
        @method('PATCH')
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="observationModalLabel{{ $reception->id }}">تعديل الملاحظة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <textarea name="observation" class="form-control" rows="4">{{ $reception->dossier->observation }}</textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">حفظ</button>
          </div>
        </div>
    </form>
  </div>
</div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>ملاحظة:</strong> الملفات المتحقق منها ستكون متاحة في قسم 
                            <a href="{{ route('receptions.dossiers_valides') }}" class="fw-bold">الملفات المتحقق منها</a>.
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3">
                            {{ $receptions->links() }}
                        </div>
                    @endif
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
            const cells = row.querySelectorAll('td');

            if (filterType === 'all') {
                showRow = Array.from(cells).some(cell =>
                    cell.textContent.toLowerCase().includes(searchText)
                );
            } else if (filterType === 'titre') {
                // Le titre est maintenant à l'index 1
                showRow = cells[1].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'service') {
                showRow = cells[2].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'expediteur') {
                showRow = cells[3].textContent.toLowerCase().includes(searchText);
            } else if (filterType === 'numero') {
                showRow = cells[0].textContent.toLowerCase().includes(searchText);
            }
            row.style.display = showRow ? '' : 'none';
        });
    }
    
    // Initialiser la recherche pour la table des réceptions
    setupSearchAndFilter('tableReceptions', 'searchReceptions', 'receptions');
});
</script>

<style>
    /* Style général */
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    
    /* Style des tableaux */
    .table {
        width: 100% !important;
        table-layout: fixed;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 0.75rem;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
        border-color: rgba(0, 0, 0, 0.03);
        font-size: 0.9rem;
    }
    
    /* Style pour les boutons */
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
    
    /* Style pour les badges et alertes */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border: none;
        border-radius: 0.5rem;
    }
    
    /* Style pour la pagination */
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
    
    /* Style spécifique pour les actions */
    .text-end {
        white-space: nowrap;
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
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        background-color: #0d6efd;
        color: white;
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
        
        .table {
            min-width: 700px;
        }
    }
</style>
@endsection