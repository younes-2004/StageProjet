<nav class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Espace réservé à droite (au lieu de gauche) pour le bouton de menu -->
        <div class="w-12 flex-shrink-0 md:w-16">
            <!-- Bouton du sidebar, maintenant à droite -->
            <button id="sidebarToggle" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars fa-lg"></i>
            </button>
        </div>
        
        <!-- Partie principale avec les liens (au centre) -->
        <div class="flex items-center space-x-uniform overflow-x-auto flex-grow justify-center" dir="rtl">
            <!-- Élément 1 -->
            <a href="{{ route('dossiers.mes_dossiers') }}" class="flex items-center text-blue-600 font-medium nav-item">
                <span class="bg-blue-100 text-blue-600 p-2 rounded-md ml-2">
                    <i class="fas fa-home"></i>
                </span>
                <span class="nav-text">الرئيسية</span>
            </a>
            
            <!-- Élément 2 -->
            <a href="{{ route('dossiers.create') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md ml-2">
                    <i class="fas fa-tag"></i>
                </span>
                <span class="nav-text">ملف جديد</span>
            </a>
            
            @if(Auth::user()->role == 'greffier_en_chef')
                <a href="{{ route('users.index') }}" class="flex items-center text-gray-700 font-medium nav-item">
                    <span class="bg-gray-100 text-gray-600 p-2 rounded-md ml-2">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="nav-text">المستخدمين</span>
                </a>
                
                <a href="{{ route('services.index') }}" class="flex items-center text-gray-700 font-medium nav-item">
                    <span class="bg-gray-100 text-gray-600 p-2 rounded-md ml-2">
                        <i class="fas fa-building"></i>
                    </span>
                    <span class="nav-text">الخدمات</span>
                </a>
            @endif
            
            <!-- Élément 3 -->
            <a href="{{ route('receptions.inbox') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md ml-2">
                    <i class="fas fa-inbox"></i>
                </span>
                <span class="nav-text">صندوق الوارد</span>
            </a>
            
            <!-- Élément 4 -->
            <a href="{{ route('receptions.dossiers_valides') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md ml-2">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span class="nav-text">الملفات المصادق عليها</span>
            </a>
        </div>
        
        <!-- Menu utilisateur (à gauche pour RTL) -->
        <div class="relative flex-shrink-0" x-data="{ open: false }">
            <div>
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                    <span class="font-medium md:inline hidden">{{ Auth::user()->name }}</span>
                    <span class="bg-gray-100 text-gray-600 p-2 rounded-full">
                        <i class="fas fa-user"></i>
                    </span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            
            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute left-0 mt-2 w-48 bg-white py-1 rounded-md shadow-lg z-50 border border-gray-200">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-right">
                    <i class="fas fa-user-edit ml-2"></i> حسابي
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Style pour assurer un espacement uniforme */
        .space-x-uniform > * {
            margin-left: 1.5rem; /* Espacement entre les éléments (RTL) */
            margin-right: 0;
        }
        
        .space-x-uniform > *:last-child {
            margin-left: 0;
        }
        
        /* Style pour que tous les éléments de navigation aient la même hauteur */
        .nav-item {
            height: 40px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease; /* Animation douce au survol */
            padding: 0 0.5rem;
            border-radius: 0.375rem;
        }
        
        .nav-item:hover {
            background-color: rgba(237, 242, 247, 0.5); /* Fond léger au survol */
        }
        
        .nav-text {
            white-space: nowrap; /* Empêche le texte de se couper */
        }
        
        /* Assurer que les icônes ont une taille constante */
        .nav-item span:first-of-type {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }
        
        /* Styles pour les badges (pour assurer une apparence cohérente) */
        .rounded-full {
            border-radius: 9999px;
        }
        
        /* Ajustement pour éviter l'écrasement sur les petits écrans */
        @media (max-width: 1024px) {
            .space-x-uniform > * {
                margin-left: 0.75rem;
                margin-right: 0;
            }
            
            .nav-item {
                padding: 0 0.25rem;
            }
        }
        
        @media (max-width: 768px) {
            .overflow-x-auto {
                padding-bottom: 0.5rem;
                justify-content: flex-start; /* Alignement à droite sur petit écran pour RTL */
            }
            
            .nav-text {
                display: none; /* Cache le texte */
            }
            
            .nav-item span:first-of-type {
                margin-left: 0; /* Pas besoin de marge quand il n'y a pas de texte */
            }
            
            .space-x-uniform > * {
                margin-left: 0.5rem;
                margin-right: 0;
            }
        }
    </style>
</nav>