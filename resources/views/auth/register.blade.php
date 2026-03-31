<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Market Databank</title>
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
            background: radial-gradient(circle, rgba(94,234,212,0.28), transparent 70%);
            border-radius: 50%;
        }
        .auth-left-content { position: relative; z-index: 1; max-width: 420px; }
        .auth-left-content h1 { font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; }
        .auth-left-content p { font-size: 1.05rem; color: rgba(255,255,255,0.8); line-height: 1.7; }
        .role-card {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1rem;
            border: 1px solid rgba(94,234,212,0.35);
        }
        .role-card h6 { font-weight: 600; margin-bottom: 0.25rem; }
        .role-card p { font-size: 0.8rem; color: rgba(255,255,255,0.7); margin: 0; }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: #fdfcf9;
            overflow-y: auto;
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

        .form-floating > .form-control, .form-floating > .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        .form-floating > .form-control:focus, .form-floating > .form-select:focus {
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

        .btn-register {
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
        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(15,118,110,0.35);
            color: white;
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
            <h1>Join Market Databank</h1>
            <p>Create a user or contributor account to access market insights and workflows.</p>
            <div class="role-card">
                <h6><i class="fas fa-eye me-1"></i> User and Contributor Access</h6>
                <p>Users can browse data and reports, contributors can also submit market data entries.</p>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-form-container">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-chart-line"></i></div>
                <h4>Market Databank</h4>
            </div>

            <h2>Create Account</h2>
            <p class="subtitle">Fill in your details to get started</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:0.85rem;border:none;background:#fee2e2;color:#991b1b;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
                    <label for="name"><i class="fas fa-user me-1"></i> Full Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                    <label for="email"><i class="fas fa-envelope me-1"></i> Email Address</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="role" name="role" required>
                        <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="contributor" {{ old('role') == 'contributor' ? 'selected' : '' }}>Contributor</option>
                    </select>
                    <label for="role"><i class="fas fa-user-tag me-1"></i> Account Type</label>
                </div>

                <div class="form-floating mb-3 password-toggle">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-1"></i> Password</label>
                    <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-floating mb-4 password-toggle">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <label for="password_confirmation"><i class="fas fa-lock me-1"></i> Confirm Password</label>
                    <button type="button" class="toggle-btn" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </button>
            </form>

            <p class="text-center mt-4" style="font-size:0.9rem;color:#64748b;">
                Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign In</a>
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
