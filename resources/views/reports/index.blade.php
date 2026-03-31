@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-file-alt me-2"></i> Reports</h1>
        <p>Browse reports and export market data in CSV or PDF format.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('reports.create') }}" class="btn btn-primary-modern"><i class="fas fa-plus me-1"></i> New Report</a>
    @endif
</div>

<!-- Upload Report Section for Contributors -->
@if(auth()->user()->isContributor() || auth()->user()->isAdmin())
<div style="background: linear-gradient(135deg, rgba(13, 148, 136, 0.08) 0%, rgba(5, 150, 213, 0.08) 100%); border: 2px dashed #0d9488; border-radius: 16px; padding: 1.75rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; gap: 2rem; animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
    <div style="display: flex; align-items: center; gap: 1.5rem; flex: 1;">
        <div style="width: 4rem; height: 4rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem;">
            <i class="fas fa-file-upload"></i>
        </div>
        <div>
            <h3 style="margin: 0 0 0.3rem 0; font-size: 1.1rem; font-weight: 600; color: var(--gray-900); font-family: 'Space Grotesk', sans-serif;">Upload Your Report</h3>
            <p style="margin: 0; font-size: 0.9rem; color: var(--gray-600);">Share PDF, Excel, CSV, Word, or images with our community</p>
        </div>
    </div>
    <a href="{{ route('reports.upload.create') }}" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; padding: 0.75rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 600; white-space: nowrap; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
        <i class="fas fa-arrow-right"></i> Upload Now
    </a>
</div>

<!-- Quick Stats for Uploads -->
@php
$pendingUploads = auth()->user()->reports()->where('status', 'pending')->whereNotNull('file_size')->count();
$approvedUploads = auth()->user()->reports()->where('status', 'approved')->whereNotNull('file_size')->count();
@endphp

@if($pendingUploads > 0 || $approvedUploads > 0)
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    @if($pendingUploads > 0)
    <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 12px; padding: 1.25rem; box-shadow: 0 10px 28px rgba(17, 24, 39, 0.04); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #92400e; font-size: 1.5rem;">
            <i class="fas fa-hourglass-end"></i>
        </div>
        <div>
            <p style="margin: 0; font-size: 0.8rem; color: #92400e; font-weight: 600; text-transform: uppercase;">Pending Review</p>
            <p style="margin: 0.3rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: var(--gray-900);">{{ $pendingUploads }}</p>
        </div>
    </div>
    @endif
    
    @if($approvedUploads > 0)
    <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 12px; padding: 1.25rem; box-shadow: 0 10px 28px rgba(17, 24, 39, 0.04); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3rem; height: 3rem; background: #d1fae5; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.5rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p style="margin: 0; font-size: 0.8rem; color: #10b981; font-weight: 600; text-transform: uppercase;">Approved</p>
            <p style="margin: 0.3rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: var(--gray-900);">{{ $approvedUploads }}</p>
        </div>
    </div>
    @endif
</div>
@endif
@endif

{{-- Filter --}}
<div class="card-modern mb-4">
    <div class="card-body-modern">
        <form method="GET" class="d-flex gap-3 flex-wrap">
            <select name="category_id" class="form-select" style="max-width:220px">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="year" class="form-select" style="max-width:150px">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            <select name="region" class="form-select" style="max-width:200px">
                <option value="">All Regions</option>
                @foreach($regions as $region)
                    <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                @endforeach
            </select>
            <select name="type" class="form-select" style="max-width:200px">
                <option value="">All Types</option>
                <option value="market_analysis" {{ request('type')=='market_analysis'?'selected':'' }}>Market Analysis</option>
                <option value="price_trend" {{ request('type')=='price_trend'?'selected':'' }}>Price Trend</option>
                <option value="demand_supply" {{ request('type')=='demand_supply'?'selected':'' }}>Demand & Supply</option>
                <option value="custom" {{ request('type')=='custom'?'selected':'' }}>Custom</option>
            </select>
            <button class="btn btn-primary-modern"><i class="fas fa-filter me-1"></i> Filter</button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-modern">Reset</a>
        </form>

        <div class="d-flex gap-2 flex-wrap mt-3">
            <a href="{{ route('reports.export.csv', request()->query()) }}" class="btn btn-outline-modern">
                <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-outline-modern">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead><tr><th>Title</th><th>Type</th><th>Created By</th><th>Status</th><th>Downloads</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td><strong>{{ $report->title }}</strong><br><small class="text-muted">{{ Str::limit($report->description, 60) }}</small></td>
                            <td><span class="badge-modern badge-primary">{{ str_replace('_', ' ', ucfirst($report->type)) }}</span></td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td>
                                @if($report->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-end me-1"></i> Pending</span>
                                @elseif($report->status === 'approved')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                @elseif($report->status === 'rejected')
                                    <span class="badge bg-danger" title="{{ $report->rejection_reason }}"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $report->download_count }}</td>
                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                            <td class="d-flex gap-1">
                                @if($report->file_path)
                                    <a href="{{ route('reports.view', $report) }}" class="btn btn-sm btn-outline-modern" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('reports.download', $report) }}" class="btn btn-sm btn-primary-modern"><i class="fas fa-download"></i></a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    @if($report->status === 'pending')
                                        <form method="POST" action="{{ route('reports.approve', $report) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-sm btn-success" title="Approve" type="submit"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('reports.reject', $report) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to reject this report?');">
                                            @csrf
                                            <input type="hidden" name="rejection_reason" value="Rejected by admin">
                                            <button class="btn btn-sm btn-danger" title="Reject" type="submit"><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('reports.destroy', $report) }}" onsubmit="return confirm('Delete this report?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-modern text-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @elseif(auth()->user()->isContributor() && $report->user_id === auth()->id())
                                    <form method="POST" action="{{ route('reports.destroy', $report) }}" onsubmit="return confirm('Delete this report?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-modern text-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">No reports found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $reports->links() }}</div>
    </div>
</div>



<style>
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

:root {
    --gray-600: #6b7280;
    --gray-900: #1c1917;
}
</style>
@endsection
