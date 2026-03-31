@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-users me-2"></i> Manage Users</h1>
        <p>View and manage all registered users.</p>
    </div>
</div>

{{-- Filter --}}
<div class="card-modern mb-4">
    <div class="card-body-modern">
        <form method="GET" class="d-flex gap-3 flex-wrap">
            <input type="text" name="search" class="form-control" style="max-width:260px" placeholder="Search name or email..." value="{{ request('search') }}">
            <select name="role" class="form-select" style="max-width:160px">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                <option value="user" {{ request('role')=='user'?'selected':'' }}>User</option>
            </select>
            <button class="btn btn-primary-modern"><i class="fas fa-search me-1"></i> Filter</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-modern">Reset</a>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card-modern">
    <div class="card-body-modern">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($u->avatar)
                                        <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;display:block;">
                                    @else
                                        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;">
                                            {{ strtoupper(substr($u->name,0,1)) }}
                                        </div>
                                    @endif
                                    {{ $u->name }}
                                </div>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge-modern badge-{{ $u->isAdmin() ? 'primary' : 'info' }}">{{ $u->roleLabel() }}</span></td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge-modern badge-success">Active</span>
                                @else
                                    <span class="badge-modern badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $u->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-modern me-1"><i class="fas fa-edit"></i></a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.delete', $u) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-modern text-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
