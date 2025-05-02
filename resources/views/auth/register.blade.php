<!-- filepath: resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="{{ asset('storage/index.css') }}" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h1>Ajouter un utilisateur</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nom -->
            <div>
                <label for="name">Nom</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <!-- Prénom -->
            <div>
                <label for="fname">Prénom</label>
                <input id="fname" type="text" name="fname" value="{{ old('fname') }}" required>
            </div>

            <!-- Email -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <!-- Rôle -->
            <div>
                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="greffier">Greffier</option>
                    <option value="greffier_en_chef">Greffier en chef</option>
                </select>
            </div>

            <!-- Service -->
            <div>
                <label for="service_id">Service</label>
                <select id="service_id" name="service_id" required>
    <option value="" disabled selected>Choisissez un service</option>
    @foreach($services as $service)
        <option value="{{ $service->id }}">{{ $service->nom }}</option>
    @endforeach
</select>
            </div>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
