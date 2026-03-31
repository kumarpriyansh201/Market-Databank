@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-bell me-2"></i> Notifications</h1>
        <p>Stay updated with your latest activity alerts.</p>
    </div>
    @if($notifications->where('is_read', false)->count())
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button class="btn btn-outline-modern"><i class="fas fa-check-double me-1"></i> Mark All Read</button>
        </form>
    @endif
</div>

<div class="card-modern">
    <div class="card-body-modern">
        @forelse($notifications as $n)
            <a href="{{ $n->link }}" class="d-flex align-items-start gap-3 p-3 {{ !$n->is_read ? 'bg-light' : '' }}" style="border-bottom: 1px solid #eee; text-decoration: none; display: block; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.backgroundColor='rgba(13,148,136,0.05)'" onmouseout="this.style.backgroundColor='{{ !$n->is_read ? '#f3f4f6' : 'transparent' }}'">
                <div style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                    background: {{ $n->type == 'success' ? 'rgba(16,185,129,0.1)' : ($n->type == 'error' ? 'rgba(239,68,68,0.1)' : ($n->type == 'info' ? 'rgba(59,130,246,0.1)' : 'rgba(79,70,229,0.1)')) }};
                    color: {{ $n->type == 'success' ? '#10b981' : ($n->type == 'error' ? '#ef4444' : ($n->type == 'info' ? '#3b82f6' : '#4f46e5')) }};">
                    <i class="fas {{ $n->type == 'success' ? 'fa-check-circle' : ($n->type == 'error' ? 'fa-times-circle' : ($n->type == 'info' ? 'fa-info-circle' : 'fa-bell')) }}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1" style="font-weight:{{ $n->is_read ? '400' : '600' }}; color: #000;">{{ $n->title }}</h6>
                    <p class="text-muted mb-1" style="font-size:0.9rem;">{{ $n->message }}</p>
                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                </div>
                <div>
                    @if(!$n->is_read)
                        <form method="POST" action="{{ route('notifications.read', $n) }}" onclick="event.stopPropagation();">
                            @csrf
                            <button class="btn btn-sm btn-outline-modern" title="Mark as read"><i class="fas fa-check"></i></button>
                        </form>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <p>No notifications yet.</p>
            </div>
        @endforelse
        <div class="mt-3">{{ $notifications->links() }}</div>
    </div>
</div>
@endsection
