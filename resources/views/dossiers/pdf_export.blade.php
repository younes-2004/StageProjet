// resources/views/dossiers/pdf_export.blade.php

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export de dossiers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        
        .status-cree { background-color: #17a2b8; }
        .status-valide { background-color: #28a745; }
        .status-enattente { background-color: #ffc107; color: #333; }
        .status-archive { background-color: #6c757d; }
    </style>
</head>
<body>
    <h1>Export des dossiers - {{ date('d/m/Y') }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Titre</th>
                <th>Statut</th>
                <th>Genre</th>
                <th>Créateur</th>
                <th>Service</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dossiers as $dossier)
            <tr>
                <td>{{ $dossier->numero_dossier_judiciaire }}</td>
                <td>{{ $dossier->titre }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '', $dossier->statut)) }}">
                        {{ $dossier->statut }}
                    </span>
                </td>
                <td>{{ $dossier->genre }}</td>
                <td>{{ $dossier->createur->name ?? 'N/A' }}</td>
                <td>{{ $dossier->service->nom ?? 'N/A' }}</td>
                <td>{{ $dossier->date_creation ? $dossier->date_creation->format('d/m/Y') : ($dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré le {{ date('d/m/Y H:i:s') }} | Total: {{ $dossiers->count() }} dossier(s)</p>
    </div>
</body>
</html>