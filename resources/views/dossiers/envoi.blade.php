@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">إرسال الملف: {{ $dossier->titre }}</h1>

    <form method="GET" action="{{ route('dossiers.envoyer', $dossier->id) }}">
        <!-- اختيار الخدمة -->
        <div class="mb-4">
            <label for="service_id" class="block text-sm font-medium text-gray-700">الخدمة</label>
            <select id="service_id" name="service_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- اختر خدمة --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if(request('service_id'))
    <form method="POST" action="{{ route('dossiers.traiter_envoi', $dossier->id) }}">
        @csrf
        <input type="hidden" name="service_id" value="{{ request('service_id') }}">

        <!-- اختيار المستخدم -->
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">المستخدم</label>
            <select id="user_id" name="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- اختر مستخدم --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- التعليق -->
        <div class="mb-4">
            <label for="commentaire" class="block text-sm font-medium text-gray-700">التعليق</label>
            <textarea id="commentaire" name="commentaire" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <!-- زر الإرسال -->
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
            إرسال
        </button>
    </form>
    @endif
</div>
@endsection