
            <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>محكمة الاستئناف خريبكة - وزارة العدل</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Styles personnalisés -->
        <style>
            /* Styles pour la page de connexion */
            body {
                direction: rtl;
                font-family: 'Tajawal', sans-serif;
                margin: 0;
                padding: 0;
            }
            
            .login-page {
                background-color: #f7f7f7;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            
            .login-card {
                width: 100%;
                max-width: 1100px;
                background: white;
                border-radius: 0.75rem;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
            }
            
            @media (min-width: 768px) {
                .login-card {
                    flex-direction: row;
                    height: 550px;
                }
            }
            
            .form-side {
                width: 100%;
                padding: 2rem 2rem;
                display: flex;
                flex-direction: column;
            }
            
            @media (min-width: 768px) {
                .form-side {
                    width: 50%;
                    padding: 2.5rem;
                }
            }
            
            .image-side {
                display: none;
                background-color: #934904;
                position: relative;
            }
            
            @media (min-width: 768px) {
                .image-side {
                    display: block;
                    width: 50%;
                }
            }
            
            .image-side img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
            
            .logo-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 2rem;
            }
            
            .logo {
                width: 120px;
                height: auto;
                margin-bottom: 1rem;
                transition: transform 0.3s ease;
                filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            }
            
            .logo:hover {
                transform: scale(1.05);
                filter: drop-shadow(0 6px 8px rgba(0, 0, 0, 0.15));
            }
            
            .login-title {
                text-align: center;
                margin-bottom: 0.25rem;
                font-size: 1.25rem;
                font-weight: 700;
                color: #4b5563;
            }
            
            .login-subtitle {
                text-align: center;
                color: #666;
                margin-bottom: 1.5rem;
                font-size: 1rem;
            }
            
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                background-color: #f9fafb;
                transition: all 0.3s ease;
                margin-bottom: 0.75rem;
                font-size: 0.95rem;
            }
            
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: #4b5563;
                font-size: 1rem;
            }
            
            .login-button {
                width: 100%;
                background-color: #92400e;
                color: white;
                font-weight: 600;
                font-size: 1rem;
                padding: 0.7rem 1rem;
                border-radius: 0.5rem;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 1rem;
                box-shadow: 0 4px 6px rgba(146, 64, 14, 0.2);
            }
            
            .login-button:hover {
                background-color: #78350f;
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(146, 64, 14, 0.3);
            }
            
            .password-container {
                position: relative;
            }
            
            .eye-icon {
                position: absolute;
                top: 50%;
                left: 0.75rem;
                transform: translateY(-50%);
                color: #9ca3af;
                cursor: pointer;
            }
            
            .remember-me {
                display: flex;
                align-items: center;
                margin-top: 0.5rem;
            }
            
            .remember-me input {
                margin-left: 0.5rem;
            }
            
            .footer {
                margin-top: auto;
                text-align: center;
                font-size: 0.75rem;
                color: #6b7280;
                padding-top: 1rem;
            }
            
            .error-message {
                color: #ef4444;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
            
            /* Animation du logo */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .logo-container {
                animation: fadeIn 0.8s ease;
            }
            
            /* Overlay sur l'image */
            .image-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(rgba(120, 53, 15, 0.7), rgba(146, 64, 14, 0.8));
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                padding: 2rem;
                text-align: center;
            }
            
            .overlay-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            
            .overlay-text {
                font-size: 1.125rem;
                max-width: 30ch;
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="login-page">
            {{ $slot }}
        </div>
    </body>
</html>