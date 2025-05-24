<!-- resources/views/partials/sidebar.blade.php -->
<div class="sidebar sidebar-right">
    <div class="sidebar-header">
        <div class="logo-container">
            <!-- Si vous avez un logo, utilisez-le ici -->
            <div class="sidebar-logo">
                <i class="fas fa-balance-scale"></i>
            </div>
            <h5 class="sidebar-title">نظام إدارة الملفات</h5>
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
        
    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <h6 class="user-name">{{ Auth::user()->name }} {{ Auth::user()->fname }}</h6>
            <span class="user-role">{{ Auth::user()->role == 'greffier_en_chef' ? 'رئيس كتاب الضبط' : 'كاتب ضبط' }}</span>
        </div>
    </div>
        
    <ul class="sidebar-menu">
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>لوحة التحكم</span>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('dossiers.*') ? 'active' : '' }}">
            <a href="#" class="menu-link has-dropdown">
                <i class="fas fa-folder"></i>
                <span>إدارة الملفات</span>
                <i class="fas fa-chevron-left dropdown-icon"></i>
            </a>
            <ul class="submenu {{ request()->routeIs('dossiers.*') ? 'expanded' : '' }}">
                <li class="{{ request()->routeIs('dossiers.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dossiers.dashboard') }}">لوحة معلومات الملفات</a>
                </li>
                <li class="{{ request()->routeIs('dossiers.mes_dossiers') ? 'active' : '' }}">
                    <a href="{{ route('dossiers.mes_dossiers') }}">ملفاتي</a>
                </li>
                <li class="{{ request()->routeIs('dossiers.create') ? 'active' : '' }}">
                    <a href="{{ route('dossiers.create') }}">إنشاء ملف</a>
                </li>
                <li class="{{ request()->routeIs('dossiers.search') ? 'active' : '' }}">
                    <a href="{{ route('dossiers.search') }}">بحث متقدم</a>
                </li>
                <li class="{{ request()->routeIs('dossiers.archives') ? 'active' : '' }}">
                    <a href="{{ route('dossiers.archives') }}">الأرشفة</a>
                </li>
            </ul>
        </li>
        
        <li class="menu-item {{ request()->routeIs('receptions.inbox') ? 'active' : '' }}">
            <a href="{{ route('receptions.inbox') }}" class="menu-link">
                <i class="fas fa-inbox"></i>
                <span>صندوق الوارد</span>
            </a>
        </li>
        
        <li class="menu-item {{ request()->routeIs('receptions.dossiers_valides') ? 'active' : '' }}">
            <a href="{{ route('receptions.dossiers_valides') }}" class="menu-link">
                <i class="fas fa-check-circle"></i>
                <span>الملفات المصادق عليها</span>
            </a>
        </li>
        
        @if(Auth::user()->role == 'greffier_en_chef')
            <li class="menu-header">الإدارة</li>
            
            <!-- NOUVELLE SECTION : Historique des actions -->
            <li class="menu-item {{ request()->routeIs('admin.historique.*') ? 'active' : '' }}">
                <a href="{{ route('historique.index') }}" class="menu-link">
                    <i class="fas fa-history"></i>
                    <span>سجل الإجراءات</span>
                </a>
            </li>
            
            <!-- NOUVELLE SECTION : Transferts -->
            <li class="menu-item {{ request()->routeIs('admin.transferts.*') ? 'active' : '' }}">
                <a href="{{ route('transferts.index') }}" class="menu-link">
                    <i class="fas fa-exchange-alt"></i>
                    <span>التحويلات</span>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="#" class="menu-link has-dropdown">
                    <i class="fas fa-users"></i>
                    <span>إدارة المستخدمين</span>
                    <i class="fas fa-chevron-left dropdown-icon"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('users.*') ? 'expanded' : '' }}">
                    <li class="{{ request()->routeIs('users.index') && !request()->has('tab') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">قائمة المستخدمين</a>
                    </li>
                    <li class="{{ request()->has('tab') && request('tab') == 'add-user' ? 'active' : '' }}">
                        <a href="{{ route('users.index', ['tab' => 'add-user']) }}">إضافة مستخدم</a>
                    </li>
                </ul>
            </li>
            
            <li class="menu-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                <a href="{{ route('services.index') }}" class="menu-link">
                    <i class="fas fa-building"></i>
                    <span>إدارة الخدمات</span>
                </a>
            </li>
        @endif
    </ul>
        
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</div>

