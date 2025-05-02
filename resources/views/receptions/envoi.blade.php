@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Envoyer un dossier</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('receptions.store-envoi') }}">
                        @csrf
                        <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                        
                        <div class="form-group mb-3">
                            <label for="dossier_info">Informations du dossier</label>
                            <div class="form-control bg-light">
                                <strong>ID:</strong> {{ $dossier->id }} <br>
                                <strong>Titre:</strong> {{ $dossier->titre ?? 'Non défini' }} <br>
                                <strong>Référence:</strong> {{ $dossier->reference ?? 'Non définie' }}
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="service_id">Service destinataire</label>
                            <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" onchange="updateUsersList()">
                                <option value="">Sélectionner un service</option>
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
                        
                        <div class="form-group mb-3">
                            <label for="user_id">Utilisateur destinataire</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un destinataire</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-service="{{ $user->service_id ?? '' }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="message">Message (optionnel)</label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="3"></textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Envoyer le dossier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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