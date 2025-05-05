@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Réaffecter le dossier : {{ $dossier->titre }}</h1>

                    <form method="POST" action="{{ route('dossiers.reaffecter.store', $dossier->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="dossier_info" class="form-label fw-semibold">
                                <i class="fas fa-info-circle text-primary me-1"></i>Informations du dossier
                            </label>
                            <div class="p-3 bg-light border-0 rounded-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-2"><i class="fas fa-hashtag text-secondary me-1"></i> <strong>ID:</strong> {{ $dossier->id }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-2"><i class="fas fa-file-alt text-secondary me-1"></i> <strong>Titre:</strong> {{ $dossier->titre ?? 'Non défini' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-2"><i class="fas fa-tag text-secondary me-1"></i> <strong>Référence:</strong> {{ $dossier->reference ?? 'Non définie' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="service_id" class="form-label fw-semibold">
                                <i class="fas fa-building text-primary me-1"></i>Service :
                            </label>
                            <select id="service_id" name="service_id" class="form-select @error('service_id') is-invalid @enderror" required onchange="updateUsersList()">
                                <option value="">-- Choisir un service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->nom }}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">
                                <i class="fas fa-user text-primary me-1"></i>Réaffecter à :
                            </label>
                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Choisir un utilisateur --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-service="{{ $user->service_id ?? '' }}">
                                        {{ $user->name }} ({{ $user->email ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="commentaire" class="form-label fw-semibold">
                                <i class="fas fa-comment text-primary me-1"></i>Commentaire :
                            </label>
                            <textarea id="commentaire" name="commentaire" class="form-control @error('commentaire') is-invalid @enderror" rows="3" placeholder="Ajoutez un commentaire optionnel..."></textarea>
                            @error('commentaire')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-share me-1"></i> Réaffecter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-color: rgba(0, 0, 0, 0.1);
        border-radius: 0.375rem;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .btn {
        font-weight: 500;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
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
    
    .alert {
        border: none;
        border-radius: 0.5rem;
    }
    
    .rounded-3 {
        border-radius: 0.5rem !important;
    }
</style>

<script>
function updateUsersList() {
    const serviceId = document.getElementById('service_id').value;
    const userSelect = document.getElementById('user_id');
    const userOptions = userSelect.querySelectorAll('option');
    
    userOptions.forEach(option => {
        if (option.value === '') {
            return; // Skip the default option
        }
        
        const userServiceId = option.getAttribute('data-service');
        
        if (!serviceId || userServiceId === serviceId) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Reset selection
    userSelect.value = '';
}
</script>
@endsection