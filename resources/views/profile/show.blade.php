@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<style>
    .password-toggle {
        position: relative;
    }
    .password-toggle .toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        font-size: 0.95rem;
        transition: color 0.2s;
        z-index: 10;
        padding: 0.5rem;
    }
    .password-toggle .toggle-btn:hover {
        color: #0d9488;
    }
    .password-toggle .form-control {
        padding-right: 2.5rem;
    }
</style>
<div class="page-header">
    <h1><i class="fas fa-user-circle me-2"></i> My Profile</h1>
    <p>Manage your account information and password.</p>
</div>

<div class="row g-4">
    {{-- Profile Info --}}
    <div class="col-md-8">
        <div class="card-modern mb-4">
            <div class="card-body-modern">
                <h5 class="mb-3"><i class="fas fa-id-card me-2 text-primary"></i> Profile Information</h5>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form-modern">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png">
                            @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary-modern"><i class="fas fa-save me-2"></i> Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card-modern">
            <div class="card-body-modern">
                <h5 class="mb-3"><i class="fas fa-lock me-2 text-secondary"></i> Change Password</h5>
                <form method="POST" action="{{ route('profile.password') }}" class="form-modern">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Current Password *</label>
                            <div class="password-toggle">
                                <input type="password" name="current_password" class="form-control" required>
                                <button type="button" class="toggle-btn" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New Password *</label>
                            <div class="password-toggle">
                                <input type="password" name="password" class="form-control" required>
                                <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirm New Password *</label>
                            <div class="password-toggle">
                                <input type="password" name="password_confirmation" class="form-control" required>
                                <button type="button" class="toggle-btn" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-modern"><i class="fas fa-key me-2"></i> Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-md-4">
        <div class="card-modern text-center">
            <div class="card-body-modern">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="width:100px;height:100px;border-radius:50%;object-fit:cover;margin-bottom:1rem;">
                @else
                    <div style="width:100px;height:100px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;margin:0 auto 1rem;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
                <h5>{{ $user->name }}</h5>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <span class="badge-modern badge-primary">{{ ucfirst($user->role) }}</span>
                @if($user->phone)
                    <p class="text-muted mt-2 mb-0"><i class="fas fa-phone me-1"></i> {{ $user->phone }}</p>
                @endif
                @if($user->bio)
                    <hr>
                    <p class="text-muted" style="font-size:0.9rem;">{{ $user->bio }}</p>
                @endif
                <hr>
                <small class="text-muted">Member since {{ $user->created_at->format('M d, Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function togglePassword(fieldId) {
        const field = document.querySelector('input[name="' + fieldId + '"]') || document.getElementById(fieldId);
        const btn = event.target.closest('.toggle-btn');
        const icon = btn.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
