@extends('layouts.guest')
@section('title', 'Market Databank - Empowering Market Intelligence')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #134e4a 45%, #0d9488 100%);
        color: white;
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(94,234,212,0.28), transparent 70%);
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(251,191,36,0.22), transparent 70%);
        border-radius: 50%;
    }
    .hero-content { position: relative; z-index: 1; }
    .hero-content h1 { font-family: 'Space Grotesk', sans-serif; font-size: 3rem; font-weight: 700; line-height: 1.15; }
    .hero-content p { font-size: 1.15rem; color: rgba(255,255,255,0.8); max-width: 540px; }
    .hero-stats { display: flex; gap: 2.5rem; margin-top: 2.5rem; }
    .hero-stat .value { font-size: 2rem; font-weight: 800; color: #99f6e4; }
    .hero-stat .label { font-size: 0.8rem; color: rgba(255,255,255,0.6); }

    .feature-card {
        background: rgba(255,255,255,0.92);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(17,24,39,0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: block;
        text-decoration: none;
        box-shadow: 0 10px 28px rgba(17,24,39,0.05);
    }
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    .feature-card:focus-visible {
        outline: 3px solid rgba(13,148,136,0.35);
        outline-offset: 2px;
    }
    .feature-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 1.25rem;
    }
    .feature-card h5 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.05rem; color: #1f2937; }
    .feature-card p { color: #57534e; font-size: 0.875rem; line-height: 1.6; }

    .cta-section {
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: white;
        padding: 4rem 0;
        border-radius: 24px;
        margin: 0 1rem;
    }
    .how-it-works { background: #f4f1ea; padding: 5rem 0; }
    .step-number {
        width: 48px; height: 48px; border-radius: 50%;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: white; font-weight: 700; display: flex;
        align-items: center; justify-content: center; margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 mb-3" style="font-size:0.8rem;border-radius:8px">
                    <i class="fas fa-rocket me-1"></i> Market Intelligence Platform
                </span>
                <h1>Unlock the Power of <span style="color:#99f6e4">Market Data</span></h1>
                <p class="mt-3">Collect, analyze, and share market-related data including product prices, demand trends, supplier information, and market insights - all in one place.</p>
                <div class="mt-4 d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-lg px-4" style="background:#fcd34d;color:#1f2937;font-weight:700;border-radius:10px;font-size:0.95rem">
                        Get Started Free <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-lg btn-outline-light px-4" style="border-radius:10px;font-size:0.95rem">
                        Learn More
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="value">{{ $stats['products'] }}+</div>
                        <div class="label">Products Tracked</div>
                    </div>
                    <div class="hero-stat">
                        <div class="value">{{ $stats['data_points'] }}+</div>
                        <div class="label">Data Points</div>
                    </div>
                    <div class="hero-stat">
                        <div class="value">{{ $stats['locations'] }}+</div>
                        <div class="label">Locations</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center" style="position:relative;z-index:1;">
                <div style="width:420px;height:340px;background:rgba(255,255,255,0.06);border-radius:20px;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.1);padding:2rem;display:flex;flex-direction:column;gap:1rem;">
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;">
                        <div style="width:40px;height:40px;background:#99f6e4;color:#0f172a;border-radius:10px;display:flex;align-items:center;justify-content:center"><i class="fas fa-chart-line"></i></div>
                        <div><div style="font-weight:600;font-size:0.9rem">Price Trends</div><div style="font-size:0.75rem;color:rgba(255,255,255,0.6)">Real-time market analytics</div></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;">
                        <div style="width:40px;height:40px;background:#10b981;border-radius:10px;display:flex;align-items:center;justify-content:center"><i class="fas fa-database"></i></div>
                        <div><div style="font-weight:600;font-size:0.9rem">Market Database</div><div style="font-size:0.75rem;color:rgba(255,255,255,0.6)">Comprehensive data collection</div></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;">
                        <div style="width:40px;height:40px;background:#f59e0b;border-radius:10px;display:flex;align-items:center;justify-content:center"><i class="fas fa-file-alt"></i></div>
                        <div><div style="font-weight:600;font-size:0.9rem">Reports</div><div style="font-size:0.75rem;color:rgba(255,255,255,0.6)">Download detailed reports</div></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:1rem;display:flex;align-items:center;gap:1rem;">
                        <div style="width:40px;height:40px;background:#ef4444;border-radius:10px;display:flex;align-items:center;justify-content:center"><i class="fas fa-bell"></i></div>
                        <div><div style="font-weight:600;font-size:0.9rem">Notifications</div><div style="font-size:0.75rem;color:rgba(255,255,255,0.6)">Stay updated with alerts</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="padding:5rem 0!important;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 mb-3" style="background:#ccfbf1;color:#115e59;font-size:0.8rem;border-radius:8px">Features</span>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#1f2937;font-size:2.2rem">Everything You Need for Market Intelligence</h2>
            <p style="color:#57534e;max-width:600px;margin:0.75rem auto 0;">Our platform provides powerful tools to collect, analyze, and share market data efficiently.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? route('market-data.create') : route('login') }}">
                    <div class="feature-icon bg-gradient-primary"><i class="fas fa-database text-white"></i></div>
                    <h5>Data Collection</h5>
                    <p>Easily submit and collect market data including prices, demand levels, supply information, and more.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? route('analytics') : route('login') }}">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#10b981,#34d399)"><i class="fas fa-chart-bar text-white"></i></div>
                    <h5>Data Visualization</h5>
                    <p>Interactive charts and graphs to visualize market trends, price movements, and demand-supply dynamics.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? route('market-data.index') : route('login') }}">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24)"><i class="fas fa-search text-white"></i></div>
                    <h5>Smart Search</h5>
                    <p>Powerful search and filtering system to find specific market data by product, location, date, and more.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? route('reports.create') : route('login') }}">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#ef4444,#f87171)"><i class="fas fa-file-pdf text-white"></i></div>
                    <h5>Report Generation</h5>
                    <p>Generate comprehensive reports with market analysis, price trends, and demand-supply insights.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.approvals') : route('dashboard')) : route('login') }}">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#06b6d4,#22d3ee)"><i class="fas fa-check-double text-white"></i></div>
                    <h5>Data Approval</h5>
                    <p>Quality-assured data with admin review and approval workflow before publishing.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a class="feature-card" href="{{ auth()->check() ? route('notifications') : route('login') }}">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa)"><i class="fas fa-bell text-white"></i></div>
                    <h5>Notifications</h5>
                    <p>Stay informed with real-time notifications about data approvals, updates, and market changes.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 mb-3" style="background:#ccfbf1;color:#115e59;font-size:0.8rem;border-radius:8px">How It Works</span>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#1f2937;font-size:2.2rem">Simple Steps to Get Started</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="step-number mx-auto">1</div>
                <h5 style="font-weight:700;font-size:1rem">Register</h5>
                <p style="color:#57534e;font-size:0.85rem">Create your free account and choose your role.</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-number mx-auto">2</div>
                <h5 style="font-weight:700;font-size:1rem">Submit Data</h5>
                <p style="color:#57534e;font-size:0.85rem">Contribute market data with prices and trends.</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-number mx-auto">3</div>
                <h5 style="font-weight:700;font-size:1rem">Analyze</h5>
                <p style="color:#57534e;font-size:0.85rem">View interactive charts and analytics.</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="step-number mx-auto">4</div>
                <h5 style="font-weight:700;font-size:1rem">Download</h5>
                <p style="color:#57534e;font-size:0.85rem">Generate and download detailed reports.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="cta-section text-center">
        <h2 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:2rem">Ready to Make Data-Driven Decisions?</h2>
        <p style="color:rgba(255,255,255,0.8);max-width:500px;margin:1rem auto;">Join thousands of businesses and researchers who use Market Databank for reliable market intelligence.</p>
        <a href="{{ route('register') }}" class="btn btn-lg mt-3 px-5" style="background:#fef3c7;color:#1f2937;font-weight:700;border-radius:10px">
            Start Free Today <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</section>
@endsection
