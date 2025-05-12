@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-folder-plus text-primary me-2"></i>إنشاء ملف جديد
                    </h5>
                    
                    <!-- رابط العودة -->
                    <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i> العودة إلى الملفات
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <!-- بطاقة التأكيد الجديدة -->
                        <div class="confirmation-card mb-4">
                            <div class="card border-0 bg-success-subtle">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="confirmation-icon bg-success text-white me-3">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="confirmation-content">
                                        <h5 class="mb-1 fw-bold">تم إنشاء الملف بنجاح!</h5>
                                        <p class="mb-0">{{ session('success') }}</p>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="{{ route('dossiers.show', session('dossier_id')) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye me-1"></i> عرض الملف
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('dossiers.store') }}" class="mt-2">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_dossier_judiciaire" class="form-label fw-medium">
                                        <i class="fas fa-hashtag text-secondary me-1"></i>
                                        رقم الملف بالمحكمة
                                    </label>
                                    <input 
                                        id="numero_dossier_judiciaire" 
                                        type="text" 
                                        name="numero_dossier_judiciaire" 
                                        required 
                                        class="form-control rounded-3 border-light shadow-sm"
                                        placeholder="أدخل رقم الملف">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="genre" class="form-label fw-medium">
                                        <i class="fas fa-tag text-secondary me-1"></i>
                                        النوع
                                    </label>
                                    <input 
                                        id="genre" 
                                        type="text" 
                                        name="genre" 
                                        required 
                                        class="form-control rounded-3 border-light shadow-sm"
                                        placeholder="أدخل نوع الملف">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label fw-medium">
                                <i class="fas fa-heading text-secondary me-1"></i>
                                العنوان
                            </label>
                            <input 
                                id="titre" 
                                type="text" 
                                name="titre" 
                                required 
                                class="form-control rounded-3 border-light shadow-sm"
                                placeholder="أدخل عنوان الملف">
                        </div>
                        
                        <div class="mb-4">
                            <label for="contenu" class="form-label fw-medium">
                                <i class="fas fa-file-alt text-secondary me-1"></i>
                                المحتوى
                            </label>
                            <textarea 
                                id="contenu" 
                                name="contenu" 
                                rows="5" 
                                required 
                                class="form-control rounded-3 border-light shadow-sm"
                                placeholder="أدخل محتوى الملف"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* التنسيق المتناسق مع الصفحات الأخرى */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    .form-control {
        padding: 0.6rem 0.75rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    /* باقي الأنماط تبقى كما هي */
</style>
@endsection