@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>إدارة التحويلات
                    </h5>
                    
                    <!-- Bouton d'exportation -->
                    <a href="{{ route('transferts.export', request()->query()) }}" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> تصدير
                    </a>
                </div>

                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">التحويلات حسب الحالة</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="transfertsParStatutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">التحويلات حسب اليوم</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="transfertsParJourChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">التحويلات حسب القسم</h6>
                                    <div class="chart-container" style="height: 150px;">
                                        <canvas id="transfertsParServiceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <div class="mb-3 text-center">
                                        <h6 class="text-muted mb-1">متوسط وقت التحقق</h6>
                                          <div class="display-6 fw-bold text-primary">
                                            {{ round($tempsValidationMoyen->avg_validation_hours ?? 0) }}h
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h6 class="text-muted mb-1">التحويلات غير المتحقق منها</h6>
                                        <div class="display-6 fw-bold text-warning">
                                            {{ $transfertsNonValides ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">تصفية متقدمة</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transferts.index') }}" method="GET" id="searchForm"  style="text-align: center;">
                                <div class="row g-3">
                                    <!-- Statut -->
                                    <div class="col-md-3" >
                                        <label for="statut" class="form-label" >الحالة</label>
                                        <select id="statut" name="statut" class="form-select">
                                            <option value=""  style="text-align: center;">جميع الحالات</option>
                                            @foreach($statuts as $statut)
                                                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}  style="text-align: center;">
                                                    @if($statut == 'envoyé')
                                                        مرسل
                                                    @elseif($statut == 'reçu')
                                                        مستلم
                                                    @elseif($statut == 'validé')
                                                        متحقق
                                                    @elseif($statut == 'refusé')
                                                        مرفوض
                                                    @elseif($statut == 'réaffectation')
                                                        إعادة تعيين
                                                    @else
                                                        {{ ucfirst($statut) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Utilisateur source -->
                                    <div class="col-md-3">
                                        <label for="user_source_id" class="form-label">المرسل</label>
                                        <select id="user_source_id" name="user_source_id" class="form-select"  style="text-align: center;">
                                            <option value="">جميع المرسلين</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('user_source_id') == $user->id ? 'selected' : '' }} style="text-align: center;">
                                                    {{ $user->name }} {{ $user->fname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Utilisateur destination -->
                                    <div class="col-md-3">
                                        <label for="user_destination_id" class="form-label"  style="text-align: center;">المستلم</label>
                                        <select id="user_destination_id" name="user_destination_id" class="form-select">
                                            <option value=""  style="text-align: center;">جميع المستلمين</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('user_destination_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} {{ $user->fname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Service source -->
                                    <div class="col-md-3">
                                        <label for="service_source_id" class="form-label">قسم المصدر</label>
                                        <select id="service_source_id" name="service_source_id" class="form-select">
                                            <option value=""  style="text-align: center;">جميع أقسام المصدر</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ request('service_source_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Service destination -->
                                    <div class="col-md-3">
                                        <label for="service_destination_id" class="form-label" >قسم الوجهة</label>
                                        <select id="service_destination_id" name="service_destination_id" class="form-select"  style="text-align: center;">
                                            <option value=""  style="text-align: center;">جميع أقسام الوجهة</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ request('service_destination_id') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Dossier -->
                                    <div class="col-md-3">
                                        <label for="dossier_id" class="form-label">ملف</label>
                                        <select id="dossier_id" name="dossier_id" class="form-select"  style="text-align: center;">
                                            <option value="">جميع الملفات</option>
                                            @foreach($dossiers as $dossier)
                                                <option value="{{ $dossier->id }}" {{ request('dossier_id') == $dossier->id ? 'selected' : '' }}>
                                                    {{ $dossier->numero_dossier_judiciaire }} - {{ $dossier->titre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Date envoi début -->
                                    <div class="col-md-3">
                                        <label for="date_envoi_debut" class="form-label">تاريخ الإرسال (البداية)</label>
                                        <input type="date" id="date_envoi_debut" name="date_envoi_debut" class="form-control" value="{{ request('date_envoi_debut') }}">
                                    </div>
                                    
                                    <!-- Date envoi fin -->
                                    <div class="col-md-3">
                                        <label for="date_envoi_fin" class="form-label">تاريخ الإرسال (النهاية)</label>
                                        <input type="date" id="date_envoi_fin" name="date_envoi_fin" class="form-control" value="{{ request('date_envoi_fin') }}">
                                    </div>
                                    
                                    <!-- Date réception début -->
                                    <div class="col-md-3">
                                        <label for="date_reception_debut" class="form-label">تاريخ الاستلام (البداية)</label>
                                        <input type="date" id="date_reception_debut" name="date_reception_debut" class="form-control" value="{{ request('date_reception_debut') }}">
                                    </div>
                                    
                                    <!-- Date réception fin -->
                                    <div class="col-md-3">
                                        <label for="date_reception_fin" class="form-label">تاريخ الاستلام (النهاية)</label>
                                        <input type="date" id="date_reception_fin" name="date_reception_fin" class="form-control" value="{{ request('date_reception_fin') }}">
                                    </div>
                                    
                                    <!-- État de validation -->
                                    <div class="col-md-3" >
                                        <label for="valide" class="form-label" >حالة التحقق</label>
                                        <select id="valide" name="valide" class="form-select">
                                            <option value=""  style="text-align: center;">الكل</option>
                                            <option value="1" {{ request('valide') == '1' ? 'selected' : '' }}>متحقق منها</option>
                                            <option value="0" {{ request('valide') == '0' ? 'selected' : '' }}>غير متحقق منها</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Recherche par mot-clé -->
                                    <div class="col-md-3">
                                        <label for="keyword" class="form-label">كلمة مفتاحية في التعليقات</label>
                                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="بحث..." value="{{ request('keyword') }}">
                                    </div>
                                    
                                    <!-- Tri -->
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="sort_by" class="form-label">ترتيب حسب</label>
                                                <select id="sort_by" name="sort_by" class="form-select"  style="text-align: center;">
                                                    <option value="date_envoi" {{ request('sort_by') == 'date_envoi' ? 'selected' : '' }}  style="text-align: left;">تاريخ الإرسال</option>
                                                    <option value="date_reception" {{ request('sort_by') == 'date_reception' ? 'selected' : '' }}  style="text-align: center;">تاريخ الاستلام</option>
                                                    <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}  style="text-align: center;">الحالة</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="sort_direction" class="form-label">الترتيب</label>
                                                <select id="sort_direction" name="sort_direction" class="form-select">
                                                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}  style="text-align: center;">تنازلي</option>
                                                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}  style="text-align: center;">تصاعدي</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="d-flex gap-2 w-100">
                                            <button type="button" id="resetButton" class="btn btn-secondary flex-grow-1">
                                                <i class="fas fa-redo me-1"></i>إعادة تعيين
                                            </button>
                                            <button type="submit" class="btn btn-primary flex-grow-1">
                                                <i class="fas fa-search me-1"></i>تصفية
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Liste des transferts -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">قائمة التحويلات</h6>
                            <span class="badge bg-primary">{{ $transferts->total() }} نتيجة</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%;">رقم</th>
                                            <th style="width: 11%;">ملف</th>
                                            <th style="width: 15%;">المصدر</th>
                                            <th style="width: 15%;">الوجهة</th>
                                            <th style="width: 12%;">تاريخ الإرسال</th>
                                            <th style="width: 12%;">تاريخ الاستلام</th>
                                            <th style="width: 8%;">الحالة</th>
                                            <th style="width: 15%;">تعليق</th>
                                            <th style="width: 7%;">إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transferts as $transfert)
                                            <tr>
                                                <td>{{ $transfert->id }}</td>
                                                <td>
                                                    <a href="{{ route('dossiers.show', $transfert->dossier_id) }}" class="text-decoration-none">
                                                        {{ $transfert->dossier->numero_dossier_judiciaire ?? 'غير متوفر' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>
                                                            <i class="fas fa-user me-1 text-primary"></i>
                                                            {{ $transfert->userSource->name ?? 'غير متوفر' }}
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="fas fa-building me-1"></i>
                                                            {{ $transfert->serviceSource->nom ?? 'غير متوفر' }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>
                                                            <i class="fas fa-user me-1 text-primary"></i>
                                                            {{ $transfert->userDestination->name ?? 'غير متوفر' }}
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="fas fa-building me-1"></i>
                                                            {{ $transfert->serviceDestination->nom ?? 'غير متوفر' }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'غير متوفر' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($transfert->date_reception)
                                                        <span class="text-success">
                                                            <i class="far fa-calendar-check me-1"></i>
                                                            {{ $transfert->date_reception->format('d/m/Y H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="text-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            قيد الانتظار
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($transfert->statut == 'envoyé') bg-info 
                                                        @elseif($transfert->statut == 'reçu') bg-primary 
                                                        @elseif($transfert->statut == 'validé') bg-success 
                                                        @elseif($transfert->statut == 'refusé') bg-danger
                                                        @elseif($transfert->statut == 'réaffectation') bg-warning
                                                        @else bg-secondary @endif">
                                                        @if($transfert->statut == 'envoyé')
                                                            مرسل
                                                        @elseif($transfert->statut == 'reçu')
                                                            مستلم
                                                        @elseif($transfert->statut == 'validé')
                                                            متحقق
                                                        @elseif($transfert->statut == 'refusé')
                                                            مرفوض
                                                        @elseif($transfert->statut == 'réaffectation')
                                                            إعادة تعيين
                                                        @else
                                                            {{ ucfirst($transfert->statut) }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($transfert->commentaire)
                                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $transfert->commentaire }}">
                                                            {{ $transfert->commentaire }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted fst-italic">لا يوجد تعليق</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('transferts.show', $transfert->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-3">
                                                    <i class="fas fa-info-circle text-info me-1"></i>
                                                    لم يتم العثور على تحويلات بالمعايير المحددة
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-center">
                                {{ $transferts->appends(request()->except('page'))->links() }}
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
    
    // Graphique Transferts par statut
    const transfertsParStatutChart = new Chart(
        document.getElementById('transfertsParStatutChart'),
        {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($transfertsParStatut as $data)
                        @if($data->statut == 'envoyé')
                            'مرسل',
                        @elseif($data->statut == 'reçu')
                            'مستلم',
                        @elseif($data->statut == 'validé')
                            'متحقق',
                        @elseif($data->statut == 'refusé')
                            'مرفوض',
                        @elseif($data->statut == 'réaffectation')
                            'إعادة تعيين',
                        @else
                            '{{ ucfirst($data->statut) }}',
                        @endif
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($transfertsParStatut as $data)
                            {{ $data->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(13, 202, 240, 0.7)', // Info (envoyé)
                        'rgba(13, 110, 253, 0.7)', // Primary (reçu)
                        'rgba(25, 135, 84, 0.7)',  // Success (validé)
                        'rgba(220, 53, 69, 0.7)',  // Danger (refusé)
                        'rgba(255, 193, 7, 0.7)'   // Warning (réaffectation)
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
    
    // Graphique Transferts par jour
    const transfertsParJourChart = new Chart(
        document.getElementById('transfertsParJourChart'),
        {
            type: 'line',
            data: {
                labels: [
                    @foreach($transfertsParJour as $transfert)
                        '{{ \Carbon\Carbon::parse($transfert->date)->format('d/m') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'التحويلات',
                    data: [
                        @foreach($transfertsParJour as $transfert)
                            {{ $transfert->total }},
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
    
    // Graphique Transferts par service
    const transfertsParServiceChart = new Chart(
        document.getElementById('transfertsParServiceChart'),
        {
            type: 'bar',
            data: {
                labels: [
                    @foreach($transfertsParService as $transfert)
                        '{{ $transfert->serviceDestination->nom ?? 'غير متوفر' }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'التحويلات',
                    data: [
                        @foreach($transfertsParService as $transfert)
                            {{ $transfert->total }},
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
    
    .card-header {
        padding: 1.25rem 1.5rem;
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
        
        .row > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection