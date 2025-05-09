@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-users text-primary me-2"></i>إدارة المستخدمين
                    </h5>
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

                    <!-- Tabs navigation -->
                    <ul class="nav nav-tabs mb-4" id="userManagementTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="user-list-tab" data-bs-toggle="tab" data-bs-target="#user-list" 
                                    type="button" role="tab" aria-controls="user-list" aria-selected="true">
                                <i class="fas fa-list me-2"></i>قائمة المستخدمين
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="add-user-tab" data-bs-toggle="tab" data-bs-target="#add-user" 
                                    type="button" role="tab" aria-controls="add-user" aria-selected="false">
                                <i class="fas fa-user-plus me-2"></i>إضافة مستخدم
                            </button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="userManagementTabsContent">
                        <!-- User List Tab -->
                        <div class="tab-pane fade show active" id="user-list" role="tabpanel" aria-labelledby="user-list-tab">
                            <!-- Search and filter -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 search-input" 
                                                       id="searchUsers" placeholder="البحث عن مستخدم...">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="btn-group filter-group" role="group">
                                                <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                                                    <i class="fas fa-list-ul me-1"></i> الكل
                                                </button>
                                                <button type="button" class="btn btn-outline-primary filter-btn" data-filter="name">
                                                    <i class="fas fa-user me-1"></i> الاسم
                                                </button>
                                                <button type="button" class="btn btn-outline-primary filter-btn" data-filter="role">
                                                    <i class="fas fa-user-tag me-1"></i> الدور
                                                </button>
                                                <button type="button" class="btn btn-outline-primary filter-btn" data-filter="service">
                                                    <i class="fas fa-building me-1"></i> الخدمة
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Users table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="usersTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 3%;">#</th>
                                            <th style="width: 15%;">الاسم</th>
                                            <th style="width: 15%;">اسم العائلة</th>
                                            <th style="width: 20%;">البريد الإلكتروني</th>
                                            <th style="width: 12%;">الدور</th>
                                            <th style="width: 15%;">الخدمة</th>
                                            <th style="width: 30%;" class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($utilisateurs as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->fname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge {{ $user->role == 'greffier_en_chef' ? 'bg-danger' : 'bg-primary' }}">
                                                        {{ $user->role == 'greffier_en_chef' ? 'رئيس كتبة الضبط' : 'كاتب الضبط' }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->service->nom ?? 'غير معين' }}</td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary action-btn">
                                                            <i class="fas fa-edit me-1"></i> تعديل
                                                        </a>
                                                        
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                                              class="d-inline delete-user-form" data-user-name="{{ $user->name }} {{ $user->fname }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger delete-user-btn action-btn">
                                                                <i class="fas fa-trash-alt me-1"></i> حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $utilisateurs->links() }}
                            </div>
                        </div>

                        <!-- Add User Tab -->
                        <div class="tab-pane fade" id="add-user" role="tabpanel" aria-labelledby="add-user-tab">
                            <!-- Using the existing registration form -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">إضافة مستخدم جديد</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.store') }}">
                                        @csrf

                                        <!-- Nom -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">اسم</label>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       name="name" value="{{ old('name') }}" required autofocus>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Prénom -->
                                            <div class="col-md-6">
                                                <label for="fname" class="form-label">اسم العائلة</label>
                                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" 
                                                       name="fname" value="{{ old('fname') }}" required>
                                                @error('fname')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">البريد الإلكتروني</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Mot de passe -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">كلمة المرور</label>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       name="password" required>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Confirmation du mot de passe -->
                                            <div class="col-md-6">
                                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                                <input id="password_confirmation" type="password" class="form-control" 
                                                       name="password_confirmation" required>
                                            </div>
                                        </div>

                                        <!-- Rôle -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="role" class="form-label">الدور</label>
                                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                                    <option value="" disabled selected>اختر دور</option>
                                                    <option value="greffier" {{ old('role') == 'greffier' ? 'selected' : '' }}>كاتب الضبط</option>
                                                    <option value="greffier_en_chef" {{ old('role') == 'greffier_en_chef' ? 'selected' : '' }}>رئيس كتبة الضبط </option>
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Service -->
                                            <div class="col-md-6">
                                                <label for="service_id" class="form-label">الخدمة</label>
                                                <select id="service_id" name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                                    <option value="" disabled selected>اختر خدمة</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                            {{ $service->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('service_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> إلغاء
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fas fa-save me-1"></i> حفظ
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف المستخدم <strong id="deleteUserName"></strong>؟</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">حذف</button>
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
        padding: 1.25rem 1.5rem;
    }
    
    /* Table styling */
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
        padding: 0.75rem;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    
    .action-btn {
        width: 110px; /* Taille fixe pour les boutons */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    /* Form styling */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-color: #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Nav tabs styling */
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.75rem 1rem;
        font-weight: 500;
        border-radius: 0;
        margin-right: 0.5rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: transparent;
        border-bottom: 2px solid #0d6efd;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom: 2px solid #dee2e6;
    }
    
    /* Search and filter styling */
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    .search-input {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .filter-btn {
        font-size: 0.875rem;
        border-width: 1px;
        flex: 1;
        min-width: 80px;
    }
    
    .filter-btn.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 0.5rem;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-group {
            flex-wrap: wrap;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-btn {
            width: 100%;
        }
    }
    /* Ajouter ces styles CSS dans la section <style> */
.action-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.action-btn {
    width: 130px !important; /* Largeur fixe et forcée */
    text-align: center;
    padding: 6px 12px;
    margin: 0;
}

/* Pour éviter que le formulaire n'influenve la largeur */
.delete-user-form {
    margin: 0;
    padding: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete user functionality
    const deleteButtons = document.querySelectorAll('.delete-user-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    let formToSubmit = null;
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            formToSubmit = this.closest('form');
            const userName = formToSubmit.getAttribute('data-user-name');
            document.getElementById('deleteUserName').textContent = userName;
            deleteModal.show();
        });
    });
    
    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
        deleteModal.hide();
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchUsers');
    const table = document.getElementById('usersTable');
    const rows = table.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active');
        const filterType = activeFilter ? activeFilter.getAttribute('data-filter') : 'all';
        
        filterTable(rows, searchText, filterType);
    });
    
    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter type
            const filterType = this.getAttribute('data-filter');
            
            // Apply filter
            const searchText = searchInput.value.toLowerCase();
            filterTable(rows, searchText, filterType);
        });
    });
    
  // Corriger la fonction de filtrage dans le script JavaScript
function filterTable(rows, searchText, filterType = 'all') {
    rows.forEach(row => {
        const cells = row.getElementsByTagName('td');
        let showRow = false;
        
        switch(filterType) {
            case 'all':
                // Rechercher dans toutes les colonnes
                showRow = Array.from(cells).some(cell => 
                    cell.textContent.toLowerCase().includes(searchText)
                );
                break;
            case 'name':
                // Rechercher dans les colonnes Nom et Prénom (index 1 et 2)
                showRow = cells[1].textContent.toLowerCase().includes(searchText) || 
                          cells[2].textContent.toLowerCase().includes(searchText);
                break;
            case 'role':
                // Rechercher dans la colonne Role (index 4)
                // On utilise textContent pour obtenir tout le texte, y compris le texte dans le span
                showRow = cells[4].textContent.toLowerCase().includes(searchText);
                break;
            case 'service':
                // Rechercher dans la colonne Service (index 5)
                showRow = cells[5].textContent.toLowerCase().includes(searchText);
                break;
            default:
                showRow = Array.from(cells).some(cell => 
                    cell.textContent.toLowerCase().includes(searchText)
                );
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}
});
</script>
@endsection
