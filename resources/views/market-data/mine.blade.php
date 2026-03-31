@extends('layouts.app')
@section('title', 'My Datasets - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>My Datasets</h1>
        <p>Manage your own uploaded datasets and their approval status.</p>
    </div>
    <a href="{{ route('market-data.create') }}" class="btn btn-primary-modern"><i class="fas fa-upload me-1"></i> Upload Dataset</a>
</div>

<div class="card-modern mb-4">
    <div class="card-body-modern">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Product name">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary-modern w-100">Filter</button></div>
        </form>
    </div>
</div>

<div class="card-modern">
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
                    @forelse($datasets as $dataset)
                        <tr>
                            <td>{{ $dataset->product->name ?? '-' }}</td>
                            <td>{{ ucfirst($dataset->status) }}</td>
                            <td>Rs {{ number_format($dataset->price, 2) }}</td>
                            <td>{{ optional($dataset->data_date)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('market-data.show', $dataset) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('market-data.edit', $dataset) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('market-data.destroy', $dataset) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-modern btn-sm" onclick="return confirm('Delete this dataset?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No datasets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $datasets->links('pagination::bootstrap-5') }}</div>
@endsection
