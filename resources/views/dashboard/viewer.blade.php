@extends('layouts.app')
@section('title', 'Dashboard - Market Databank')

@section('content')
<div class="page-header">
    <h1>Welcome, {{ auth()->user()->name }}!</h1>
    <p>Explore market data, view trends, and download reports.</p>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Products Tracked</div>
                    <div class="stat-value">{{ $stats['total_products'] }}</div>
                </div>
                <div class="stat-icon bg-gradient-primary"><i class="fas fa-boxes"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Data Points</div>
                    <div class="stat-value">{{ $stats['total_market_data'] }}</div>
                </div>
                <div class="stat-icon bg-gradient-success"><i class="fas fa-database"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Categories</div>
                    <div class="stat-value">{{ $stats['total_categories'] }}</div>
                </div>
                <div class="stat-icon bg-gradient-warning"><i class="fas fa-tags"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Community</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                </div>
                <div class="stat-icon bg-gradient-info"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Trending Products -->
<div class="card-modern mb-4">
    <div class="card-header-modern">
        <h5><i class="fas fa-fire me-2" style="color:#ef4444;"></i>Trending Products</h5>
        <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern btn-sm">Browse All</a>
    </div>
    <div class="card-body-modern">
        <div class="row g-3">
            @forelse($trendingProducts as $item)
                <div class="col-md-4 col-sm-6">
                    <div style="background:var(--gray-50);border-radius:10px;padding:1.25rem;border:1px solid var(--gray-200);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 style="font-weight:600;margin:0;color:var(--gray-900);">{{ $item->product->name ?? 'N/A' }}</h6>
                            <span class="badge-modern badge-info">{{ $item->data_count }} entries</span>
                        </div>
                        <div style="font-size:1.25rem;font-weight:700;color:var(--primary);">₹{{ number_format($item->avg_price, 2) }}</div>
                        <div style="font-size:0.75rem;color:var(--gray-500);">Average price</div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4" style="color:var(--gray-500);">
                    <i class="fas fa-chart-bar" style="font-size:2rem;"></i>
                    <p class="mt-2 mb-0">No trending data available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-md-4">
        <a href="{{ route('market-data.index') }}" class="text-decoration-none">
            <div class="stat-card text-center">
                <div class="stat-icon bg-gradient-primary mx-auto mb-3" style="width:56px;height:56px;"><i class="fas fa-search"></i></div>
                <h6 style="font-weight:600;color:var(--gray-900);">Search Market Data</h6>
                <p style="font-size:0.8rem;color:var(--gray-500);margin:0;">Find prices and trends by product, location, date</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('analytics') }}" class="text-decoration-none">
            <div class="stat-card text-center">
                <div class="stat-icon bg-gradient-success mx-auto mb-3" style="width:56px;height:56px;"><i class="fas fa-chart-line"></i></div>
                <h6 style="font-weight:600;color:var(--gray-900);">View Analytics</h6>
                <p style="font-size:0.8rem;color:var(--gray-500);margin:0;">Interactive charts and market insights</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reports.index') }}" class="text-decoration-none">
            <div class="stat-card text-center">
                <div class="stat-icon bg-gradient-warning mx-auto mb-3" style="width:56px;height:56px;"><i class="fas fa-download"></i></div>
                <h6 style="font-weight:600;color:var(--gray-900);">Download Reports</h6>
                <p style="font-size:0.8rem;color:var(--gray-500);margin:0;">Get detailed market reports in PDF format</p>
            </div>
        </a>
    </div>
</div>
@endsection
