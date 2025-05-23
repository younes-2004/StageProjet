
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-archive text-secondary me-2"></i>الملفات المؤرشفة
                    </h5>
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
                                        <i class="fas fa-heading me-1"></i> العنوان
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
                                <th>العنوان</th>
                                <th>القسم</th>
                                <th>تاريخ الأرشفة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dossiersArchives as $dossier)
                                <tr>
                                    <td>{{ $dossier->numero_dossier_judiciaire }}</td>
                                    <td>{{ $dossier->titre }}</td>
                                    <td>{{ $dossier->service->nom ?? 'غير محدد' }}</td>
                                    <td>
                                        {{ $dossier->updated_at ? $dossier->updated_at->format('d/m/Y H:i') : 'غير متوفر' }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> عرض
                                            </a>
                                            
                                            @if(auth()->user()->role === 'greffier_en_chef')
                                                <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit me-1"></i> تعديل
                                                </a>
                                                
                                                <form action="{{ route('dossiers.destroy', $dossier->id) }}" method="POST" class="delete-dossier-form" data-dossier-titre="{{ $dossier->titre }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-dossier-btn">
                                                        <i class="fas fa-trash-alt me-1"></i> حذف
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h4>لا توجد ملفات مؤرشفة</h4>
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

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف الملف <strong id="deleteDossierTitre"></strong> ؟</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    هذا الإجراء لا يمكن التراجع عنه.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
            </div>
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

    // Gestion de la suppression
    const deleteButtons = document.querySelectorAll('.delete-dossier-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    let currentForm = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentForm = this.closest('form');
            const dossierTitre = currentForm.getAttribute('data-dossier-titre');
            
            document.getElementById('deleteDossierTitre').textContent = dossierTitre;
            
            deleteModal.show();
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (currentForm) {
            currentForm.submit();
            deleteModal.hide();
        }
    });
});
</script>
<style>
    /* Conservez les styles de la vue dossiers/search.blade.php */
</style>
@endsection
