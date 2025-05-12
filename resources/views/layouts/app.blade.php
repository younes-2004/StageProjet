<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
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
                margin-right: 260px; /* Changer margin-left en margin-right pour RTL */
                width: calc(100% - 260px);
            }
        }
        
        /* Style responsive pour les petits écrans */
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                transform: translateX(100%); /* Inverser pour RTL */
                transition: transform 0.3s ease;
            }
            
            .sidebar-wrapper.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                right: 0; /* Changer left en right pour RTL */
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
        
/* CSS pour la sidebar - Ajusté pour RTL */
.sidebar-wrapper {
    position: fixed;
    top: 0;
    right: -280px; /* Changer left en right pour RTL */
    height: 100%;
    width: 280px;
    background: linear-gradient(135deg,rgba(6, 50, 80, 0.54),rgba(19, 41, 55, 0.71));
    color: #ecf0f1;
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1); /* Inverser l'ombre pour RTL */
    overflow-y: auto;
}

.sidebar-wrapper.active {
    right: 0; /* Changer left en right pour RTL */
}

.sidebar-header {
    padding: 25px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
    display: flex;
    align-items: center;
}

.sidebar-logo {
    width: 35px;
    height: 35px;
    margin-left: 12px; /* Changer margin-right en margin-left pour RTL */
    background-color: #3498db;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.sidebar-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    color: #fff;
    letter-spacing: 0.5px;
}

.sidebar-close {
    background: none;
    border: none;
    color: #ecf0f1;
    font-size: 22px;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.sidebar-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-user {
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #2980b9);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 15px; /* Changer margin-right en margin-left pour RTL */
    font-size: 22px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    color: #fff;
    letter-spacing: 0.3px;
}

.user-role {
    font-size: 13px;
    color: #bdc3c7;
    margin-top: 3px;
}

.sidebar-menu {
    list-style: none;
    margin: 0;
    padding: 15px 0;
    flex-grow: 1;
}

.menu-header {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #bdc3c7;
    padding: 15px 20px 8px;
    margin-top: 5px;
    letter-spacing: 1px;
}

.menu-item {
    position: relative;
    margin: 2px 10px;
    border-radius: 8px;
    overflow: hidden;
}

.menu-item.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.menu-item.active .menu-link {
    color: #3498db;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.2s ease;
    border-radius: 8px;
    text-align: right; /* Ajouter pour RTL */
}

.menu-link:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: #3498db;
}

.menu-link i {
    margin-left: 12px; /* Changer margin-right en margin-left pour RTL */
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.menu-link span {
    flex-grow: 1;
    font-size: 14px;
    font-weight: 500;
}

.has-dropdown .dropdown-icon {
    font-size: 10px;
    transform: rotate(180deg); /* Inverser pour RTL */
    transition: transform 0.3s ease;
}

.menu-item.active .has-dropdown .dropdown-icon {
    transform: rotate(90deg);
}

.submenu {
    list-style: none;
    padding: 5px 35px 5px 0; /* Inverser le padding pour RTL */
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
    padding: 8px 12px;
    color: #bdc3c7;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.2s ease;
    border-radius: 6px;
    text-align: right; /* Ajouter pour RTL */
}

.submenu li a:hover {
    color: #3498db;
    background-color: rgba(255, 255, 255, 0.03);
}

.submenu li.active a {
    color: #3498db;
    font-weight: 600;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.btn-logout {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 12px;
    color: #ecf0f1;
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.7), rgba(192, 57, 43, 0.7));
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
    text-align: right; /* Ajouter pour RTL */
}

.btn-logout:hover {
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.9), rgba(192, 57, 43, 0.9));
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-logout i {
    margin-left: 10px; /* Changer margin-right en margin-left pour RTL */
    font-size: 16px;
}

/* Style pour le bouton hamburger */
.menu-toggle {
    background: linear-gradient(135deg, #3498db, #2980b9);
    border: none;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}

.menu-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
}

.menu-toggle i {
    font-size: 18px;
}

/* Overlay pour fermer la sidebar */
.sidebar-overlay {
    position: fixed;
    top: 0;
    right: 0; /* Changer left en right pour RTL */
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.sidebar-overlay.active {
    display: block;
    opacity: 1;
}

/* Ajustement pour le contenu principal */
.main-content {
    transition: margin-right 0.3s ease; /* Changer margin-left en margin-right pour RTL */
}

.main-content.sidebar-active {
    margin-right: 280px; /* Changer margin-left en margin-right pour RTL */
}

/* Animation pour le bouton menu */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.menu-toggle.pulse {
    animation: pulse 1s infinite;
}
    .search-container {
        display: flex;
        align-items: center;
        direction: rtl; /* Gardons la direction RTL pour le texte arabe */
        margin: 15px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        width: 100%; /* Assurons-nous qu'elle prend toute la largeur */
    }
    
    .search-filters {
        display: flex;
        background-color: #f5f5f5;
    }
    
    .filter {
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        border-right: 1px solid #ddd;
    }
    
    .filter.active {
        background-color: #6c757d;
        color: white;
    }
    
    .search-input {
        flex-grow: 1;
        position: relative;
        display: flex; /* Ajouté pour aligner le bouton et l'input */
    }
    
    .search-input form {
        display: flex;
        width: 100%; /* Pour que le formulaire prenne toute la largeur */
    }
    
    .search-input input {
        width: 100%;
        padding: 10px 15px;
        border: none;
        outline: none;
    }
    
    /* Modification importante ici pour placer le bouton à gauche */
    .search-btn {
        background: none;
        border: none;
        padding: 0 15px;
        cursor: pointer;
        order: -1; /* Place le bouton avant l'input dans un contexte flex */
    }

    </style>
</head>
<!-- Modifiez la section du sidebar dans layouts/app.blade.php -->
<body class="font-sans antialiased">
    @if(Auth::check() && Auth::user()->role == 'greffier_en_chef')
        <!-- Sidebar pour Greffier en Chef - cachée par défaut -->
        <div class="sidebar-wrapper collapsed" id="sidebar">
            @include('partials.sidebar')
        </div>
        
        <!-- Overlay pour fermer la sidebar -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Bouton hamburger fixe en haut à droite (au lieu de gauche) -->
        <button class="position-fixed top-0 end-0 mt-3 me-3 btn btn-primary rounded-circle shadow menu-toggle" id="menuToggle" style="z-index: 999; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-bars"></i>
        </button>
    @endif
    
    <!-- Le reste du code... -->
    
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
            @yield('content', 'المحتوى الافتراضي هنا')
        </main>
    </div>
    
    <!-- Inclusion des scripts spécifiques -->
    @yield('scripts')
    
    <!-- Script pour la sidebar - Ajusté pour RTL -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.querySelector('.sidebar-close');
    
    if (menuToggle && sidebar) {
        // Fonction pour activer/désactiver la sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            
            // Optionnel: Ajouter un effet au bouton menu
            menuToggle.classList.toggle('pulse');
            
            // Déclencher l'événement resize pour les graphiques ou autres éléments
            window.dispatchEvent(new Event('resize'));
        }
        
        // Événements
        menuToggle.addEventListener('click', toggleSidebar);
        
        // Fermer la sidebar quand on clique sur l'overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
        
        // Fermer la sidebar quand on clique sur le bouton de fermeture
        if (sidebarClose) {
            sidebarClose.addEventListener('click', toggleSidebar);
        }
        
        // Gestion des sous-menus
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
        
        // Marquer le menu actif
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
    }
});
</script>
    
</body>
</html>