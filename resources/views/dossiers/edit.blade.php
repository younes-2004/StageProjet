@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold fs-3">
                        <i class="fas fa-edit text-primary me-2"></i>تعديل الملف
                    </h5>
                    
                    <!-- رابط العودة -->
                    <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i> العودة إلى تفاصيل الملف
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
                    
                    <form method="POST" action="{{ route('dossiers.update', $dossier->id) }}" class="mt-2" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
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
                                        class="form-control rounded-3 border-light shadow-sm"
                                        value="{{ $dossier->numero_dossier_judiciaire }}"
                                        disabled>
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
                                        value="{{ $dossier->genre }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label fw-medium">
                                <i class="fas fa-heading text-secondary me-1"></i>
                              المصدر
                            </label>
                            <input 
                                id="titre" 
                                type="text" 
                                name="titre" 
                                required 
                                class="form-control rounded-3 border-light shadow-sm"
                                value="{{ $dossier->titre }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="type_contenu" class="form-label fw-medium">
                                <i class="fas fa-file-alt text-secondary me-1"></i>
                                نوع المحتوى
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_contenu" id="type_texte" value="texte" {{ $dossier->type_contenu == 'texte' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_texte">
                                    نص
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_contenu" id="type_pdf" value="pdf" {{ $dossier->type_contenu == 'pdf' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_pdf">
                                    ملف PDF
                                </label>
                            </div>
                        </div>
                        
                        <div id="contenu_texte_div" class="mb-4" {{ $dossier->type_contenu == 'pdf' ? 'style="display: none;"' : '' }}>
                            <label for="contenu_texte" class="form-label fw-medium">
                                <i class="fas fa-file-alt text-secondary me-1"></i>
                                المحتوى (نص)
                            </label>
                            <textarea 
                                id="contenu_texte" 
                                name="contenu_texte" 
                                rows="5" 
                                class="form-control rounded-3 border-light shadow-sm">{{ $dossier->type_contenu == 'texte' ? $dossier->contenu : '' }}</textarea>
                        </div>
                        
                        <div id="contenu_pdf_div" class="mb-4" {{ $dossier->type_contenu == 'texte' ? 'style="display: none;"' : '' }}>
                            @if($dossier->type_contenu == 'pdf')
                                <div class="mb-3">
                                    <label class="form-label fw-medium">
                                        <i class="fas fa-file-pdf text-secondary me-1"></i>
                                        الملف الحالي
                                    </label>
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="fas fa-file-pdf text-danger fs-2 me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ basename($dossier->contenu) }}</h6>
                                            <div class="my-2">
                                                <a href="{{ asset('storage/' . $dossier->contenu) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-eye me-1"></i> عرض الملف
                                                </a>
                                                <a href="{{ asset('storage/' . $dossier->contenu) }}" class="btn btn-sm btn-secondary ms-2" download>
                                                    <i class="fas fa-download me-1"></i> تحميل
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="remplacer_pdf" name="remplacer_pdf" value="1">
                                    <label class="form-check-label" for="remplacer_pdf">
                                        استبدال الملف الحالي بملف جديد
                                    </label>
                                </div>
                                
                                <div id="nouveau_pdf_div" style="display: none;">
                                    <label for="contenu_pdf" class="form-label fw-medium">
                                        <i class="fas fa-file-pdf text-secondary me-1"></i>
                                        الملف الجديد (PDF)
                                    </label>
                                    <input 
                                        type="file" 
                                        id="contenu_pdf" 
                                        name="contenu_pdf" 
                                        accept=".pdf" 
                                        class="form-control rounded-3 border-light shadow-sm">
                                    <small class="text-muted">الحد الأقصى للحجم: 1 جيجابايت</small>
                                    
                                    <div class="progress mt-2 d-none" id="upload-progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            @else
                                <label for="contenu_pdf" class="form-label fw-medium">
                                    <i class="fas fa-file-pdf text-secondary me-1"></i>
                                    المحتوى (ملف PDF)
                                </label>
                                <input 
                                    type="file" 
                                    id="contenu_pdf" 
                                    name="contenu_pdf" 
                                    accept=".pdf" 
                                    class="form-control rounded-3 border-light shadow-sm">
                                <small class="text-muted">الحد الأقصى للحجم: 1 جيجابايت</small>
                                
                                <div class="progress mt-2 d-none" id="upload-progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('dossiers.detail', $dossier->id) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script pour basculer entre les options de contenu
    document.addEventListener('DOMContentLoaded', function() {
        const typeTexte = document.getElementById('type_texte');
        const typePdf = document.getElementById('type_pdf');
        const contenuTexteDiv = document.getElementById('contenu_texte_div');
        const contenuPdfDiv = document.getElementById('contenu_pdf_div');
        
        typeTexte.addEventListener('change', function() {
            if (this.checked) {
                contenuTexteDiv.style.display = 'block';
                contenuPdfDiv.style.display = 'none';
            }
        });
        
        typePdf.addEventListener('change', function() {
            if (this.checked) {
                contenuTexteDiv.style.display = 'none';
                contenuPdfDiv.style.display = 'block';
            }
        });
        
        // Gestion de l'option "remplacer le PDF"
        const remplacerPdf = document.getElementById('remplacer_pdf');
        const nouveauPdfDiv = document.getElementById('nouveau_pdf_div');
        
        if (remplacerPdf) {
            remplacerPdf.addEventListener('change', function() {
                if (this.checked) {
                    nouveauPdfDiv.style.display = 'block';
                } else {
                    nouveauPdfDiv.style.display = 'none';
                }
            });
        }
        
        // Gestion de la barre de progression pour les téléchargements volumineux
        const form = document.querySelector('form');
        const progressBar = document.getElementById('upload-progress');
        
        if (form && progressBar) {
            form.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('contenu_pdf');
                if (fileInput && fileInput.files.length > 0 && fileInput.files[0].size > 5 * 1024 * 1024) { // > 5MB
                    e.preventDefault();
                    
                    const xhr = new XMLHttpRequest();
                    const formData = new FormData(form);
                    
                    // Afficher la barre de progression
                    progressBar.classList.remove('d-none');
                    
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percentage = Math.round((e.loaded / e.total) * 100);
                            const progressBarInner = progressBar.querySelector('.progress-bar');
                            progressBarInner.style.width = percentage + '%';
                            progressBarInner.textContent = percentage + '%';
                        }
                    });
                    
                    xhr.addEventListener('load', function() {
                        if (xhr.status === 200) {
                            window.location.href = xhr.responseURL;
                        } else {
                            progressBar.classList.add('d-none');
                            alert('حدث خطأ أثناء تحميل الملف. الرجاء المحاولة مرة أخرى.');
                        }
                    });
                    
                    xhr.open('POST', form.action, true);
                    xhr.send(formData);
                }
            });
        }
    });
</script>

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
</style>
@endsection