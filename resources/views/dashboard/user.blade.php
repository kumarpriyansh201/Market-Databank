@extends('layouts.app')
@section('title', 'User Dashboard - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>User Dashboard</h1>
        <p>Track your data activity and discover approved datasets.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern">
            <i class="fas fa-search me-1"></i> Explore Data
        </a>
        <a href="{{ route('saved-datasets.index') }}" class="btn btn-primary-modern">
            <i class="fas fa-bookmark me-1"></i> Saved Datasets
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Recent Datasets</div>
            <div class="stat-value">{{ $stats['recent_dataset_count'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Saved Datasets</div>
            <div class="stat-value">{{ $stats['saved_dataset_count'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Views</div>
            <div class="stat-value">{{ $stats['view_count'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Downloads</div>
            <div class="stat-value">{{ $stats['download_count'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card-modern">
            <div class="card-header-modern">
                <h5><i class="fas fa-clock me-2"></i>Recently Added Datasets</h5>
            </div>
            <div class="card-body-modern p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDatasets as $dataset)
                                <tr>
                                    <td>{{ $dataset->product->name ?? '-' }}</td>
                                    <td>{{ $dataset->category->name ?? '-' }}</td>
                                    <td>Rs {{ number_format($dataset->price, 2) }}</td>
                                    <td>{{ optional($dataset->data_date)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('market-data.show', $dataset) }}" class="btn btn-outline-modern btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No datasets available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-modern mb-4">
            <div class="card-header-modern">
                <h5><i class="fas fa-bookmark me-2"></i>Recently Saved</h5>
            </div>
            <div class="card-body-modern">
                <div class="d-flex flex-column gap-2">
                    @forelse($savedDatasets as $saved)
                        <a href="{{ route('market-data.show', $saved) }}" class="text-decoration-none" style="padding:0.75rem;border:1px solid var(--gray-200);border-radius:10px;display:block;">
                            <div style="font-weight:600;color:var(--gray-900)">{{ $saved->product->name ?? '-' }}</div>
                            <div style="font-size:0.8rem;color:var(--gray-500)">{{ $saved->location->name ?? '-' }} | {{ optional($saved->data_date)->format('M d, Y') }}</div>
                        </a>
                    @empty
                        <p class="mb-0" style="color:var(--gray-500);">No saved datasets yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-header-modern">
                <h5><i class="fas fa-history me-2"></i>View & Download History</h5>
            </div>
            <div class="card-body-modern">
                <div class="d-flex flex-column gap-2">
                    @forelse($recentHistory as $history)
                        <div style="padding:0.7rem;border:1px solid var(--gray-200);border-radius:10px;">
                            <div style="font-weight:600;font-size:0.85rem;">{{ $history->marketData->product->name ?? 'Dataset' }}</div>
                            <div style="font-size:0.78rem;color:var(--gray-500);">
                                {{ ucfirst($history->interaction_type) }} | {{ $history->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="mb-0" style="color:var(--gray-500);">No history yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
