@extends('layouts.app')
@section('title', 'Manage Categories')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-tags me-2"></i> Manage Categories</h1>
        <p>Create, edit, and manage product categories.</p>
    </div>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-plus me-1"></i> Add Category</button>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead><tr><th>Name</th><th>Description</th><th>Icon</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td><strong>{{ $cat->name }}</strong></td>
                            <td>{{ Str::limit($cat->description, 60) }}</td>
                            <td>@if($cat->icon)<i class="{{ $cat->icon }}"></i>@else -@endif</td>
                            <td>{{ $cat->products_count }}</td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge-modern badge-success">Active</span>
                                @else
                                    <span class="badge-modern badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-modern me-1" data-bs-toggle="modal" data-bs-target="#editCat{{ $cat->id }}"><i class="fas fa-edit"></i></button>
                                <form method="POST" action="{{ route('admin.categories.delete', $cat) }}" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-modern text-danger"><i class="fas fa-trash"></i></button>
                                </form>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editCat{{ $cat->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.categories.update', $cat) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                <div class="modal-body">
                                                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $cat->name }}" required></div>
                                                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ $cat->description }}</textarea></div>
                                                    <div class="mb-3"><label class="form-label">Icon (Font Awesome class)</label><input type="text" name="icon" class="form-control" value="{{ $cat->icon }}" placeholder="fas fa-leaf"></div>
                                                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $cat->is_active ? 'checked' : '' }}><label class="form-check-label">Active</label></div>
                                                </div>
                                                <div class="modal-footer"><button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary-modern">Save</button></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Icon (Font Awesome class)</label><input type="text" name="icon" class="form-control" placeholder="fas fa-leaf"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary-modern">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
