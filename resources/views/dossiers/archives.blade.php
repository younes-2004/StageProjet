@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-archive text-secondary me-2"></i>
                        ملفاتي المؤرشفة
                    </h5>
                    
                    <!-- Lien de retour vers mes dossiers -->
                    <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-1"></i> العودة إلى ملفاتي
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Message de confirmation -->
                    @if (session('success'))
                        <div class="confirmation-card mb-4">
                            <div class="card border-0 bg-success-subtle">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="confirmation-icon bg-success text-white me-3">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="confirmation-content">
                                        <h5 class="mb-1 fw-bold">تمت العملية بنجاح!</h5>
                                        <p class="mb-0">{{ session('success') }}</p>
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

                    <!-- Info sur le nombre de dossiers -->
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        عدد ملفاتك المؤرشفة: {{ $dossiersArchives->total() }}
                    </div>

                    <!-- Barre de recherche et filtres -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control border-start-0 search-input" 
                                               id="searchArchives" placeholder="البحث في الملفات المؤرشفة...">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary filter-btn active" data-filter="all">
                                            <i class="fas fa-list-ul me-1"></i> الكل
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="titre">
    <i class="fas fa-heading me-1"></i> المصدر
</button>
                                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="numero">
                                            <i class="fas fa-hashtag me-1"></i> الرقم
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="service">
                                            <i class="fas fa-building me-1"></i> القسم
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des archives -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="archivesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>رقم الملف</th>
                                    <th>المصدر</th>
                                    <th>القسم</th>
                                    <th>تاريخ الأرشفة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dossiersArchives as $dossier)
                                    <tr>
                                        <td>
                                            <span class="d-inline-block rounded-circle bg-secondary me-2" style="width:10px;height:10px"></span>
                                            {{ $dossier->numero_dossier_judiciaire }}
                                        </td>
                                        <td>{{ $dossier->titre }}</td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $dossier->service->nom ?? 'غير محدد' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $dossier->updated_at ? $dossier->updated_at->format('d/m/Y H:i') : 'غير متوفر' }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i> عرض
                                                </a>
                                                
                                                <!-- Bouton de désarchivage -->
                                                <form action="{{ route('dossiers.desarchiver', $dossier->id) }}" 
                                                      method="POST" 
                                                      class="desarchiver-form" 
                                                      data-dossier-titre="{{ $dossier->titre }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" class="btn btn-sm btn-success desarchiver-btn" 
                                                            title="إلغاء الأرشفة وإعادة الملف إلى ملفاتي">
                                                        <i class="fas fa-undo me-1"></i> إلغاء الأرشفة
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <h4>لا توجد ملفات مؤرشفة</h4>
                                            <p class="text-muted">لم تقم بأرشفة أي ملف بعد</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $dossiersArchives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de désarchivage -->
<div class="modal fade" id="desarchiverConfirmModal" tabindex="-1" aria-labelledby="desarchiverConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="desarchiverConfirmModalLabel">تأكيد إلغاء الأرشفة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إلغاء أرشفة الملف <strong id="desarchiverDossierTitre"></strong> ؟</p>
                <p class="text-success">
                    <i class="fas fa-info-circle me-2"></i>
                    سيتم إعادة الملف إلى قائمة "ملفاتي" وسيظهر مع الملفات النشطة.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" id="confirmDesarchiverBtn">
                    <i class="fas fa-undo me-1"></i> إلغاء الأرشفة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage et recherche des archives
    function filterTable(rows, searchText, filterType = 'all') {
        rows.forEach(row => {
            // Ignorer les lignes de message "pas de résultats"
            if (row.cells.length === 1 && row.cells[0].hasAttribute('colspan')) {
                row.style.display = '';
                return;
            }
            
            const cells = row.querySelectorAll('td');
            let showRow = false;
            
            switch(filterType) {
                case 'all':
                    showRow = Array.from(cells).some(cell => 
                        cell.textContent.toLowerCase().includes(searchText)
                    );
                    break;
                case 'titre':
                    showRow = cells[1].textContent.toLowerCase().includes(searchText);
                    break;
                case 'numero':
                    showRow = cells[0].textContent.toLowerCase().includes(searchText);
                    break;
                case 'service':
                    showRow = cells[2].textContent.toLowerCase().includes(searchText);
                    break;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
    }

    const searchInput = document.getElementById('searchArchives');
    const table = document.getElementById('archivesTable');
    const rows = table.querySelectorAll('tbody tr');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    searchInput.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active');
        const filterType = activeFilter ? activeFilter.getAttribute('data-filter') : 'all';
        
        filterTable(rows, searchText, filterType);
    });
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filterType = this.getAttribute('data-filter');
            const searchText = searchInput.value.toLowerCase();
            
            filterTable(rows, searchText, filterType);
        });
    });

    // Gestion du désarchivage
    const desarchiverButtons = document.querySelectorAll('.desarchiver-btn');
    if (desarchiverButtons.length > 0) {
        const desarchiverModal = new bootstrap.Modal(document.getElementById('desarchiverConfirmModal'));
        let currentForm = null;

        desarchiverButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentForm = this.closest('form');
                const dossierTitre = currentForm.getAttribute('data-dossier-titre');
                
                document.getElementById('desarchiverDossierTitre').textContent = dossierTitre;
                
                desarchiverModal.show();
            });
        });

        document.getElementById('confirmDesarchiverBtn').addEventListener('click', function() {
            if (currentForm) {
                currentForm.submit();
                desarchiverModal.hide();
            }
        });
    }
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
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    /* Style pour les badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Style pour les alertes */
    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    
    /* Animation d'entrée pour les messages de confirmation */
    @keyframes slideIn {
        0% { transform: translateY(-20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .confirmation-card {
        animation: slideIn 0.3s ease forwards;
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
    
    /* Style pour les boutons de filtre */
    .filter-btn {
        border-width: 1px;
        font-size: 0.875rem;
    }
    
    .filter-btn.active {
        font-weight: 600;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.15);
        background-color: #6c757d;
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
    }
</style>
@endsection