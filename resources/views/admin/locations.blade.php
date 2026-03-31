@extends('layouts.app')
@section('title', 'Manage Locations')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-map-marker-alt me-2"></i> Manage Locations</h1>
        <p>Add and manage market locations.</p>
    </div>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addLocationModal"><i class="fas fa-plus me-1"></i> Add Location</button>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead><tr><th>Name</th><th>State</th><th>Country</th><th>Data Entries</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($locations as $loc)
                        <tr>
                            <td><strong>{{ $loc->name }}</strong></td>
                            <td>{{ $loc->state ?? '-' }}</td>
                            <td>{{ $loc->country }}</td>
                            <td>{{ $loc->market_data_count }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.locations.delete', $loc) }}" class="d-inline" onsubmit="return confirm('Delete this location?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-modern text-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No locations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Location Modal --}}
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.locations.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Location</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">City / Market Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">State</label><input type="text" name="state" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="India"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary-modern">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
