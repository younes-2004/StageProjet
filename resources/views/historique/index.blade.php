@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-history text-primary me-2"></i>Historique des actions
                    </h5>
                    
                    <!-- Bouton d'exportation -->
                    <a href="{{ route('historique.export', request()->query()) }}" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Exporter
                    </a>
                </div>

                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Actions par type</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="actionsParTypeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Actions par jour</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="actionsParJourChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Actions par service</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="actionsParServiceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="text-muted mb-1">Utilisateurs les plus actifs</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach($utilisateursActifs as $user)
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                                                <span>{{ $user->utilisateur->name ?? 'N/A' }}</span>
                                                <span class="badge bg-primary rounded-pill">{{ $user->total }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">Filtres avancés</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('historique.index') }}" method="GET" id="searchForm">
                                <div class="row g-3">
                                    <!-- Action -->
                                    <div class="col-md-4">
                                        <label for="action" class="form-label">Type d'action</label>
                                        <select id="action" name="action" class="form-select">
                                            <option value="">Toutes les actions</option>
                                            @foreach($actions as $action)
                                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                    {{ ucfirst($action) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Utilisateur -->
                                    <div class="col-md-4">
                                        <label for="user_id" class="form-label">Utilisateur</label>
                                        <select id="user_id" name="user_id" class="form-select">
                                            <option value="">Tous les utilisateurs</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} {{ $user->fname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Service -->
                                    <div class="col-md-4">
                                        <label for="service_id" class="form-label">Service</label>
                                        <select id="service_id" name="service_id" class="form-select">
                                            <option value="">Tous les services</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Dossier -->
                                    <div class="col-md-4">
                                        <label for="dossier_id" class="form-label">Dossier</label>
                                        <select id="dossier_id" name="dossier_id" class="form-select">
                                            <option value="">Tous les dossiers</option>
                                            @foreach($dossiers as $dossier)
                                                <option value="{{ $dossier->id }}" {{ request('dossier_id') == $dossier->id ? 'selected' : '' }}>
                                                    {{ $dossier->numero_dossier_judiciaire }} - {{ $dossier->titre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Date début -->
                                    <div class="col-md-4">
                                        <label for="date_debut" class="form-label">Date de début</label>
                                        <input type="date" id="date_debut" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                    </div>
                                    
                                    <!-- Date fin -->
                                    <div class="col-md-4">
                                        <label for="date_fin" class="form-label">Date de fin</label>
                                        <input type="date" id="date_fin" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                    </div>
                                    
                                    <!-- Recherche par mot-clé -->
                                    <div class="col-md-8">
                                        <label for="keyword" class="form-label">Mot-clé dans la description</label>
                                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Rechercher..." value="{{ request('keyword') }}">
                                    </div>
                                    
                                    <!-- Boutons de tri -->
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="sort_by" class="form-label">Trier par</label>
                                                <select id="sort_by" name="sort_by" class="form-select">
                                                    <option value="date_action" {{ request('sort_by') == 'date_action' ? 'selected' : '' }}>Date</option>
                                                    <option value="action" {{ request('sort_by') == 'action' ? 'selected' : '' }}>Type d'action</option>
                                                    <option value="description" {{ request('sort_by') == 'description' ? 'selected' : '' }}>Description</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="sort_direction" class="form-label">Ordre</label>
                                                <select id="sort_direction" name="sort_direction" class="form-select">
                                                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="col-12 text-end mt-3">
                                        <button type="button" id="resetButton" class="btn btn-secondary me-2">
                                            <i class="fas fa-redo me-1"></i>Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Filtrer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Liste des actions -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Liste des actions</h6>
                            <span class="badge bg-primary">{{ $historiques->total() }} résultat(s)</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 10%;">Action</th>
                                            <th style="width: 15%;">Utilisateur</th>
                                            <th style="width: 15%;">Service</th>
                                            <th style="width: 15%;">Dossier</th>
                                            <th style="width: 25%;">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($historiques as $historique)
                                            <tr>
                                                <td>{{ $historique->id }}</td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ $historique->date_action ? $historique->date_action->format('d/m/Y H:i') : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($historique->action == 'creation') bg-success 
                                                        @elseif($historique->action == 'modification') bg-info 
                                                        @elseif($historique->action == 'transfert') bg-primary 
                                                        @elseif($historique->action == 'validation') bg-warning
                                                        @elseif($historique->action == 'archivage') bg-secondary 
                                                        @else bg-light text-dark @endif">
                                                        {{ ucfirst($historique->action) }}
                                                    </span>
                                                </td>
                                                <td>{{ $historique->user->name ?? 'N/A' }}</td>
                                                <td>{{ $historique->service->nom ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('dossiers.show', $historique->dossier_id) }}" class="text-decoration-none">
                                                        {{ $historique->dossier->numero_dossier_judiciaire ?? 'N/A' }}
                                                    </a>
                                                </td>
                                                <td>{{ $historique->description }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    <i class="fas fa-info-circle text-info me-1"></i>
                                                    Aucune action trouvée avec les critères sélectionnés
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-center">
                                {{ $historiques->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Réinitialisation du formulaire
    document.getElementById('resetButton').addEventListener('click', function() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.value = '';
        });
        
        form.submit();
    });
    
    // Graphique Actions par type
    const actionsParTypeChart = new Chart(
        document.getElementById('actionsParTypeChart'),
        {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($actionsParType as $action)
                        '{{ ucfirst($action->action) }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($actionsParType as $action)
                            {{ $action->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(13, 202, 240, 0.7)',
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(108, 117, 125, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        }
    );
    
    // Graphique Actions par jour
    const actionsParJourChart = new Chart(
        document.getElementById('actionsParJourChart'),
        {
            type: 'line',
            data: {
                labels: [
                    @foreach($actionsParJour as $action)
                        '{{ \Carbon\Carbon::parse($action->date)->format('d/m') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Actions',
                    data: [
                        @foreach($actionsParJour as $action)
                            {{ $action->total }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderColor: 'rgba(13, 110, 253, 0.7)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                }
            }
        }
    );
    
    // Graphique Actions par service
    const actionsParServiceChart = new Chart(
        document.getElementById('actionsParServiceChart'),
        {
            type: 'bar',
            data: {
                labels: [
                    @foreach($actionsParService as $action)
                        '{{ $action->service->nom ?? 'N/A' }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Actions',
                    data: [
                        @foreach($actionsParService as $action)
                            {{ $action->total }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                }
            }
        }
    );
});
</script>

<style>
    /* Card styling */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
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
    
    /* Form styling */
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
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
    
    /* Chart styling */
    .chart-container {
        position: relative;
        margin: auto;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .chart-container {
            height: 200px !important;
        }
    }
</style>
@endsection