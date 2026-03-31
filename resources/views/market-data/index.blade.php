@extends('layouts.app')
@section('title', 'Market Data - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Market Data</h1>
        <p>Browse and search through verified market data entries.</p>
    </div>
    @if(auth()->user()->isContributor() || auth()->user()->isAdmin())
        <a href="{{ route('market-data.create') }}" class="btn btn-primary-modern">
            <i class="fas fa-upload me-1"></i> Upload Dataset
        </a>
    @endif
</div>

<!-- Filters -->
<div class="card-modern mb-4">
    <div class="card-body-modern">
        <form method="GET" action="{{ route('market-data.index') }}" class="form-modern">
            <div class="row g-3 align-items-end" style="background:var(--gray-50); padding: 1.25rem; border-radius:12px; border: 1px solid var(--gray-200);">
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;color:var(--gray-500);"><i class="fas fa-search me-1"></i>Search</label>
                    <input type="text" name="search" class="form-control bg-white" placeholder="Product name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;color:var(--gray-500);">Category</label>
                    <select name="category_id" class="form-select bg-white">
                        <option value="">All</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;color:var(--gray-500);">Year</label>
                    <select name="year" class="form-select bg-white">
                        <option value="">All</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;color:var(--gray-500);">Region</label>
                    <select name="region" class="form-select bg-white">
                        <option value="">All</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.75rem;text-transform:uppercase;color:var(--gray-500);">Location</label>
                    <select name="location_id" class="form-select bg-white">
                        <option value="">All</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary-modern w-100" style="height:38px;"><i class="fas fa-filter"></i></button>
                </div>
            </div>
        </form>

        <div class="d-flex gap-2 flex-wrap mt-3">
            <a href="{{ route('reports.export.csv', request()->query()) }}" class="btn btn-outline-modern btn-sm">
                <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-outline-modern btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card-modern">
    <div class="card-header-modern">
        <h5><i class="fas fa-table me-2"></i>Results ({{ $marketData->total() }} entries)</h5>
    </div>
    <div class="card-body-modern p-0">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Demand</th>
                        <th>Supply</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marketData as $item)
                        <tr>
                            <td style="font-weight:600;">{{ $item->product->name ?? 'N/A' }}</td>
                            <td><span class="badge-modern badge-primary">{{ $item->category->name ?? 'N/A' }}</span></td>
                            <td><i class="fas fa-map-marker-alt me-1" style="color:var(--danger);font-size:0.75rem;"></i>{{ $item->location->name ?? 'N/A' }}</td>
                            <td style="font-weight:700;color:var(--primary);">₹{{ number_format($item->price, 2) }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-circle" style="font-size:0.45rem;color:{{ $i <= $item->demand_level ? '#10b981' : '#e2e8f0' }};"></i>
                                @endfor
                            </td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-circle" style="font-size:0.45rem;color:{{ $i <= $item->supply_level ? '#4f46e5' : '#e2e8f0' }};"></i>
                                @endfor
                            </td>
                            <td>{{ $item->data_date->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('market-data.show', $item) }}" class="btn btn-outline-modern btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('market-data.download', $item) }}" class="btn btn-outline-modern btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>

                                @if(auth()->user()->isUser())
                                    <form method="POST" action="{{ route('market-data.save', $item) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-modern btn-sm" title="Save dataset">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
                                    </form>
                                @endif

                                @if(auth()->user()->isAdmin() || (auth()->user()->isContributor() && auth()->id() === $item->user_id))
                                    <a href="{{ route('market-data.edit', $item) }}" class="btn btn-outline-modern btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4" style="color:var(--gray-500);">
                                <i class="fas fa-inbox" style="font-size:2rem;"></i>
                                <p class="mt-2 mb-0">No market data found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $marketData->links('pagination::bootstrap-5') }}
</div>
@endsection
