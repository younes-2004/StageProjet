<!-- resources/views/partials/sidebar.blade.php -->
<div class="sidebar-wrapper">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo" onerror="this.src='{{ asset('images/logo-placeholder.png') }}'">
                <h5 class="sidebar-title">GestDoss</h5>
            </div>
            <button class="btn sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-info">
                <h6 class="user-name">{{ Auth::user()->name }} {{ Auth::user()->fname }}</h6>
                <span class="user-role">{{ Auth::user()->role == 'greffier_en_chef' ? 'Greffier en chef' : 'Greffier' }}</span>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('dossiers.*') ? 'active' : '' }}">
                <a href="{{ route('dossiers.mes_dossiers') }}" class="menu-link">
                    <i class="fas fa-folder"></i>
                    <span>Mes dossiers</span>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('receptions.inbox') ? 'active' : '' }}">
                <a href="{{ route('receptions.inbox') }}" class="menu-link">
                    <i class="fas fa-inbox"></i>
                    <span>Boîte de réception</span>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('receptions.dossiers_valides') ? 'active' : '' }}">
                <a href="{{ route('receptions.dossiers_valides') }}" class="menu-link">
                    <i class="fas fa-check-circle"></i>
                    <span>Dossiers validés</span>
                </a>
            </li>
            
            @if(Auth::user()->role == 'greffier_en_chef')
                <li class="menu-header">Administration</li>
                
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="#" class="menu-link has-dropdown">
                        <i class="fas fa-users"></i>
                        <span>Gestion utilisateurs</span>
                        <i class="fas fa-chevron-right dropdown-icon"></i>
                    </a>
                    <ul class="submenu {{ request()->routeIs('users.*') ? 'expanded' : '' }}">
                        <li class="{{ request()->routeIs('users.index') && !request()->has('tab') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">Liste des utilisateurs</a>
                        </li>
                        <li class="{{ request()->has('tab') && request('tab') == 'add-user' ? 'active' : '' }}">
                            <a href="{{ route('users.index', ['tab' => 'add-user']) }}">Ajouter un utilisateur</a>
                        </li>
                    </ul>
                </li>
                
                <li class="menu-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <a href="#" class="menu-link has-dropdown">
                        <i class="fas fa-building"></i>
                        <span>Gestion des services</span>
                        <i class="fas fa-chevron-right dropdown-icon"></i>
                    </a>
                    <ul class="submenu {{ request()->routeIs('services.*') ? 'expanded' : '' }}">
                        <li class="{{ request()->routeIs('services.index') ? 'active' : '' }}">
                            <a href="#">Liste des services</a>
                        </li>
                        <li class="{{ request()->routeIs('services.create') ? 'active' : '' }}">
                            <a href="#">Ajouter un service</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>
</div>
```