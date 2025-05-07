
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js pour le menu déroulant -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    <!-- Sidebar CSS -->
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Style pour ajuster le contenu principal quand la sidebar est présente */
        @media (min-width: 992px) {
            .has-sidebar .min-h-screen {
                margin-left: 260px;
                width: calc(100% - 260px);
            }
        }
        
        /* Style responsive pour les petits écrans */
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar-wrapper.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 998;
                display: none;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }
        
/* CSS pour la sidebar */
.sidebar-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 260px;
    background-color: #2c3e50;
    color: #ecf0f1;
    z-index: 999;
    transition: all 0.3s ease;
}

.sidebar {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
    display: flex;
    align-items: center;
}

.sidebar-logo {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

.sidebar-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: #ecf0f1;
}

.sidebar-toggle {
    font-size: 18px;
    color: #ecf0f1;
    background: transparent;
    border: none;
    cursor: pointer;
}

.sidebar-user {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 20px;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

.user-role {
    font-size: 12px;
    color: #bdc3c7;
}

.sidebar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
    flex-grow: 1;
    overflow-y: auto;
}

.menu-header {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #bdc3c7;
    padding: 15px 20px 5px;
    margin-top: 10px;
}

.menu-item {
    position: relative;
    margin: 2px 0;
}

.menu-item.active .menu-link {
    background-color: rgba(255, 255, 255, 0.1);
    color: #3498db;
    border-left: 3px solid #3498db;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.menu-link:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: #3498db;
}

.menu-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.menu-link span {
    flex-grow: 1;
}

.has-dropdown .dropdown-icon {
    font-size: 10px;
    transition: transform 0.3s ease;
}

.menu-item.active .has-dropdown .dropdown-icon {
    transform: rotate(90deg);
}

.submenu {
    list-style: none;
    padding-left: 50px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.submenu.expanded {
    max-height: 200px;
}

.submenu li {
    margin: 2px 0;
}

.submenu li a {
    display: block;
    padding: 8px 10px;
    color: #bdc3c7;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    border-radius: 4px;
}

.submenu li a:hover {
    color: #3498db;
}

.submenu li.active a {
    color: #3498db;
    font-weight: 600;
}

.sidebar-footer {
    padding: 15px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-logout {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 8px 12px;
    color: #ecf0f1;
    background-color: rgba(231, 76, 60, 0.2);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background-color: rgba(231, 76, 60, 0.4);
}

.btn-logout i {
    margin-right: 10px;
}

/* Layout avec sidebar */
.main-content {
    margin-left: 260px;
    padding: 20px;
    transition: all 0.3s ease;
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar-wrapper {
        transform: translateX(-100%);
    }
    
    .sidebar-wrapper.active {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .main-content.sidebar-active {
        margin-left: 260px;
    }
}

/* Overlay pour fermer la sidebar sur mobile */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 998;
    display: none;
}

.sidebar-overlay.active {
    display: block;
}

    </style>
</head>
<body class="font-sans antialiased {{ Auth::check() && Auth::user()->role == 'greffier_en_chef' ? 'has-sidebar' : '' }}">
    @if(Auth::check() && Auth::user()->role == 'greffier_en_chef')
        <!-- Sidebar pour Greffier en Chef -->
        @include('partials.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Bouton pour ouvrir la sidebar sur mobile -->
        <button class="d-lg-none position-fixed top-0 start-0 mt-2 ms-2 btn btn-primary rounded-circle shadow-sm" id="mobileSidebarToggle" style="z-index: 997; width: 42px; height: 42px; padding: 0;">
            <i class="fas fa-bars"></i>
        </button>
    @endif
    
    <div class="min-h-screen bg-gray-100">
        @include('partials.header')
        
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <!-- Page Content -->
        <main>
            @yield('content', 'Contenu par défaut ici')
        </main>
    </div>
    
    <!-- Inclusion des scripts spécifiques -->
    @yield('scripts')
    
    <!-- Script pour la sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (mobileSidebarToggle && sidebarWrapper && sidebarOverlay) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebarWrapper.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
                
                sidebarOverlay.addEventListener('click', function() {
                    sidebarWrapper.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
            
            // Submenu Toggle
            const hasDropdowns = document.querySelectorAll('.has-dropdown');
            hasDropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const submenu = parent.querySelector('.submenu');
                    parent.classList.toggle('active');
                    submenu.classList.toggle('expanded');
                });
            });
            
            // Activer le submenu correspondant à la route actuelle
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
            
            menuItems.forEach(function(item) {
                const link = item.querySelector('.menu-link');
                const submenu = item.querySelector('.submenu');
                
                if (link && !link.classList.contains('has-dropdown') && link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                } else if (submenu) {
                    const submenuLinks = submenu.querySelectorAll('a');
                    submenuLinks.forEach(function(subLink) {
                        if (subLink.getAttribute('href') === currentPath) {
                            item.classList.add('active');
                            submenu.classList.add('expanded');
                            subLink.parentElement.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
    
</body>
</html>
```