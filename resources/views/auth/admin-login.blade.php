<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Market Databank</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; min-height: 100vh; display: flex; }
        .auth-left {
            flex: 1;
            background: linear-gradient(145deg, #111827 0%, #7f1d1d 45%, #b91c1c 100%);
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
            background: radial-gradient(circle, rgba(248,113,113,0.3), transparent 70%);
            border-radius: 50%;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(251,191,36,0.2), transparent 70%);
            border-radius: 50%;
        }
        .auth-left-content { position: relative; z-index: 1; max-width: 420px; }
        .auth-left-content h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .auth-left-content p { font-size: 1.05rem; color: rgba(255,255,255,0.82); line-height: 1.7; }
        .auth-badge {
            margin-top: 1.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-size: 0.82rem;
            color: #fee2e2;
        }

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
            background: rgba(255,255,255,0.92);
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
            background: linear-gradient(135deg, #b91c1c, #7f1d1d);
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
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185,28,28,0.14);
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
            color: #b91c1c;
        }
        .password-toggle .form-control {
            padding-right: 2.5rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #b91c1c, #7f1d1d);
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
            box-shadow: 0 12px 26px rgba(127,29,29,0.35);
            color: white;
        }
        .auth-link { color: #991b1b; text-decoration: none; font-weight: 600; }
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
            <h1>Admin Control Access</h1>
            <p>Sign in with your administrator account to manage users, approvals, categories, products, and platform operations.</p>
            <div class="auth-badge">
                <i class="fas fa-shield-halved"></i>
                Restricted to admin accounts only
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-container">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-user-shield"></i></div>
                <h4>Market Databank</h4>
            </div>

            <h2>Admin Sign In</h2>
            <p class="subtitle">Use your administrator credentials</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:0.85rem;border:none;background:#fee2e2;color:#991b1b;">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
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
                    <i class="fas fa-sign-in-alt me-2"></i> Admin Sign In
                </button>
            </form>

            <p class="text-center mt-4" style="font-size:0.9rem;color:#64748b;">
                User or Contributor? <a href="{{ route('login') }}" class="auth-link">Go to user login</a>
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
