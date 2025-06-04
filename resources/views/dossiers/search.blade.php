@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">البحث المتقدم عن الملفات
                        <i class="fas fa-search text-primary me-2"></i>                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dossiers.search') }}" method="GET" id="searchForm">
                        <div class="row mb-3">
                            <!-- بحث بالكلمة المفتاحية -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keyword" class="form-label">الكلمة المفتاحية</label>
                                    <div class="input-group">
                                       
                                        <input type="text" class="form-control" id="keyword" name="keyword" 
                                               value="{{ request('keyword') }}" placeholder="البحث بالمصدر، المحتوى أو الرقم..."  style="text-align: center;">
                                    </div>
                                </div>
                            </div>
                           <!-- تصفية حسب الحالة -->
<div class="col-md-3">
    <div class="form-group">
        <label for="statut" class="form-label">الحالة</label>
        <select class="form-select" id="statut" name="statut">
            <option value=""  style="text-align: center;">جميع الحالات</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    @if($statut == 'Créé') تم الإنشاء
                    @elseif($statut == 'Validé') تمت المصادقة
                    @elseif($statut == 'En attente') قيد الانتظار
                    @elseif($statut == 'Archivé') مؤرشف
                    @elseif($statut == 'Transmis') مُحال
                    @else {{ $statut }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>
</div>
                            
                            <!-- تصفية حسب النوع -->
                           <div class="col-md-3">
    <div class="form-group">
        <label for="genre" class="form-label">النوع</label>
        <select class="form-select" id="genre" name="genre">
            <option value=""  style="text-align: center;">جميع الأنواع</option>
            @foreach($genres as $genre)
                <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                    {{ $genre }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<!-- تصفية حسب رقم النوع -->
<div class="col-md-3">
    <div class="form-group">
        <label for="genre_numero" class="form-label">رقم النوع</label>
        <input type="text" class="form-control" id="genre_numero" name="genre_numero"
               value="{{ request('genre_numero') }}" placeholder="مثال: 2526">
    </div>
</div>
                        
                        <div class="row mb-3">
                            <!-- تصفية حسب القسم -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="service_id" class="form-label">القسم</label>
                                    <select class="form-select" id="service_id" name="service_id">
                                        <option value=""  style="text-align: center;">جميع الأقسام</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- تصفية حسب المنشئ -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="createur_id" class="form-label">المنشئ</label>
                                    <select class="form-select" id="createur_id" name="createur_id">
                                        <option value=""  style="text-align: center;">جميع المنشئين</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('createur_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} {{ $user->fname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- تصفية حسب تاريخ البداية -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label">تاريخ البداية</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                           value="{{ request('date_debut') }}">
                                </div>
                            </div>
                            
                            <!-- تصفية حسب تاريخ النهاية -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label">تاريخ النهاية</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                           value="{{ request('date_fin') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <!-- خيارات الترتيب -->
                            <div class="col-md-3">
    <div class="form-group">
        <label for="sort_by" class="form-label">ترتيب حسب</label>
        <select class="form-select" id="sort_by" name="sort_by">
            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}  style="text-align: center;">تاريخ الإنشاء</option>
            <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}  style="text-align: center;">المصدر</option>
            <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}  style="text-align: center;">الحالة</option>
            <option value="numero_dossier_judiciaire" {{ request('sort_by') == 'numero_dossier_judiciaire' ? 'selected' : '' }}  style="text-align: center;">رقم الملف</option>
        </select>
    </div>
</div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_direction" class="form-label"  style="text-align: center;">الترتيب</label>
                                    <select class="form-select" id="sort_direction" name="sort_direction">
                                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}  style="text-align: center;">تنازلي</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}  style="text-align: center;">تصاعدي</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="observation" class="form-label">الملاحظة</label>
                                    <input type="text" class="form-control" id="observation" name="observation"
                                           value="{{ request('observation') }}" placeholder="بحث في الملاحظة">
                                </div>
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-1"></i> بحث
                                    </button>
                                    <button type="button" id="resetButton" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-redo me-1"></i> إعادة تعيين
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- نتائج البحث -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-list text-primary me-2"></i>نتائج البحث
                        <span class="badge bg-primary ms-2">{{ $dossiers->total() }} ملف</span>
                    </h5>
                    
                    @if($dossiers->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-1"></i> تصدير
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item" href="{{ route('dossiers.export', ['format' => 'csv'] + request()->all()) }}">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                @if($dossiers->count() > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>الرقم</th>
                                    <th>المصدر</th>
                                    <th>الحالة</th>
                                    <th>النوع</th>
                                    <th>المنشئ</th>
                                    <th>القسم</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->numero_dossier_judiciaire }}</td>
                                    <td>{{ $dossier->titre }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($dossier->statut == 'Créé') bg-info 
                                            @elseif($dossier->statut == 'Validé') bg-success 
                                            @elseif($dossier->statut == 'En attente') bg-warning 
                                            @elseif($dossier->statut == 'Archivé') bg-secondary 
                                            @elseif($dossier->statut == 'Transmis') bg-primary
                                            @else bg-light text-dark @endif">
                                            @if($dossier->statut == 'Créé') تم الإنشاء 
                                            @elseif($dossier->statut == 'Validé') تمت المصادقة 
                                            @elseif($dossier->statut == 'En attente') قيد الانتظار 
                                            @elseif($dossier->statut == 'Archivé') مؤرشف 
                                            @elseif($dossier->statut == 'Transmis') مُحال
                                            @else {{ $dossier->statut }} @endif
                                        </span>

                                    </td>
                                    <td>{{ $dossier->genre }}</td>
                                    <td>{{ $dossier->createur->name ?? 'غير متوفر' }}</td>
                                    <td>{{ $dossier->service->nom ?? 'غير متوفر' }}</td>
                                    <td> {{ $dossier->created_at ? $dossier->created_at->format('d/m/Y H:i') : ($dossier->date_creation ? $dossier->date_creation->format('d/m/Y H:i') : 'N/A') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                             <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> عرض
                                            </a>
                                            <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> تعديل
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
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $dossiers->links() }}
                    </div>
                </div>
                @else
                <div class="card-body text-center p-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>لم يتم العثور على أي ملف</h5>
                    <p class="text-muted">قم بتعديل معايير البحث للعثور على الملفات</p>
                </div>
                @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إعادة تعيين النموذج
    document.getElementById('resetButton').addEventListener('click', function() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.value = '';
        });
        
        form.submit();
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection