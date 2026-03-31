<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Market Databank</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; min-height: 100vh; display: flex; }
        .auth-left {
            flex: 1;
            background: linear-gradient(145deg, #0f172a 0%, #134e4a 45%, #0d9488 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(94,234,212,0.3), transparent 70%);
            border-radius: 50%;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(251,191,36,0.22), transparent 70%);
            border-radius: 50%;
        }
        .auth-left-content { position: relative; z-index: 1; max-width: 420px; }
        .auth-left-content h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .auth-left-content p { font-size: 1.05rem; color: rgba(255,255,255,0.8); line-height: 1.7; }
        .auth-features { margin-top: 2rem; }
        .auth-feature { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
        .auth-feature-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
        }
        .auth-feature span { font-size: 0.9rem; color: rgba(255,255,255,0.85); }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: #fdfcf9;
        }
        .auth-form-container {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(17,24,39,0.08);
            box-shadow: 0 22px 50px rgba(17,24,39,0.12);
            border-radius: 20px;
            padding: 1.75rem;
        }
        .auth-form-container .brand {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .auth-form-container .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem;
        }
        .auth-form-container .brand h4 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: #1f2937; margin: 0; }
        .auth-form-container h2 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: #1f2937; font-size: 1.5rem; }
        .auth-form-container .subtitle { color: #64748b; font-size: 0.9rem; margin-top: 0.25rem; margin-bottom: 1.5rem; }

        .form-floating > .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem 0.85rem;
            font-size: 0.9rem;
        }
        .form-floating > .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13,148,136,0.14);
        }
        .form-floating > label { font-size: 0.85rem; color: #64748b; }
        .password-toggle {
            position: relative;
        }
        .password-toggle .toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 0.95rem;
            transition: color 0.2s;
            z-index: 10;
        }
        .password-toggle .toggle-btn:hover {
            color: #0d9488;
        }
        .password-toggle .form-control {
            padding-right: 2.5rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(15,118,110,0.35);
            color: white;
        }
        .btn-google-login {
            width: 100%;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            min-height: 44px;
            background: #ffffff;
            color: #334155;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-google-login:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #334155;
        }
        .auth-divider {
            text-align: center;
            color: #64748b;
            margin: 0.7rem 0 0.9rem;
        }
        .auth-link { color: #0f766e; text-decoration: none; font-weight: 600; }
        .auth-link:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .auth-left { display: none; }
            .auth-right { padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Welcome Back!</h1>
            <p>User and contributor access for market data dashboards, analytics, and submissions.</p>
            <div class="auth-features">
                <div class="auth-feature">
                    <div class="auth-feature-icon"><i class="fas fa-chart-line"></i></div>
                    <span>Real-time market analytics</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon"><i class="fas fa-database"></i></div>
                    <span>Comprehensive data collection</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon"><i class="fas fa-file-alt"></i></div>
                    <span>Downloadable reports</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon"><i class="fas fa-bell"></i></div>
                    <span>Instant notifications</span>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-container">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-chart-line"></i></div>
                <h4>Market Databank</h4>
            </div>

            <h2>User & Contributor Sign In</h2>
            <p class="subtitle">Enter your credentials to access your account</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:0.85rem;border:none;background:#fee2e2;color:#991b1b;">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <a href="{{ route('auth.google.redirect') }}" class="btn-google-login mb-2">
                <i class="fab fa-google me-2" style="color:#ea4335"></i> Continue with Google
            </a>
            <div class="auth-divider">or</div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <label for="email"><i class="fas fa-envelope me-1"></i> Email Address</label>
                </div>

                <div class="form-floating mb-3 password-toggle">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-1"></i> Password</label>
                    <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem;color:#64748b;">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <p class="text-center mt-4" style="font-size:0.9rem;color:#64748b;">
                Don't have an account? <a href="{{ route('register') }}" class="auth-link">Create Account</a>
            </p>
            <p class="text-center mt-2" style="font-size:0.9rem;color:#64748b;">
                Admin account? <a href="{{ route('admin.login') }}" class="auth-link">Go to admin login</a>
            </p>
            <p class="text-center mt-2">
                <a href="{{ route('home') }}" style="font-size:0.85rem;color:#64748b;text-decoration:none;">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            </p>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const btn = event.target.closest('.toggle-btn');
            const icon = btn.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
