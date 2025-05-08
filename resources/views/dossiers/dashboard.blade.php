@extends('layouts.app')

@section('content')
<style>
    .bg-indigo {
        background-color: #6610f2 !important;
    }
    
    .bg-pink {
        background-color: #d63384 !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>Tableau de bord des dossiers
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Statistiques générales -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <h6 class="mb-1">Total des dossiers</h6>
                                    <h3 class="mb-0 fw-bold">{{ $totalDossiers }}</h3>
                                </div>
                            </div>
                        </div>
                        
                        @foreach($dossiersParStatut as $statut)
                        <div class="col-md-3">
                            <div class="card 
                                @if($statut->statut == 'Créé') bg-info 
                                @elseif($statut->statut == 'En attente') bg-warning 
                                @elseif($statut->statut == 'Validé') bg-success 
                                @elseif($statut->statut == 'En traitement') bg-primary
                                @elseif($statut->statut == 'Transmis') bg-indigo
                                @elseif($statut->statut == 'Réaffecté') bg-pink
                                @elseif($statut->statut == 'Archivé') bg-secondary 
                                @else bg-light text-dark @endif 
                                text-white">
                                <div class="card-body text-center p-3">
                                    <h6 class="mb-1">{{ $statut->statut }}</h6>
                                    <h3 class="mb-0 fw-bold">{{ $statut->total }}</h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Graphiques -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Dossiers par service</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartDossiersParService" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Dossiers par statut</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartDossiersParStatut" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dossiers récents -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-folder-open text-primary me-2"></i>Dossiers récents
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Statut</th>
                                    <th>Créateur</th>
                                    <th>Service</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dossiersRecents as $dossier)
                                <tr>
                                    <td>{{ $dossier->id }}</td>
                                    <td>{{ $dossier->titre }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($dossier->statut == 'Créé') bg-info 
                                            @elseif($dossier->statut == 'En attente') bg-warning 
                                            @elseif($dossier->statut == 'Validé') bg-success 
                                            @elseif($dossier->statut == 'En traitement') bg-primary
                                            @elseif($dossier->statut == 'Transmis') bg-indigo
                                            @elseif($dossier->statut == 'Réaffecté') bg-pink
                                            @elseif($dossier->statut == 'Archivé') bg-secondary 
                                            @else bg-light text-dark @endif">
                                            {{ $dossier->statut }}
                                        </span>
                                    </td>
                                    <td>{{ $dossier->createur->name }}</td>
                                    <td>{{ $dossier->service->nom }}</td>
                                    <td>{{ $dossier->created_at->format('d/m/Y H:i') }}</td>
                                   <!-- Changez cette ligne dans le tableau des dossiers récents -->
<td>
    <div class="btn-group">
        <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-eye"></i>consulter
        </a>
        <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i>modifier
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
            
            <!-- Transferts récents -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>Transferts récents
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Dossier</th>
                                    <th>De</th>
                                    <th>À</th>
                                    <th>Date d'envoi</th>
                                    <th>Date de réception et validation</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfertsRecents as $transfert)
                                <tr>
                                    <td>
                                        <a href="{{ route('dossiers.show', $transfert->dossier_id) }}">
                                            {{ $transfert->dossier->titre }}
                                        </a>
                                    </td>
                                    <td>{{ $transfert->userSource->name ?? 'N/A' }}</td>
                                    <td>{{ $transfert->userDestination->name ?? 'N/A' }}</td>
                                    <td>{{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'En attente' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($transfert->statut == 'envoyé') bg-info 
                                            @elseif($transfert->statut == 'validé') bg-success 
                                            @elseif($transfert->statut == 'refusé') bg-danger
                                            @elseif($transfert->statut == 'réaffectation') bg-warning
                                            @elseif($transfert->statut == 'transmis') bg-indigo
                                            @else bg-light text-dark @endif">
                                            {{ $transfert->statut }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique Dossiers par service
    const serviceLabels = [
        @foreach($dossiersParService as $service)
            "{{ $service->nom }}",
        @endforeach
    ];
    
    const serviceData = [
        @foreach($dossiersParService as $service)
            {{ $service->dossiers_count }},
        @endforeach
    ];
    
    new Chart(document.getElementById('chartDossiersParService'), {
        type: 'bar',
        data: {
            labels: serviceLabels,
            datasets: [{
                label: 'Nombre de dossiers',
                data: serviceData,
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
    
    // Graphique Dossiers par statut
    const statutLabels = [
        @foreach($dossiersParStatut as $statut)
            "{{ $statut->statut }}",
        @endforeach
    ];
    
    const statutData = [
        @foreach($dossiersParStatut as $statut)
            {{ $statut->total }},
        @endforeach
    ];
    
    const statutColors = [
        @foreach($dossiersParStatut as $statut)
            @if($statut->statut == 'Créé') 'rgba(13, 202, 240, 0.7)'
            @elseif($statut->statut == 'En attente') 'rgba(255, 193, 7, 0.7)'
            @elseif($statut->statut == 'Validé') 'rgba(25, 135, 84, 0.7)'
            @elseif($statut->statut == 'En traitement') 'rgba(13, 110, 253, 0.7)'
            @elseif($statut->statut == 'Transmis') 'rgba(102, 16, 242, 0.7)'
            @elseif($statut->statut == 'Réaffecté') 'rgba(214, 51, 132, 0.7)'
            @elseif($statut->statut == 'Archivé') 'rgba(108, 117, 125, 0.7)'
            @else 'rgba(173, 181, 189, 0.7)' @endif,
        @endforeach
    ];
    
    new Chart(document.getElementById('chartDossiersParStatut'), {
        type: 'pie',
        data: {
            labels: statutLabels,
            datasets: [{
                data: statutData,
                backgroundColor: statutColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
});
</script>
@endsection