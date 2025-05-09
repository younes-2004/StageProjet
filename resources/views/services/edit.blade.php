@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-building text-primary me-2"></i>تعديل القسم
                    </h5>
                    <a href="{{ route('services.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> العودة إلى القائمة
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

                    <form method="POST" action="{{ route('services.update', $service->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Informations du service -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">معلومات القسم</h6>
                            </div>
                            <div class="card-body">
                                <!-- Nom -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label">اسم القسم</label>
                                    <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           name="nom" value="{{ old('nom', $service->nom) }}" required autofocus>
                                    @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">الوصف</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="4">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Statut du service -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold">إحصائيات</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="fw-bold">المستخدمون المرتبطون</h6>
                                                <div class="display-4 fw-bold text-primary">{{ $service->users()->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="fw-bold">الملفات المرتبطة</h6>
                                                <div class="display-4 fw-bold text-primary">{{ $service->dossiers()->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">
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

<style>
    /* Card styling */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Form styling */
    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-color: #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Button styling */
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
    
    .display-4 {
        font-size: 2.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn {
            width: 100%;
        }
        
        .row > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection