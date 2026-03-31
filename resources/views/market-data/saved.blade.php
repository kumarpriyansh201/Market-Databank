@extends('layouts.app')
@section('title', 'Saved Datasets - Market Databank')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1>Saved Datasets</h1>
        <p>Your bookmarked approved datasets.</p>
    </div>
    <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern"><i class="fas fa-search me-1"></i> Explore Data</a>
</div>

<div class="card-modern">
    <div class="card-body-modern p-0">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($savedDatasets as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->category->name ?? '-' }}</td>
                            <td>{{ $item->location->name ?? '-' }}</td>
                            <td>Rs {{ number_format($item->price, 2) }}</td>
                            <td>{{ optional($item->data_date)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('market-data.show', $item) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-eye"></i></a>
                                <form method="POST" action="{{ route('market-data.save', $item) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-modern btn-sm"><i class="fas fa-bookmark"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No saved datasets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $savedDatasets->links('pagination::bootstrap-5') }}</div>
@endsection
