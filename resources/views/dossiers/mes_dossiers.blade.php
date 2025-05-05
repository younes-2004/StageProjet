@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="h2 font-weight-bold text-dark">Mes Dossiers</h1>
            <p class="text-muted mb-0">Gestion complète de vos dossiers judiciaires</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('receptions.inbox') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-inbox mr-2"></i>Boîte de réception
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
            <h2 class="h4 font-weight-bold text-dark border-left border-dark pl-2 py-1">
                <i class="fas fa-edit mr-2 text-secondary"></i>
                Dossiers non envoyés
            </h2>
            <p class="text-muted ml-4">Dossiers en cours de préparation</p>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="w-16">Numéro</th>
                            <th class="w-32">Titre</th>
                            <th class="w-16">Date création</th>
                            <th class="w-16">Statut</th>
                            <th class="w-16">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nonEnvoyes as $dossier)
                        <tr>
                            <td>
                                <span class="d-inline-block rounded-circle bg-secondary mr-2" style="width:12px;height:12px"></span>
                                {{ $dossier->numero_dossier_judiciaire }}
                            </td>
                            <td>{{ $dossier->titre }}</td>
                            <td class="text-muted">{{ $dossier->date_creation }}</td>
                            <td>
                                <span class="badge badge-secondary  text-dark">
                                    <i class="fas fa-edit mr-1  text-dark" ></i> Non envoyé
                                </span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('dossiers.show', $dossier->id) }}" 
                                       class="btn btn-primary btn-sm mr-2">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>
                                    <a href="{{ route('receptions.create-envoi', $dossier->id) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-paper-plane mr-1"></i> Envoyer
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
            <h2 class="h4 font-weight-bold text-dark border-left border-warning pl-2 py-1">
                <i class="fas fa-clock mr-2 text-warning"></i>
                Dossiers en attente
            </h2>
            <p class="text-muted ml-4">Dossiers envoyés en attente de validation</p>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-warning-light">
                        <tr>
                            <th class="w-16">Numéro</th>
                            <th class="w-32">Titre</th>
                            <th class="w-16">Destinataire</th>
                            <th class="w-16">Date Envoi</th>
                            <th class="w-16">Actions</th>
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
                                <span class="d-inline-block rounded-circle bg-warning mr-2" style="width:12px;height:12px"></span>
                                {{ $dossier->numero_dossier_judiciaire }}
                            </td>
                            <td>{{ $dossier->titre }}</td>
                            <td>{{ $transfert->userDestination->name ?? 'N/A' }}</td>
                            <td class="text-muted">
                                {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('dossiers.show', $dossier->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i> Voir
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
        <h4 class="alert-heading">Tous vos dossiers ont été validés</h4>
        <p class="mb-0">Aucun dossier en attente de traitement</p>
    </div>
    @endif

    <!-- History Dossiers Section -->
    <div class="mb-4">
        <div class="mb-3">
            <h2 class="h4 font-weight-bold text-dark border-left border-success pl-2 py-1">
                <i class="fas fa-archive mr-2 text-success"></i>
                Historique des dossiers validés
            </h2>
            <p class="text-muted ml-4">Dossiers transférés et validés</p>
        </div>

        @if(!isset($dossiersEnvoyes) || $dossiersEnvoyes->isEmpty())
        <div class="alert alert-success text-center">
            <i class="fas fa-history fa-3x text-success mb-3"></i>
            <h4 class="alert-heading">Aucun dossier validé pour le moment</h4>
            <p class="mb-0">L'historique apparaîtra ici après validation des dossiers</p>
        </div>
        @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-success-light">
                        <tr>
                            <th class="w-16">Numéro</th>
                            <th class="w-32">Titre</th>
                            <th class="w-16">Validé par</th>
                            <th class="w-16">Service</th>
                            <th class="w-16">Date Envoi</th>
                            <th class="w-16">Date Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossiersEnvoyes as $transfert)
                        <tr class="bg-success-light">
                            <td>
                                <span class="d-inline-block rounded-circle bg-success mr-2" style="width:12px;height:12px"></span>
                                {{ $transfert->dossier->numero_dossier_judiciaire }}
                            </td>
                            <td>{{ $transfert->dossier->titre }}</td>
                            <td>{{ $transfert->userDestination->name ?? 'N/A' }}</td>
                            <td>{{ $transfert->serviceDestination->nom ?? 'N/A' }}</td>
                            <td class="text-muted">
                                {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                            <span style="color: green; font-size: 0.875rem;">
    <i class="fas fa-check-circle me-1" style="color: green;"></i>
    {{ $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'Non validé' }}
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

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Custom styles */
    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .border-left {
        border-left: 4px solid !important;
    }
    .w-16 {
        width: 16.666667%;
    }
    .w-32 {
        width: 33.333333%;
    }
    .btn-primary {
        background: linear-gradient(to right, #0d6efd, #0b5ed7);
        border: none;
    }
    .btn-success {
        background: linear-gradient(to right, #198754, #157347);
        border: none;
    }
    .btn:hover {
        opacity: 0.9;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
</style>
@endsection