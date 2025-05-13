<x-guest-layout>
<div class="login-card">
    <!-- Image - Côté gauche -->
    <div class="image-side">
        <img src="{{ asset('image.png') }}" alt="صورة محكمة الاستئناف">
        <div class="image-overlay">
            <h2 class="overlay-title">محكمة الاستئناف خريبكة</h2>
            <p class="overlay-text">منصة إلكترونية لتسهيل الإجراءات القضائية وتحسين خدمات المواطنين</p>
        </div>
    </div>
    
    <!-- Formulaire de connexion - Côté droit -->
    <div class="form-side">
        <!-- Logo et titres -->
        <div class="logo-container">
            <img src="{{ asset('logo.png') }}" alt="شعار وزارة العدل" class="logo">
            <h1 class="login-title text-2xl font-bold">محكمة الاستئناف خريبكة</h1>
            <h2 class="login-subtitle text-lg text-gray-600">وزارة العدل</h2>
        </div>
        
        <!-- Formulaire -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                    class="form-input" required autofocus autocomplete="username">
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <div class="password-container">
                    <input id="password" type="password" name="password" 
                        class="form-input" required autocomplete="current-password">
                    <span class="eye-icon" onclick="togglePasswordVisibility()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-amber-500 text-amber-600 focus:ring-amber-500">
                <label for="remember_me" class="mr-2 text-sm text-gray-600">تذكرني</label>
            </div>
            
            <!-- Login Button -->
            <button type="submit" class="login-button">
                تسجيل الدخول
            </button>
        </form>
        
        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} وزارة العدل - جميع الحقوق محفوظة</p>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }
</script>

</x-guest-layout>
