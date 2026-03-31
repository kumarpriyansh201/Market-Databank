@extends('layouts.guest')
@section('title', 'About - Market Databank')

@section('content')
<!-- Hero -->
<section style="background:linear-gradient(135deg,#1e1b4b,#4f46e5);color:white;padding:5rem 0;">
    <div class="container text-center">
        <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 mb-3" style="font-size:0.8rem;border-radius:8px">
            <i class="fas fa-info-circle me-1"></i> About Us
        </span>
        <h1 style="font-weight:800;font-size:2.5rem;">About Market Databank</h1>
        <p style="max-width:600px;margin:1rem auto 0;color:rgba(255,255,255,0.8);font-size:1.1rem;">
            Empowering businesses and researchers with reliable market intelligence through data-driven insights.
        </p>
    </div>
</section>

<!-- Mission -->
<section class="py-5" style="padding:5rem 0!important;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge px-3 py-2 mb-3" style="background:#e0e7ff;color:#4f46e5;font-size:0.8rem;border-radius:8px">Our Mission</span>
                <h2 style="font-weight:800;color:#0f172a;font-size:2rem;">Making Market Data Accessible to Everyone</h2>
                <p style="color:#64748b;line-height:1.8;margin-top:1rem;">
                    Market Databank is a comprehensive platform designed to collect, store, analyze, and share market-related data.
                    We bridge the gap between data collectors and decision makers, ensuring that accurate and timely market information
                    is available to all stakeholders.
                </p>
                <p style="color:#64748b;line-height:1.8;margin-top:1rem;">
                    Our platform supports product prices, demand trends, supplier information, and market insights — helping
                    businesses, researchers, and consumers make better-informed decisions.
                </p>
            </div>
            <div class="col-lg-6">
                <div style="background:linear-gradient(135deg,#f8fafc,#e0e7ff);border-radius:20px;padding:2.5rem;">
                    <div class="row g-3">
                        <div class="col-6">
                            <div style="background:white;border-radius:12px;padding:1.5rem;text-align:center;">
                                <div style="font-size:2rem;font-weight:800;color:#4f46e5;">100+</div>
                                <div style="color:#64748b;font-size:0.85rem;">Products</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:white;border-radius:12px;padding:1.5rem;text-align:center;">
                                <div style="font-size:2rem;font-weight:800;color:#10b981;">1K+</div>
                                <div style="color:#64748b;font-size:0.85rem;">Data Points</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:white;border-radius:12px;padding:1.5rem;text-align:center;">
                                <div style="font-size:2rem;font-weight:800;color:#f59e0b;">50+</div>
                                <div style="color:#64748b;font-size:0.85rem;">Locations</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:white;border-radius:12px;padding:1.5rem;text-align:center;">
                                <div style="font-size:2rem;font-weight:800;color:#7c3aed;">500+</div>
                                <div style="color:#64748b;font-size:0.85rem;">Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section style="background:#f8fafc;padding:5rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 mb-3" style="background:#e0e7ff;color:#4f46e5;font-size:0.8rem;border-radius:8px">Core Values</span>
            <h2 style="font-weight:800;color:#0f172a;font-size:2rem;">What Drives Us</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div style="background:white;border-radius:16px;padding:2rem;text-align:center;border:1px solid #e2e8f0;height:100%;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;color:white;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 style="font-weight:700;color:#0f172a;">Data Accuracy</h5>
                    <p style="color:#64748b;font-size:0.875rem;">Every data point goes through a rigorous approval process to ensure reliability and accuracy.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:white;border-radius:16px;padding:2rem;text-align:center;border:1px solid #e2e8f0;height:100%;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;color:white;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 style="font-weight:700;color:#0f172a;">Community Driven</h5>
                    <p style="color:#64748b;font-size:0.875rem;">Built by and for the community — contributors make our platform richer with every data submission.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:white;border-radius:16px;padding:2rem;text-align:center;border:1px solid #e2e8f0;height:100%;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#06b6d4,#22d3ee);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;color:white;">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h5 style="font-weight:700;color:#0f172a;">Open Access</h5>
                    <p style="color:#64748b;font-size:0.875rem;">We believe market data should be accessible to everyone who needs it for informed decision making.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team / CTA -->
<section class="py-5" style="padding:4rem 0!important;">
    <div class="container text-center">
        <h2 style="font-weight:800;color:#0f172a;font-size:2rem;">Join Our Growing Community</h2>
        <p style="color:#64748b;max-width:600px;margin:1rem auto;">Whether you're a business owner, researcher, or data enthusiast, there's a place for you on Market Databank.</p>
        <div class="mt-4 d-flex gap-3 justify-content-center">
            <a href="{{ route('register') }}" class="btn px-4 py-2" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;font-weight:600;border-radius:10px;">
                Sign Up Now <i class="fas fa-arrow-right ms-2"></i>
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;">
                Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
