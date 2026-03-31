<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Market Databank')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #0d9488;
            --primary-light: #5eead4;
            --secondary: #f97316;
            --accent: #f59e0b;
            --dark: #111827;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at 10% 8%, rgba(13, 148, 136, 0.12), transparent 20%),
                radial-gradient(circle at 90% 95%, rgba(249, 115, 22, 0.1), transparent 20%),
                #fdfcf9;
            color: #2f2c26;
        }

        /* ── Navbar ── */
        .navbar-custom {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(17,24,39,0.08);
            padding: 1rem 0;
        }
        .navbar-custom .navbar-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .navbar-custom .navbar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), #0f766e);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .navbar-custom .nav-link {
            color: #44403c;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }
        .navbar-custom .nav-link:hover { color: #0f766e; }

        .btn-get-started {
            background: linear-gradient(135deg, var(--primary), #0f766e);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-get-started:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(13,148,136,0.35);
            color: white;
        }

        /* ── Login Modal ── */
        .login-modal .modal-content {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.35);
        }
        .login-modal .modal-dialog {
            max-width: 980px;
        }
        .login-modal-left {
            background: linear-gradient(145deg, #0f172a 0%, #134e4a 45%, #14b8a6 100%);
            color: #ecfeff;
            padding: 2rem 1.6rem;
            min-height: 330px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .login-modal-left::before {
            content: '';
            position: absolute;
            top: -35%;
            right: -20%;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.22), transparent 72%);
        }
        .login-modal-left h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.35rem;
            font-weight: 700;
            line-height: 1.05;
            position: relative;
            z-index: 1;
        }
        .login-modal-left p {
            margin-top: 1rem;
            font-size: 0.92rem;
            color: rgba(236,254,255,0.85);
            max-width: 360px;
            position: relative;
            z-index: 1;
        }
        .login-modal-right {
            background: #ffffff;
            padding: 1.6rem 1.4rem;
        }
        .login-modal-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #374151;
        }
        .login-google-btn {
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            height: 44px;
            width: 100%;
            background: #fff;
            color: #334155;
            font-weight: 600;
        }
        .login-google-btn:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .login-divider {
            text-align: center;
            color: #6b7280;
            margin: 0.5rem 0 0.7rem;
        }
        .login-modal .form-control {
            border-radius: 10px;
            border: 1px solid #dbe2ea;
            min-height: 42px;
        }
        .login-modal .form-control:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.22);
        }
        .btn-login-submit {
            width: 100%;
            border: 0;
            min-height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #fff;
            font-weight: 700;
        }
        .btn-login-submit:hover {
            color: #fff;
            box-shadow: 0 12px 28px rgba(15,118,110,0.3);
        }
        .btn-admin-portal {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #fecaca;
            color: #991b1b;
            background: #fff5f5;
            font-weight: 600;
            min-height: 40px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }
        .btn-admin-portal:hover {
            color: #7f1d1d;
            background: #fee2e2;
        }

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
            padding: 0.5rem;
        }
        .password-toggle .toggle-btn:hover {
            color: #0d9488;
        }
        .password-toggle .form-control {
            padding-right: 2.5rem;
        }

        @media (max-width: 991.98px) {
            .login-modal .modal-dialog {
                max-width: 94vw;
            }
            .login-modal-left { display: none; }
            .login-modal-title { font-size: 1.8rem; }
        }

        /* ── Footer ── */
        .footer {
            background:
                linear-gradient(160deg, #111827 0%, #1f2937 40%, #0f766e 120%);
            color: rgba(255,255,255,0.7);
            padding: 3rem 0 1.5rem;
        }
        .footer h5 {
            color: white;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .footer a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .footer a:hover { color: #fcd34d; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; margin-top: 2rem; }

        @yield('page-styles')
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-icon"><i class="fas fa-chart-line"></i></span>
                Market Databank
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                </ul>
                @guest
                    <button type="button" class="btn btn-get-started" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Login / Sign up
                    </button>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-get-started">Dashboard</a>
                @endguest
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-chart-line me-2"></i>Market Databank</h5>
                    <p style="font-size:0.875rem;">A comprehensive platform for collecting, analyzing, and sharing market data to empower better business decisions.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Platform</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('about') }}">About</a>
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Features</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ auth()->check() ? route('analytics') : route('login') }}">Market Analytics</a>
                        <a href="{{ auth()->check() ? route('market-data.index') : route('login') }}">Price Trends</a>
                        <a href="{{ auth()->check() ? route('reports.index') : route('login') }}">Data Reports</a>
                        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('market-data.create') : route('market-data.index') }}">Data Sharing</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contact</h5>
                    <div class="d-flex flex-column gap-2">
                        <span><i class="fas fa-envelope me-2"></i>info@marketdatabank.com</span>
                        <span><i class="fas fa-phone me-2"></i>+91 98765 43210</span>
                        <span><i class="fas fa-map-marker-alt me-2"></i>New Delhi, India</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p style="font-size:0.8rem;margin:0;">&copy; {{ date('Y') }} Market Databank. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @guest
    <div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="row g-0">
                    <div class="col-lg-6 login-modal-left">
                        <h2>Simple, Fast<br>Investing.</h2>
                        <p>Stocks, market data, and smart analytics in one platform.</p>
                    </div>
                    <div class="col-lg-6 login-modal-right">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h3 id="loginModalLabel" class="login-modal-title mb-0">Welcome to Market Databank</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger py-2 px-3 mt-2" style="border-radius:10px;font-size:0.85rem;border:none;background:#fee2e2;color:#991b1b;">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <a href="{{ route('auth.google.redirect') }}" class="login-google-btn mt-3 d-inline-flex align-items-center justify-content-center text-decoration-none">
                            <i class="fab fa-google me-2" style="color:#ea4335"></i> Continue with Google
                        </a>

                        <div class="login-divider">or</div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Your Email Address" required>
                            </div>
                            <div class="mb-2 password-toggle">
                                <input type="password" class="form-control" id="modalPassword" name="password" placeholder="Password" required>
                                <button type="button" class="toggle-btn" onclick="toggleModalPassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberModal">
                                <label class="form-check-label" for="rememberModal" style="font-size:0.85rem;color:#64748b;">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-login-submit">Continue</button>
                        </form>

                        <p class="text-center mt-3 mb-2" style="font-size:0.95rem;color:#6b7280;">
                            New here? <a href="{{ route('register') }}" style="font-weight:700;color:#0f766e;">Create account</a>
                        </p>

                        <a href="{{ route('admin.login') }}" class="btn-admin-portal mt-2">
                            <i class="fas fa-user-shield"></i> Admin Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @guest
    <script>
        function toggleModalPassword() {
            const field = document.getElementById('modalPassword');
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

        document.addEventListener('DOMContentLoaded', function () {
            const hasErrors = @json($errors->any());
            if (hasErrors) {
                const loginModal = document.getElementById('loginModal');
                if (loginModal && window.bootstrap) {
                    new bootstrap.Modal(loginModal).show();
                }
            }
        });
    </script>
    @endguest
    @stack('scripts')
</body>
</html>
