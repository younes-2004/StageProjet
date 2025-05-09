@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit text-primary me-2"></i>تعديل الملف
                    </h5>
                    <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> العودة إلى الملف
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('dossiers.update', $dossier->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- معلومات الملف -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">معلومات الملف</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="numero_dossier_judiciaire" class="form-label">رقم الملف القضائي</label>
                                        <input type="text" class="form-control" id="numero_dossier_judiciaire" value="{{ $dossier->numero_dossier_judiciaire }}" readonly>
                                        <div class="form-text">لا يمكن تعديل رقم الملف.</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="genre" class="form-label">النوع</label>
                                        <input type="text" class="form-control @error('genre') is-invalid @enderror" id="genre" name="genre" value="{{ old('genre', $dossier->genre) }}" required>
                                        @error('genre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="titre" class="form-label">العنوان</label>
                                    <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $dossier->titre) }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contenu" class="form-label">المحتوى</label>
                                    <textarea class="form-control @error('contenu') is-invalid @enderror" id="contenu" name="contenu" rows="6" required>{{ old('contenu', $dossier->contenu) }}</textarea>
                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- معلومات إضافية للقراءة فقط -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">معلومات تكميلية (غير قابلة للتعديل)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">تم الإنشاء بواسطة</label>
                                            <input type="text" class="form-control bg-light" value="{{ $dossier->createur->name ?? 'غير محدد' }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الخدمة</label>
                                            <input type="text" class="form-control bg-light" value="{{ $dossier->service->nom ?? 'غير محدد' }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">الحالة الحالية</label>
                                            <input type="text" class="form-control bg-light" value="{{ $dossier->statut }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection