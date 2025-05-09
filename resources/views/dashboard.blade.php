<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
        <!-- الأزرار -->
        <a href="{{ route('dossiers.create') }}" class="btn btn-primary">إنشاء ملف جديد</a>
        <a href="{{ route('dossiers.mes_dossiers') }}" class="btn btn-primary">تصفح ملفاتي</a>
        <a href="{{ route('receptions.dossiers_valides') }}" class="btn btn-primary">عرض الملفات المصادق عليها</a>
        <!-- رابط صندوق الوارد -->
        <a href="{{ route('receptions.inbox') }}" class="btn btn-primary">
            عرض الملفات المستلمة
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("تم تسجيل دخولك!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>