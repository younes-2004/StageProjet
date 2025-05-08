@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>Détails du transfert
                    </h5>
                    <a href="{{ route('transferts.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Informations principales du transfert -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Informations du transfert</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">ID</label>
                                        <p class="fw-medium">{{ $transfert->id }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Statut</label>
                                        <p>
                                            <span class="badge 
                                                @if($transfert->statut == 'envoyé') bg-info 
                                                @elseif($transfert->statut == 'reçu') bg-primary 
                                                @elseif($transfert->statut == 'validé') bg-success 
                                                @elseif($transfert->statut == 'refusé') bg-danger
                                                @elseif($transfert->statut == 'réaffectation') bg-warning 
                                                @else bg-secondary @endif">
                                                {{ ucfirst($transfert->statut) }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-3">