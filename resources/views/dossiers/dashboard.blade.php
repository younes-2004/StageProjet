@extends('layouts.app')

@section('content')
<style>
    .bg-indigo {
        background-color: #6610f2 !important;
    }
    
    .bg-pink {
        background-color: #d63384 !important;
    }
    /* Styles améliorés pour les boutons d'action */
    .btn-action {
        margin: 0 2px;
        border-radius: 4px;
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .btn-action i {
        margin-left: 5px;
    }
    
    /* Style spécifique pour le bouton consulter */
    .btn-primary.btn-action {
        border-radius: 4px;
    }
    
    /* Style spécifique pour le bouton modifier */
    .btn-warning.btn-action {
        border-radius: 4px;
    }
    
    /* Ajustement du groupe de boutons */
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
    /* Ajoutez ceci dans la section <style> existante */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
}

.card-welcome {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.card-welcome:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
</style>

<div class="container-fluid py-4" dir="rtl">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-welcome bg-gradient-primary shadow-lg rounded-3 overflow-hidden">
                <div class="card-body p-4 text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-6 fw-bold mb-3">
                                مرحباً، {{ Auth::user()->name }} {{ Auth::user()->fname }}
                            </h1>
                            <p class="lead mb-0">
                                مرحباً بك في لوحة معلومات إدارة الملفات
                            </p>
                        </div>
                        <div class="col-lg-4 text-center d-none d-lg-block">
                            <i class="fas fa-chart-line fa-5x text-white opacity-7"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reste du contenu existant -->
    <!-- Reste du contenu existant -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line text-primary me-2"></i>لوحة معلومات الملفات
                    </h5>
                </div>
                <div class="card-body">
                    <!-- الإحصائيات العامة -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <h6 class="mb-1">إجمالي الملفات</h6>
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
                                    <h6 class="mb-1">
                                        @if($statut->statut == 'Créé') تم الإنشاء
                                        @elseif($statut->statut == 'En attente') قيد الانتظار
                                        @elseif($statut->statut == 'Validé') تمت المصادقة 
                                        @elseif($statut->statut == 'En traitement') قيد المعالجة
                                        @elseif($statut->statut == 'Transmis') تم الإرسال
                                        @elseif($statut->statut == 'Réaffecté') تمت إعادة التخصيص
                                        @elseif($statut->statut == 'Archivé') مؤرشف
                                        @else {{ $statut->statut }} @endif
                                    </h6>
                                    <h3 class="mb-0 fw-bold">{{ $statut->total }}</h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- الرسوم البيانية -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">الملفات حسب القسم</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartDossiersParService" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">الملفات حسب الحالة</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartDossiersParStatut" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- الملفات الأخيرة -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-folder-open text-primary me-2"></i>الملفات الأخيرة
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                <th>رقم الملف بالمحكمة</th>
                                <th> المصدر</th>
                                <th>الحالة</th>
                                <th>المنشئ</th>
                                <th>القسم</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                                   
                                    
                                  
                                    
                                    
                                    
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dossiersRecents as $dossier)
                                <tr>
                                <td>{{ $dossier->numero_dossier_judiciaire }}</td>
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
                                            @if($dossier->statut == 'Créé') تم الإنشاء
                                            @elseif($dossier->statut == 'En attente') قيد الانتظار
                                            @elseif($dossier->statut == 'Validé') تمت المصادقة 
                                            @elseif($dossier->statut == 'En traitement') قيد المعالجة
                                            @elseif($dossier->statut == 'Transmis') تم الإرسال
                                            @elseif($dossier->statut == 'Réaffecté') تمت إعادة التخصيص
                                            @elseif($dossier->statut == 'Archivé') مؤرشف
                                            @else {{ $dossier->statut }} @endif
                                        </span>
                                    </td>
                                    <td>{{ $dossier->createur->name }}</td>
                                    <td>{{ $dossier->service->nom }}</td>
                                    <td>{{ $dossier->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
    <div class="btn-group">
        <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-warning btn-action">
            <i class="fas fa-edit"></i>تعديل
        </a>
        <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-sm btn-primary btn-action">
            <i class="fas fa-eye"></i>عرض
        </a>
        @if(auth()->user()->role === 'greffier_en_chef')
        <form action="{{ route('dossiers.destroy', $dossier->id) }}" method="POST" 
            class="d-inline delete-dossier-form" 
            data-dossier-titre="{{ $dossier->titre }}">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-sm btn-danger delete-dossier-btn">
                <i class="fas fa-trash-alt"></i> حذف
            </button>
        </form>
        @endif
    </div>
</td>
                                  
                                   
                                   
                                   
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- التحويلات الأخيرة -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>التحويلات الأخيرة
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                <th>الملف</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th>تاريخ الإرسال</th>
                                <th>تاريخ الاستلام والتحقق</th>
                                    <th>الحالة</th>
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
                                    <td>{{ $transfert->userSource->name ?? 'غير متوفر' }}</td>
                                    <td>{{ $transfert->userDestination->name ?? 'غير متوفر' }}</td>
                                    <td>{{ $transfert->date_envoi ? $transfert->date_envoi->format('d/m/Y H:i') : 'غير متوفر' }}</td>
                                    <td>{{ $transfert->date_reception ? $transfert->date_reception->format('d/m/Y H:i') : 'قيد الانتظار' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($transfert->statut == 'envoyé') bg-info 
                                            @elseif($transfert->statut == 'validé') bg-success 
                                            @elseif($transfert->statut == 'refusé') bg-danger
                                            @elseif($transfert->statut == 'réaffectation') bg-warning
                                            @elseif($transfert->statut == 'transmis') bg-indigo
                                            @else bg-light text-dark @endif">
                                            @if($transfert->statut == 'envoyé') تم الإرسال
                                            @elseif($transfert->statut == 'validé') تمت المصادقة
                                            @elseif($transfert->statut == 'refusé') مرفوض
                                            @elseif($transfert->statut == 'réaffectation') إعادة تخصيص
                                            @elseif($transfert->statut == 'transmis') تم التحويل
                                            @else {{ $transfert->statut }} @endif
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
    <!-- تأكيد الحذف مودال -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف الملف <strong id="deleteDossierTitre"></strong> ؟</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    هذا الإجراء لا يمكن التراجع عنه.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
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
                label: 'عدد الملفات',
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
            @if($statut->statut == 'Créé') "تم الإنشاء"
            @elseif($statut->statut == 'En attente') "قيد الانتظار"
            @elseif($statut->statut == 'Validé') "تمت المصادقة"
            @elseif($statut->statut == 'En traitement') "قيد المعالجة"
            @elseif($statut->statut == 'Transmis') "تم الإرسال"
            @elseif($statut->statut == 'Réaffecté') "تمت إعادة التخصيص"
            @elseif($statut->statut == 'Archivé') "مؤرشف"
            @else "{{ $statut->statut }}" @endif,
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
// إعداد حذف الملف
const deleteButtons = document.querySelectorAll('.delete-dossier-btn');
const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
let currentForm = null;

// اعتراض النقر على أزرار الحذف
deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
        // الحصول على النموذج وعنوان الملف
        currentForm = this.closest('form');
        const dossierTitre = currentForm.getAttribute('data-dossier-titre');
        
        // تحديث النص في النافذة المنبثقة
        document.getElementById('deleteDossierTitre').textContent = dossierTitre;
        
        // إظهار نافذة التأكيد
        deleteModal.show();
    });
});

// التعامل مع تأكيد الحذف
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentForm) {
        // إرسال النموذج
        currentForm.submit();
        
        // إغلاق النافذة المنبثقة
        deleteModal.hide();
    }
});
</script>
@endsection