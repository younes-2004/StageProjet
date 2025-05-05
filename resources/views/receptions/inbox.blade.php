@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-inbox text-primary me-2"></i>Mes dossiers reçus
                    </h5>
                    <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-primary">
                        <i class="fas fa-check-circle me-1"></i> Voir les dossiers validés
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
                        Nombre de dossiers reçus trouvés : {{ $receptions->count() }}
                    </div>

                    @if($receptions->isEmpty())
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-inbox-open fa-2x mb-3 text-info"></i>
                            <h5 class="alert-heading">Aucun dossier reçu</h5>
                            <p class="mb-0">Vous n'avez reçu aucun dossier pour le moment.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-semibold w-25">Titre du dossier</th>
                                        <th class="fw-semibold w-25">Service</th>
                                        <th class="fw-semibold w-25">Expéditeur</th>
                                        <th class="fw-semibold text-end w-25">Actions</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach($receptions as $reception)
                                        @if(!$reception->traite)
                                        <tr class="border-top border-light">
                                            <td>
                                                <span class="fw-medium">{{ $reception->dossier->titre }}</span>
                                            </td>
                                            <td class="text-muted">
                                                {{ $reception->dossier->service_id }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    <i class="fas fa-user-circle me-1"></i>
                                                    {{ $reception->dossier->createur->name ?? 'Inconnu' }}
                                                </span>
                                            </td>
                                            <td class="text-end" style="white-space: nowrap; min-width: 200px;">
    <a href="{{ route('dossiers.show', $reception->dossier_id) }}" 
       class="btn btn-sm btn-primary px-3 py-1" style="display: inline-block; margin-right: 8px;">
       <i class="fas fa-eye me-1"></i> Voir
    </a>
    
    <form action="{{ route('receptions.valider', $reception->id) }}" method="POST" style="display: inline-block; margin: 0;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="commentaire_reception" value="">
        <input type="hidden" name="observations" value="">
        <button type="submit" class="btn btn-sm btn-success px-3 py-1">
            <i class="fas fa-check me-1"></i> Valider
        </button>
    </form>
</td></tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Les dossiers validés seront disponibles dans la section 
                            <a href="{{ route('receptions.dossiers_valides') }}" class="fw-bold">Dossiers validés</a>.
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

<style>
    .table td:last-child {
    white-space: nowrap;
    width: auto;
    min-width: 160px;
}

/* Pour s'assurer que le conteneur flex ne se brise pas */
.flex-nowrap {
    flex-wrap: nowrap !important;
}

/* Pour garantir un espacement adéquat */
.me-2 {
    margin-right: 0.5rem !important;
}
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-color: rgba(0, 0, 0, 0.03);
    }
    
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
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border: none;
        border-radius: 0.5rem;
    }
    
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
    
    .gap-2 {
        gap: 0.5rem;
    }
    .table {
    width: 100% !important;
    table-layout: fixed;
}

</style>
@endsection