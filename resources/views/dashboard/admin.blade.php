@extends('layouts.app')
@section('title', 'Admin Dashboard - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Monitor review workflow and latest dataset submissions.</p>
    </div>
    <a href="{{ route('admin.approvals') }}" class="btn btn-primary-modern"><i class="fas fa-check-double me-1"></i> Review Approvals</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="stat-card"><div class="stat-label">Approved Datasets</div><div class="stat-value">{{ $stats['total_datasets'] }}</div></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="stat-label">Pending Approvals</div><div class="stat-value">{{ $stats['pending_approvals'] }}</div></div></div>
    <div class="col-md-4"><div class="stat-card"><div class="stat-label">Rejected Datasets</div><div class="stat-value">{{ $stats['rejected_datasets'] }}</div></div></div>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <h5><i class="fas fa-table me-2"></i>Latest Submissions</h5>
    </div>
    <div class="card-body-modern p-0">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Contributor</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentData as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ optional($item->data_date)->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4">No data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
