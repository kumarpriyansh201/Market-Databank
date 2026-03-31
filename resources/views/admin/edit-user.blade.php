@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-edit me-2"></i> Edit User</h1>
    <p>Update user information for <strong>{{ $user->name }}</strong>.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-body-modern">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-modern">
                    @csrf @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role *</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                                <option value="user" {{ $user->role!='admin'?'selected':'' }}>User</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">Account Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-modern"><i class="fas fa-save me-2"></i> Save Changes</button>
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-modern ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-body-modern text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;display:block;margin:0 auto 1rem;">
                @else
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;margin:0 auto 1rem;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                <p><span class="badge-modern badge-primary">{{ $user->roleLabel() }}</span></p>
                <small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
