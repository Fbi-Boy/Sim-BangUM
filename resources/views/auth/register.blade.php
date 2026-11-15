<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — SIM-BU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #F8F9FD; padding: 20px; }
        .auth-container { display: flex; width: 100%; max-width: 1000px; height: 650px; background: #fff; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); overflow: hidden; }
        
        /* PANEL KIRI (UNGU) */
        .auth-left { flex: 1; background-color: #6C63FF; padding: 40px; display: flex; flex-direction: column; justify-content: space-between; color: white; position: relative; }
        .brand { font-size: 24px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .brand-icon { background: white; color: #6C63FF; width: 32px; height: 32px; border-radius: 8px; display: flex; justify-content: center; align-items: center; font-size: 16px; }
        .left-text h1 { font-size: 32px; line-height: 1.3; margin-bottom: 10px; }
        .left-text p { font-size: 14px; opacity: 0.8; }
        .illustration { width: 100%; max-width: 300px; margin: 0 auto; display: block; }
        
        /* PANEL KANAN (FORM) */
        .auth-right { flex: 1.2; padding: 40px 60px; display: flex; flex-direction: column; justify-content: center; position: relative; overflow-y: auto; }
        .lang-switch { position: absolute; top: 30px; right: 40px; font-size: 12px; color: #999; cursor: pointer; }
        .auth-right h2 { font-size: 26px; color: #333; margin-bottom: 25px; font-weight: 600; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: #666; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px 0; border: none; border-bottom: 1px solid #ddd; font-size: 15px; outline: none; transition: 0.3s; background: transparent; }
        .form-group input:focus { border-bottom-color: #6C63FF; }
        
        .checkbox-group { display: flex; align-items: center; gap: 10px; font-size: 12px; color: #666; margin-bottom: 25px; }
        .checkbox-group input { width: auto; }
        .checkbox-group a { color: #6C63FF; text-decoration: none; }
        
        .btn-submit { width: 100%; padding: 14px; border-radius: 30px; border: none; background: #6C63FF; color: white; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: #5a52d5; }
        
        .divider { text-align: center; margin: 20px 0; position: relative; color: #999; font-size: 13px; }
        .divider::before, .divider::after { content: ''; position: absolute; top: 50%; width: 35%; height: 1px; background: #eee; }
        .divider::before { left: 0; } .divider::after { right: 0; }
        
        .social-login { display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; }
        .social-btn { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #eee; display: flex; justify-content: center; align-items: center; cursor: pointer; transition: 0.3s; color: #666; text-decoration: none; }
        .social-btn:hover { border-color: #6C63FF; color: #6C63FF; }
        
        .footer-link { text-align: center; font-size: 13px; color: #999; }
        .footer-link a { color: #6C63FF; text-decoration: none; font-weight: 600; }
        
        .error-msg { background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 8px; font-size: 12px; margin-bottom: 15px; }
        
        @media (max-width: 768px) {
            .auth-container { flex-direction: column; height: auto; }
            .auth-left { padding: 30px; text-align: center; }
            .illustration { display: none; }
            .auth-right { padding: 40px 30px; }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <!-- KIRI -->
    <div class="auth-left">
        <div class="brand">
            <div class="brand-icon">SB</div> SIM-BU
        </div>
        <div class="left-text">
            <h1>Bergabunglah<br>dengan SIM-BU.</h1>
            <p>Buat akun untuk mengakses layanan terpadu Bagian Umum Pemda.</p>
        </div>
        <svg class="illustration" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="250" cy="200" r="150" fill="#5a52d5"/>
            <rect x="150" y="220" width="200" height="20" rx="10" fill="#3b3399"/>
            <rect x="170" y="240" width="10" height="80" fill="#3b3399"/>
            <rect x="320" y="240" width="10" height="80" fill="#3b3399"/>
            <rect x="200" y="150" width="100" height="70" rx="5" fill="#fff"/>
            <circle cx="250" cy="120" r="30" fill="#fbcfe8"/>
            <path d="M230 110 Q250 90 270 110 Q280 130 250 150 Q220 130 230 110" fill="#f472b6"/>
            <rect x="220" y="160" width="60" height="40" rx="5" fill="#4c1d95"/>
            <circle cx="250" cy="180" r="10" fill="#a78bfa"/>
            <rect x="100" y="100" width="40" height="30" rx="5" fill="#fde047" transform="rotate(-20 100 100)"/>
            <circle cx="400" cy="100" r="15" fill="#fca5a5"/>
            <rect x="380" y="280" width="30" height="40" rx="5" fill="#60a5fa" transform="rotate(15 380 280)"/>
        </svg>
    </div>

    <!-- KANAN -->
    <div class="auth-right">
        <div class="lang-switch">Bahasa (ID) ▾</div>
        <h2>Buat Akun</h2>

        @if($errors->any())
            <div class="error-msg">
                <ul style="margin-left: 15px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Budi Santoso">
            </div>
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@sim-bu.local">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password">
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms" style="margin:0;cursor:pointer">Saya setuju dengan <a href="#">Syarat & Ketentuan</a></label>
            </div>

            <button type="submit" class="btn-submit">Daftar (Sign Up)</button>
        </form>

        <div class="divider">Atau daftar dengan</div>
        
        <div class="social-login">
            <a href="#" class="social-btn">G</a>
            <a href="#" class="social-btn">f</a>
            <a href="#" class="social-btn">in</a>
        </div>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
        </div>
    </div>
</div>

</body>
</html>
