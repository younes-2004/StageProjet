@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-building text-primary me-2"></i>Gestion des services
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
                    <ul class="nav nav-tabs mb-4" id="serviceManagementTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="service-list-tab" data-bs-toggle="tab" data-bs-target="#service-list" 
                                    type="button" role="tab" aria-controls="service-list" aria-selected="true">
                                <i class="fas fa-list me-2"></i>Liste des services
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="add-service-tab" data-bs-toggle="tab" data-bs-target="#add-service" 
                                    type="button" role="tab" aria-controls="add-service" aria-selected="false">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter un service
                            </button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="serviceManagementTabsContent">
                        <!-- Service List Tab -->
                        <div class="tab-pane fade show active" id="service-list" role="tabpanel" aria-labelledby="service-list-tab">
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
                                                       id="searchServices" placeholder="Rechercher un service...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Services table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="servicesTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 25%;">Nom</th>
                                            <th style="width: 40%;">Description</th>
                                            <th style="width: 15%;">Utilisateurs</th>
                                            <th style="width: 30%;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td>{{ $service->id }}</td>
                                                <td>{{ $service->nom }}</td>
                                                <td>{{ $service->description ?? 'Aucune description' }}</td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $service->users()->count() }} utilisateur(s)
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-buttons">
                                                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-primary action-btn">
                                                            <i class="fas fa-edit me-1"></i> Modifier
                                                        </a>
                                                        
                                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" 
                                                              class="d-inline delete-service-form" data-service-name="{{ $service->nom }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger delete-service-btn action-btn">
                                                                <i class="fas fa-trash-alt me-1"></i> Supprimer
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
                                {{ $services->links() }}
                            </div>
                        </div>

                        <!-- Add Service Tab -->
                        <div class="tab-pane fade" id="add-service" role="tabpanel" aria-labelledby="add-service-tab">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Ajouter un nouveau service</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('services.store') }}">
                                        @csrf

                                        <!-- Nom -->
                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom du service</label>
                                            <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   name="nom" value="{{ old('nom') }}" required autofocus>
                                            @error('nom')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" 
                                                      name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> Annuler
                                            </button>
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
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le service <strong id="deleteServiceName"></strong> ?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
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
    
    /* Search styling */
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
    }
    
    .search-input {
        border-radius: 0 0.5rem 0.5rem 0;
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
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete service functionality
    const deleteButtons = document.querySelectorAll('.delete-service-btn');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    let formToSubmit = null;
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            formToSubmit = this.closest('form');
            const serviceName = formToSubmit.getAttribute('data-service-name');
            document.getElementById('deleteServiceName').textContent = serviceName;
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
    const searchInput = document.getElementById('searchServices');
    const table = document.getElementById('servicesTable');
    const rows = table.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        
        rows.forEach(row => {
            const serviceName = row.cells[1].textContent.toLowerCase();
            const serviceDesc = row.cells[2].textContent.toLowerCase();
            
            if (serviceName.includes(searchText) || serviceDesc.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection