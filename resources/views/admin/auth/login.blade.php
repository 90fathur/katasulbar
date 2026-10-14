<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Redaksi & Admin - KATA SULBAR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-transparent.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, var(--admin-navy-dark) 0%, #062B56 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-box {
            width: 100%;
            max-width: 420px;
            background: #FFFFFF;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        .login-header {
            background: var(--admin-navy);
            padding: 2rem 1.5rem;
            text-align: center;
            color: #FFFFFF;
            border-bottom: 3px solid var(--admin-red);
        }
        .login-body {
            padding: 2rem 1.75rem;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-header">
            <img src="{{ asset('assets/images/logo-dark-bg.png') }}" alt="KATA SULBAR" style="height: 52px; width: auto; object-fit: contain; margin-bottom: 0.5rem;">
            <div style="font-size: 0.75rem; font-weight: 600; color: #CBD5E1;">
                Sistem Manajemen Portal Berita
            </div>
        </div>

        <div class="login-body">
            @if(session('error'))
                <div class="admin-alert admin-alert-danger" style="margin-bottom: 1.25rem;">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="admin-alert admin-alert-success" style="margin-bottom: 1.25rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="admin-alert admin-alert-danger" style="margin-bottom: 1.25rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <div style="position: relative;">
                        <input type="email" name="email" class="form-control" style="padding-left: 2.5rem;" required autofocus value="{{ old('email', 'admin@halosulbar.com') }}" placeholder="admin@halosulbar.com">
                        <i class="far fa-envelope" style="position: absolute; left: 12px; top: 10px; color: var(--admin-text-muted);"></i>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" style="padding-left: 2.5rem;" required value="password" placeholder="••••••••">
                        <i class="fas fa-lock" style="position: absolute; left: 12px; top: 10px; color: var(--admin-text-muted);"></i>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.8125rem;">
                    <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1"> Ingat saya
                    </label>
                    <a href="{{ route('home') }}" style="color: var(--admin-red); font-weight: 600;">Ke Website</a>
                </div>

                <button type="submit" class="admin-btn admin-btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem;">
                    Masuk ke Sistem <i class="fas fa-arrow-right" style="margin-left: 0.35rem"></i>
                </button>
            </form>

            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.75rem; color: var(--admin-text-muted);">
                © {{ date('Y') }} Kata Sulbar. Suara Sulbar, Untuk Semua.
            </div>
        </div>
    </div>

</body>
</html>
