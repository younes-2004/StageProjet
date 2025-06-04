@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الملفات المستلمة</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="numero_dossier_judiciaire" class="form-label">رقم الملف القضائي</label>
                                <input type="text" class="form-control" id="numero_dossier_judiciaire" name="numero_dossier_judiciaire"
                                       value="{{ request('numero_dossier_judiciaire') }}" placeholder="بحث برقم الملف">
                            </div>
                        </div>
                    </div>

                    @if($receptions->isEmpty())
                        <div class="alert alert-info">
                            لم تستلم أي ملفات حتى الآن.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الملف القضائي</th>
                                        <th>الملف</th>
                                        <th>المرجع</th>
                                        <th>المرسل</th>
                                        <th>تاريخ الاستلام</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receptions as $reception)
                                        <tr>
                                            <td>{{ $reception->numero_dossier_judiciaire }}</td>
                                            <td>{{ $reception->dossier->titre ?? 'بدون عنوان' }}</td>
                                            <td>{{ $reception->dossier->reference ?? 'غير محدد' }}</td>
                                            <td>{{ $reception->dossier->user->name ?? 'مجهول' }}</td>
                                            <td>{{ $reception->date_reception ? $reception->date_reception->format('d/m/Y H:i') : 'غير محدد' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('dossiers.show', $reception->dossier_id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        عرض
                                                    </a>
                                                    <a href="{{ route('receptions.mark-as-read', $reception->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        تمييز كمقروء
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $receptions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection