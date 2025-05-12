@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- En-tête de bienvenue -->
        <div class="col-12 mb-4">
            <div class="card bg-gradient-primary shadow-lg rounded-3 overflow-hidden">
                <div class="card-body p-4 text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-6 fw-bold mb-3">
                                مرحباً، {{ Auth::user()->name }} {{ Auth::user()->fname }}
                            </h1>
                            <p class="lead mb-0">
                                مرحباً بك في نظام إدارة الملفات القضائية. 
                                لديك {{ Auth::user()->dossiersCreated()->count() }} ملف قيد الإدارة.
                            </p>
                        </div>
                        <div class="col-lg-4 text-center d-none d-lg-block">
                            <i class="fas fa-user-circle fa-5x text-white opacity-7"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes de statistiques rapides -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-stat shadow-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                                <i class="fas fa-folder-open"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">الملفات المنشأة</h5>
                            <span class="h3 font-weight-bold mb-0">
                                {{ Auth::user()->dossiersCreated()->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-stat shadow-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                                <i class="fas fa-inbox"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">الملفات المستلمة</h5>
                            <span class="h3 font-weight-bold mb-0">
                                {{ \App\Models\Reception::where('user_id', Auth::id())->where('traite', false)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-stat shadow-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">الملفات المصادق عليها</h5>
                            <span class="h3 font-weight-bold mb-0">
                                {{ \App\Models\DossierValide::where('user_id', Auth::id())->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-stat shadow-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="card-title text-muted mb-0">إجمالي التحويلات</h5>
                            <span class="h3 font-weight-bold mb-0">
                                {{ \App\Models\Transfert::where('user_source_id', Auth::id())->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">الإجراءات السريعة</h4>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('dossiers.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-plus-circle me-2"></i> إنشاء ملف جديد
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('receptions.inbox') }}" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-inbox me-2"></i> صندوق الوارد
                                <span class="badge bg-danger ms-2">
                                    {{ \App\Models\Reception::where('user_id', Auth::id())->where('traite', false)->count() }}
                                </span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-check-circle me-2"></i> الملفات المصادق عليها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières activités -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-lg h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h4 class="mb-0">آخر النشاطات</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @php
                            $recentActivities = \App\Models\HistoriqueAction::where('user_id', Auth::id())
                                ->orderBy('date_action', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($recentActivities as $activity)
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">
                                            @switch($activity->action)
                                                @case('creation')
                                                    إنشاء ملف
                                                    @break
                                                @case('modification')
                                                    تعديل ملف
                                                    @break
                                                @case('transfert')
                                                    تحويل ملف
                                                    @break
                                                @case('validation')
                                                    مصادقة على ملف
                                                    @break
                                                @default
                                                    {{ $activity->action }}
                                            @endswitch
                                        </h6>
                                        <small class="text-muted">
                                            {{ $activity->date_action->diffForHumans() }}
                                        </small>
                                    </div>
                                    <span class="badge bg-primary bg-gradient">
                                        {{ $activity->dossier->numero_dossier_judiciaire ?? 'N/A' }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted">
                                لا توجد نشاطات حتى الآن
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles personnalisés pour le tableau de bord */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #a71d2a);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #d39e00);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #117a8b);
    }

    .card-stat {
        transition: all 0.3s ease;
    }

    .card-stat:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .icon-shape {
        display: inline-flex;
        padding: 12px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }

    .btn-outline-primary:hover,
    .btn-outline-warning:hover,
    .btn-outline-success:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection