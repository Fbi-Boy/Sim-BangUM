<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — SIM-BU</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:system-ui,sans-serif;background:linear-gradient(135deg,#1e40af 0%,#1e3a8a 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:12px;padding:40px;width:100%;max-width:420px;box-shadow:0 20px 40px rgba(0,0,0,.2)}
.brand{text-align:center;margin-bottom:32px}
.brand h1{font-size:32px;color:#1e40af;letter-spacing:2px}
.brand p{color:#6b7280;font-size:13px;margin-top:6px}
label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px}
input{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px;margin-bottom:16px}
input:focus{outline:none;border-color:#1e40af;box-shadow:0 0 0 3px rgba(30,64,175,.1)}
.btn{width:100%;padding:12px;background:#1e40af;color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:600;cursor:pointer}
.btn:hover{background:#1e3a8a}
.links{text-align:center;margin-top:20px;font-size:13px}
.links a{color:#1e40af;text-decoration:none;margin:0 8px}
.links a:hover{text-decoration:underline}
.error{background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;font-size:13px;margin-bottom:16px}
.success{background:#dcfce7;color:#166534;padding:10px;border-radius:6px;font-size:13px;margin-bottom:16px}
.info{text-align:center;font-size:11px;color:#9ca3af;margin-top:24px;padding-top:16px;border-top:1px solid #f3f4f6}
</style>
</head>
<body>
<div class="card">
<div class="brand">
<h1>SIM-BU</h1>
<p>Sistem Informasi Manajemen<br>Bagian Umum Sekretariat Daerah</p>
</div>

@if(session('status'))<div class="success">{{ session('status') }}</div>@endif
@if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif

<form method="POST" action="{{ route('login') }}">
@csrf
<label>Email</label>
<input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@sim-bu.local">
<label>Password</label>
<input type="password" name="password" required placeholder="••••••••">
<label style="display:flex;align-items:center;font-weight:normal;margin-bottom:16px">
<input type="checkbox" name="remember" style="width:auto;margin-right:8px;margin-bottom:0"> Ingat saya
</label>
<button class="btn">Masuk</button>
</form>

<div class="links">
@if (Route::has('password.request'))
<a href="{{ route('password.request') }}">Lupa password?</a>
@endif
</div>

<div class="info">
Demo akun: admin@sim-bu.local / password
</div>
</div>
</body>
</html>
