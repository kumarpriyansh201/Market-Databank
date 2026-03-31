@extends('layouts.app')
@section('title', 'Market Analytics')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line me-2"></i> Market Analytics</h1>
    <p>Visualize trends, compare prices, and explore market insights.</p>
</div>

{{-- Summary Stats --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <div class="stat-icon" style="background: rgba(79,70,229,0.1); color: var(--primary);"><i class="fas fa-database"></i></div>
            <div class="stat-info">
                <h3>{{ $topProducts->sum('count') }}</h3>
                <p>Total Data Points</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid var(--accent);">
            <div class="stat-icon" style="background: rgba(6,182,212,0.1); color: var(--accent);"><i class="fas fa-boxes-stacked"></i></div>
            <div class="stat-info">
                <h3>{{ $topProducts->count() }}</h3>
                <p>Tracked Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid var(--success);">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: var(--success);"><i class="fas fa-arrow-trend-up"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($demandSupply->avg_demand ?? 0, 1) }}</h3>
                <p>Avg Demand Level</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-left: 4px solid var(--secondary);">
            <div class="stat-icon" style="background: rgba(124,58,237,0.1); color: var(--secondary);"><i class="fas fa-arrow-trend-down"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($demandSupply->avg_supply ?? 0, 1) }}</h3>
                <p>Avg Supply Level</p>
            </div>
        </div>
    </div>
</div>

{{-- Price Trend Chart --}}
<div class="card-modern mb-4">
    <div class="card-body-modern">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-primary"></i> Price Trends (Last 30 Days)</h5>
            <div class="d-flex gap-2">
                <select id="trendProduct" class="form-select form-select-sm" style="width: 160px;">
                    <option value="">All Products</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                <select id="trendLocation" class="form-select form-select-sm" style="width: 160px;">
                    <option value="">All Locations</option>
                    @foreach($locations as $l)
                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <canvas id="priceTrendChart" height="100"></canvas>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Category Distribution --}}
    <div class="col-md-5">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <h5><i class="fas fa-chart-pie me-2 text-secondary"></i> Category Distribution</h5>
                <canvas id="categoryPieChart" height="220"></canvas>
            </div>
        </div>
    </div>
    {{-- Top Products --}}
    <div class="col-md-7">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <h5><i class="fas fa-chart-bar me-2 text-primary"></i> Top Products by Avg Price</h5>
                <canvas id="topProductsChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Location Prices --}}
    <div class="col-md-6">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <h5><i class="fas fa-map-marker-alt me-2 text-accent"></i> Average Price by Location</h5>
                <canvas id="locationChart" height="200"></canvas>
            </div>
        </div>
    </div>
    {{-- Demand vs Supply --}}
    <div class="col-md-6">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <h5><i class="fas fa-balance-scale me-2 text-success"></i> Demand vs Supply</h5>
                <canvas id="demandSupplyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card-modern">
    <div class="card-body-modern">
        <h5><i class="fas fa-table me-2"></i> Product Statistics</h5>
        <div class="table-responsive">
            <table class="table-modern">
                <thead><tr><th>Product</th><th>Data Points</th><th>Avg Price (₹)</th></tr></thead>
                <tbody>
                    @foreach($topProducts as $tp)
                        <tr>
                            <td>{{ $tp->product->name ?? '-' }}</td>
                            <td>{{ $tp->count }}</td>
                            <td>₹{{ number_format($tp->avg_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const COLORS = ['#4f46e5','#7c3aed','#06b6d4','#10b981','#f59e0b','#ef4444','#6366f1','#8b5cf6','#14b8a6','#f97316'];

// ---- Price Trend (AJAX) ----
let trendChart;
function loadPriceTrend() {
    const productId = document.getElementById('trendProduct').value;
    const locationId = document.getElementById('trendLocation').value;
    let url = '{{ route("analytics.chart-data") }}?type=price_trend';
    if (productId) url += '&product_id=' + encodeURIComponent(productId);
    if (locationId) url += '&location_id=' + encodeURIComponent(locationId);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(d => {
            if (trendChart) trendChart.destroy();
            trendChart = new Chart(document.getElementById('priceTrendChart'), {
                type: 'line',
                data: {
                    labels: d.labels,
                    datasets: [{
                        label: 'Avg Price (₹)',
                        data: d.data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.1)',
                        fill: true, tension: 0.4
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        });
}
document.getElementById('trendProduct').addEventListener('change', loadPriceTrend);
document.getElementById('trendLocation').addEventListener('change', loadPriceTrend);
loadPriceTrend();

// ---- Category Pie ----
new Chart(document.getElementById('categoryPieChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryDistribution->map(fn($d) => $d->category->name ?? 'Unknown')) !!},
        datasets: [{ data: {!! json_encode($categoryDistribution->pluck('count')) !!}, backgroundColor: COLORS }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// ---- Top Products Bar ----
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($topProducts->map(fn($d) => $d->product->name ?? '-')) !!},
        datasets: [{
            label: 'Avg Price (₹)',
            data: {!! json_encode($topProducts->pluck('avg_price')) !!},
            backgroundColor: COLORS
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// ---- Location Prices ----
new Chart(document.getElementById('locationChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($locationPrices->map(fn($d) => $d->location->name ?? '-')) !!},
        datasets: [{
            label: 'Avg Price (₹)',
            data: {!! json_encode($locationPrices->pluck('avg_price')) !!},
            backgroundColor: '#06b6d4'
        }]
    },
    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
});

// ---- Demand vs Supply (from chartData) ----
fetch('{{ route("analytics.chart-data") }}?type=demand_supply', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        new Chart(document.getElementById('demandSupplyChart'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'Demand', data: d.demand, backgroundColor: '#4f46e5' },
                    { label: 'Supply', data: d.supply, backgroundColor: '#10b981' }
                ]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: 5 } } }
        });
    });
</script>
@endpush
