@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Réaffecter le dossier : {{ $dossier->titre }}</h1>

    <form method="POST" action="{{ route('dossiers.reaffecter.store', $dossier->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="dossier_info" class="form-label">Informations du dossier</label>
            <div class="form-control bg-light">
                <strong>ID:</strong> {{ $dossier->id }} <br>
                <strong>Titre:</strong> {{ $dossier->titre ?? 'Non défini' }} <br>
                <strong>Référence:</strong> {{ $dossier->reference ?? 'Non définie' }}
            </div>
        </div>

        <div class="mb-3">
            <label for="service_id" class="form-label">Service :</label>
            <select id="service_id" name="service_id" class="form-control @error('service_id') is-invalid @enderror" required onchange="updateUsersList()">
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

        <div class="mb-3">
            <label for="user_id" class="form-label">Réaffecter à :</label>
            <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
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

        <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire :</label>
            <textarea id="commentaire" name="commentaire" class="form-control @error('commentaire') is-invalid @enderror" rows="3"></textarea>
            @error('commentaire')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group d-flex justify-content-between">
            <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Réaffecter</button>
        </div>
    </form>
</div>

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