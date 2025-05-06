<nav class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Partie principale avec les liens (à gauche) -->
        <div class="flex items-center space-x-uniform overflow-x-auto">
            <!-- Élément 1 -->
            <a href="{{ route('dossiers.mes_dossiers') }}" class="flex items-center text-blue-600 font-medium nav-item">
                <span class="bg-blue-100 text-blue-600 p-2 rounded-md mr-2">
                    <i class="fas fa-home"></i>
                </span>
                Principale
            </a>
            
            <!-- Élément 2 -->
            <a href="{{ route('dossiers.create') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md mr-2">
                    <i class="fas fa-tag"></i>
                </span>
                Nouveau Dossier
                <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full ml-2">50 nouveaux</span>
            </a>
            
            <!-- Élément 3 -->
            <a href="{{ route('receptions.inbox') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md mr-2">
                    <i class="fas fa-inbox"></i>
                </span>
                Boîte de réception
                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full ml-2">50 nouveaux</span>
            </a>
            
            <!-- Élément 4 -->
            <a href="{{ route('receptions.dossiers_valides') }}" class="flex items-center text-gray-700 font-medium nav-item">
                <span class="bg-gray-100 text-gray-600 p-2 rounded-md mr-2">
                    <i class="fas fa-check-circle"></i>
                </span>
                Dossiers validés
                <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full ml-2">50 nouveaux</span>
            </a>
        </div>
        
        <!-- Menu utilisateur (à droite) -->
        <div class="relative" x-data="{ open: false }">
            <div>
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                    <span class="bg-gray-100 text-gray-600 p-2 rounded-full">
                        <i class="fas fa-user"></i>
                    </span>
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            
            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white py-1 rounded-md shadow-lg z-50 border border-gray-200">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-edit mr-2"></i> Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Style pour assurer un espacement uniforme */
        .space-x-uniform > * {
            margin-right: 3rem;
        }
        
        .space-x-uniform > *:last-child {
            margin-right: 0;
        }
        
        /* Style pour que tous les éléments de navigation aient la même hauteur */
        .nav-item {
            height: 40px;
            display: flex;
            align-items: center;
        }
        
        /* Assurer que les icônes ont une taille constante */
        .nav-item span:first-child {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }
        
        /* Styles pour les badges (pour assurer une apparence cohérente) */
        .rounded-full {
            border-radius: 9999px;
        }
        
        /* Ajustement pour éviter l'écrasement sur les petits écrans */
        @media (max-width: 768px) {
            .overflow-x-auto {
                padding-bottom: 0.5rem;
            }
            
            .space-x-uniform > * {
                margin-right: 1rem;
            }
        }
    </style>
</nav>