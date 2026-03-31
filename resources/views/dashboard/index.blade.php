@extends('layouts.app')
@section('title', 'Dashboard - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Market Intelligence Dashboard</h1>
        <p>Professional overview of datasets, growth, and insights.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern">
            <i class="fas fa-magnifying-glass-chart me-1"></i> Explore Data
        </a>
        @if($isAdmin)
            <a href="{{ route('market-data.create') }}" class="btn btn-primary-modern">
                <i class="fas fa-plus me-1"></i> Add Dataset
            </a>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Datasets</div>
                    <div class="stat-value">{{ number_format($stats['total_datasets']) }}</div>
                </div>
                <div class="stat-icon bg-gradient-primary"><i class="fas fa-database"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                </div>
                <div class="stat-icon bg-gradient-info"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Dataset Growth (MoM)</div>
                    <div class="stat-value">{{ $stats['dataset_growth'] }}%</div>
                    <small class="{{ $stats['dataset_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                        <i class="fas {{ $stats['dataset_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                        {{ $stats['dataset_growth'] >= 0 ? 'Positive momentum' : 'Needs attention' }}
                    </small>
                </div>
                <div class="stat-icon bg-gradient-success"><i class="fas fa-arrow-trend-up"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">User Growth (MoM)</div>
                    <div class="stat-value">{{ $stats['user_growth'] }}%</div>
                    <small class="{{ $stats['user_growth'] >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                        <i class="fas {{ $stats['user_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                        {{ $stats['user_growth'] >= 0 ? 'Audience expanding' : 'Acquire more users' }}
                    </small>
                </div>
                <div class="stat-icon bg-gradient-warning"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Growth Pulse</div>
                    <div class="stat-value">{{ $stats['growth_pulse'] }}%</div>
                </div>
                <div class="stat-icon bg-gradient-dark"><i class="fas fa-wave-square"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Categories</div>
                    <div class="stat-value">{{ number_format($stats['total_categories']) }}</div>
                </div>
                <div class="stat-icon bg-gradient-info"><i class="fas fa-tags"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Published Reports</div>
                    <div class="stat-value">{{ number_format($stats['total_reports']) }}</div>
                </div>
                <div class="stat-icon bg-gradient-primary"><i class="fas fa-file-lines"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h5><i class="fas fa-chart-line me-2"></i> Dataset Trend (12 Months)</h5>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="trendLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h5><i class="fas fa-lightbulb me-2"></i> Market Insights</h5>
            </div>
            <div class="card-body-modern">
                <div class="d-flex flex-column gap-3">
                    @foreach($marketInsights as $insight)
                        <div style="padding:0.9rem;border:1px solid var(--gray-200);border-radius:12px;background:var(--gray-50);font-size:0.88rem;line-height:1.55;">
                            <i class="fas fa-sparkles me-2" style="color:var(--primary);"></i>{{ $insight }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h5><i class="fas fa-chart-column me-2"></i> Category Comparison</h5>
            </div>
            <div class="card-body-modern">
                <div class="chart-container" style="height:280px;">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                <h5><i class="fas fa-chart-pie me-2"></i> Regional Distribution</h5>
            </div>
            <div class="card-body-modern">
                <div class="chart-container" style="height:280px;">
                    <canvas id="distributionPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <h5><i class="fas fa-table me-2"></i> Latest Approved Datasets</h5>
        <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern btn-sm">View Full Data</a>
    </div>
    <div class="card-body-modern p-0">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Region</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Contributor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentData as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->category->name ?? '-' }}</td>
                            <td>{{ $item->location->state ?? ($item->location->name ?? '-') }}</td>
                            <td style="font-weight:600;color:var(--primary);">Rs {{ number_format($item->price, 2) }}</td>
                            <td>{{ optional($item->data_date)->format('M d, Y') }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No approved datasets available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const trendLabels = @json($trendLabels);
const trendValues = @json($trendValues);
const userTrendValues = @json($userTrendValues);
const categoryLabels = @json($categoryLabels);
const categoryValues = @json($categoryValues);
const distributionLabels = @json($distributionLabels);
const distributionValues = @json($distributionValues);

new Chart(document.getElementById('trendLineChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [
            {
                label: 'Datasets',
                data: trendValues,
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
            },
            {
                label: 'New Users',
                data: userTrendValues,
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.08)',
                fill: true,
                tension: 0.35,
                pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true, position: 'bottom' } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

new Chart(document.getElementById('categoryBarChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryValues,
            backgroundColor: ['#0d9488', '#0ea5e9', '#f59e0b', '#f97316', '#10b981', '#ef4444', '#14b8a6', '#64748b'],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

new Chart(document.getElementById('distributionPieChart'), {
    type: 'pie',
    data: {
        labels: distributionLabels,
        datasets: [{
            data: distributionValues,
            backgroundColor: ['#0d9488', '#06b6d4', '#f59e0b', '#f97316', '#ef4444', '#8b5cf6', '#3b82f6', '#22c55e'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endpush
