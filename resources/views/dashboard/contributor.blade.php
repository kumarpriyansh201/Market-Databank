@extends('layouts.app')
@section('title', 'Contributor Dashboard - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Contributor Dashboard</h1>
        <p>Manage your datasets, status pipeline, and engagement performance.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('market-data.mine') }}" class="btn btn-outline-modern">
            <i class="fas fa-table-list me-1"></i> My Datasets
        </a>
        <a href="{{ route('market-data.create') }}" class="btn btn-primary-modern">
            <i class="fas fa-upload me-1"></i> Upload Dataset
        </a>
        <a href="{{ route('reports.upload.create') }}" class="btn btn-outline-modern">
            <i class="fas fa-file-upload me-1"></i> Upload Report
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Total Uploads</div><div class="stat-value">{{ $stats['total_uploads'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Pending</div><div class="stat-value">{{ $stats['pending_uploads'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Approved</div><div class="stat-value">{{ $stats['approved_uploads'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Rejected</div><div class="stat-value">{{ $stats['rejected_uploads'] }}</div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card-modern">
            <div class="card-header-modern d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-cloud-upload-alt me-2"></i>Recent Uploads</h5>
                <a href="{{ route('reports.myUploads') }}" class="btn btn-sm btn-outline-modern">View All Reports</a>
            </div>
            <div class="card-body-modern p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUploads as $dataset)
                                <tr>
                                    <td>{{ $dataset->product->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge-modern {{ $dataset->status === 'approved' ? 'badge-success' : ($dataset->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                            {{ ucfirst($dataset->status) }}
                                        </span>
                                    </td>
                                    <td>Rs {{ number_format($dataset->price, 2) }}</td>
                                    <td>{{ optional($dataset->data_date)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('market-data.show', $dataset) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('market-data.edit', $dataset) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4">No uploads yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-modern">
            <div class="card-header-modern">
                <h5><i class="fas fa-chart-simple me-2"></i>Performance Metrics</h5>
            </div>
            <div class="card-body-modern">
                <div class="d-flex flex-column gap-2">
                    @forelse($performanceRows as $row)
                        <div style="padding:0.75rem;border:1px solid var(--gray-200);border-radius:10px;">
                            <div style="font-weight:600;">{{ $row->product->name ?? 'Dataset #' . $row->id }}</div>
                            <div style="font-size:0.8rem;color:var(--gray-500);">Views: {{ $row->view_count }} | Downloads: {{ $row->download_count }}</div>
                        </div>
                    @empty
                        <p class="mb-0" style="color:var(--gray-500);">Metrics will appear after users view or download your datasets.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
