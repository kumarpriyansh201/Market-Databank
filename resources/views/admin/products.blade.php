@extends('layouts.app')
@section('title', 'Manage Products')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-boxes-stacked me-2"></i> Manage Products</h1>
        <p>Create and manage trackable products.</p>
    </div>
    <button class="btn btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus me-1"></i> Add Product</button>
</div>

{{-- Filters --}}
<div class="card-modern mb-4">
    <div class="card-body-modern">
        <form method="GET" class="d-flex gap-3 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:240px" placeholder="Search product..." value="{{ request('search') }}">
            <select name="category_id" class="form-select" style="max-width:200px">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary-modern"><i class="fas fa-search me-1"></i> Filter</button>
            <a href="{{ route('admin.products') }}" class="btn btn-outline-modern">Reset</a>
        </form>
    </div>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead><tr><th>Product</th><th>Category</th><th>Unit</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td>{{ $p->category->name ?? '-' }}</td>
                            <td>{{ $p->unit }}</td>
                            <td>
                                @if($p->is_active)
                                    <span class="badge-modern badge-success">Active</span>
                                @else
                                    <span class="badge-modern badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-modern me-1" data-bs-toggle="modal" data-bs-target="#editProd{{ $p->id }}"><i class="fas fa-edit"></i></button>
                                <form method="POST" action="{{ route('admin.products.delete', $p) }}" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-modern text-danger"><i class="fas fa-trash"></i></button>
                                </form>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editProd{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.products.update', $p) }}">
                                                @csrf @method('PUT')
                                                <div class="modal-header"><h5 class="modal-title">Edit Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                <div class="modal-body">
                                                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ $p->name }}" required></div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Category *</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $p->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3"><label class="form-label">Unit *</label><input type="text" name="unit" class="form-control" value="{{ $p->unit }}" required></div>
                                                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ $p->description }}</textarea></div>
                                                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $p->is_active ? 'checked' : '' }}><label class="form-check-label">Active</label></div>
                                                </div>
                                                <div class="modal-footer"><button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary-modern">Save</button></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $products->links() }}</div>
    </div>
</div>

{{-- Add Product Modal --}}
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Unit *</label><input type="text" name="unit" class="form-control" value="kg" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary-modern">Create</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
