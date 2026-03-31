@extends('layouts.app')
@section('title', 'Data Approvals')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1><i class="fas fa-clipboard-check me-2"></i> Data Approvals</h1>
        <p>Review and manage submitted market data entries.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.pending') }}" class="btn btn-outline-modern">
            <i class="fas fa-file me-1"></i> Pending Reports
        </a>
    </div>
</div>

{{-- Status Filter --}}
<div class="card-modern mb-4">
    <div class="card-body-modern d-flex gap-2 flex-wrap">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $val => $label)
            <a href="{{ route('admin.approvals', ['status' => $val]) }}"
               class="btn {{ $status == $val ? 'btn-primary-modern' : 'btn-outline-modern' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

{{-- Data Table --}}
<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Submitted By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marketData as $md)
                        <tr>
                            <td>{{ $md->product->name ?? '-' }}</td>
                            <td>{{ $md->location->name ?? '-' }}</td>
                            <td>₹{{ number_format($md->price, 2) }}</td>
                            <td>{{ $md->user->name ?? '-' }}</td>
                            <td>{{ $md->data_date->format('M d, Y') }}</td>
                            <td>
                                @if($md->status == 'pending')
                                    <span class="badge-modern badge-warning">Pending</span>
                                @elseif($md->status == 'approved')
                                    <span class="badge-modern badge-success">Approved</span>
                                @else
                                    <span class="badge-modern badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('market-data.show', $md) }}" class="btn btn-sm btn-outline-modern me-1"><i class="fas fa-eye"></i></a>
                                @if($md->status == 'pending')
                                    <form method="POST" action="{{ route('admin.approvals.approve', $md) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Approve"><i class="fas fa-check"></i></button>
                                    </form>
                                    <button class="btn btn-sm btn-danger ms-1" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $md->id }}"><i class="fas fa-times"></i></button>

                                    {{-- Reject Modal --}}
                                    <div class="modal fade" id="rejectModal{{ $md->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.approvals.reject', $md) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Entry</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label class="form-label">Reason for rejection *</label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">No data entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $marketData->links() }}</div>
    </div>
</div>
@endsection
