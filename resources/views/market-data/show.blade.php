@extends('layouts.app')
@section('title', $marketData->product->name . ' - Market Data')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('market-data.index') }}" style="color:var(--primary);text-decoration:none;">Market Data</a></li>
            <li class="breadcrumb-item active">{{ $marketData->product->name ?? 'Details' }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card-modern mb-4">
            <div class="card-header-modern">
                <h5><i class="fas fa-chart-pie me-2"></i>{{ $marketData->product->name ?? 'Product' }}</h5>
                <div class="d-flex align-items-center gap-2">
                    @if($marketData->status === 'approved')
                        <span class="badge-modern badge-success">Approved</span>
                    @elseif($marketData->status === 'pending')
                        <span class="badge-modern badge-warning">Pending</span>
                    @else
                        <span class="badge-modern badge-danger">Rejected</span>
                    @endif
                    <a href="{{ route('market-data.download', $marketData) }}" class="btn btn-outline-modern btn-sm">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    @if(auth()->user()->isUser() && $marketData->status === 'approved')
                        <form method="POST" action="{{ route('market-data.save', $marketData) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-modern btn-sm"><i class="fas fa-bookmark me-1"></i> Save</button>
                        </form>
                    @endif
                    @if(auth()->user()->isAdmin() || (auth()->user()->isContributor() && auth()->id() === $marketData->user_id))
                        <a href="{{ route('market-data.edit', $marketData) }}" class="btn btn-outline-modern btn-sm"><i class="fas fa-edit me-1"></i> Edit</a>
                        <form method="POST" action="{{ route('market-data.destroy', $marketData) }}" class="d-inline" onsubmit="return confirm('Delete this dataset?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-modern btn-sm"><i class="fas fa-trash me-1"></i> Delete</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body-modern">
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div style="background:linear-gradient(135deg,#e0e7ff,#f8fafc);border-radius:12px;padding:1.5rem;">
                            <div style="font-size:0.75rem;color:var(--gray-500);text-transform:uppercase;letter-spacing:1px;">Price</div>
                            <div style="font-size:2rem;font-weight:800;color:var(--primary);">₹{{ number_format($marketData->price, 2) }}</div>
                            <div style="font-size:0.8rem;color:var(--gray-500);">per {{ $marketData->product->unit ?? 'unit' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div style="background:linear-gradient(135deg,#d1fae5,#f8fafc);border-radius:12px;padding:1.5rem;">
                            <div style="font-size:0.75rem;color:var(--gray-500);text-transform:uppercase;letter-spacing:1px;">Min Price</div>
                            <div style="font-size:2rem;font-weight:800;color:var(--success);">₹{{ number_format($marketData->min_price ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div style="background:linear-gradient(135deg,#fee2e2,#f8fafc);border-radius:12px;padding:1.5rem;">
                            <div style="font-size:0.75rem;color:var(--gray-500);text-transform:uppercase;letter-spacing:1px;">Max Price</div>
                            <div style="font-size:2rem;font-weight:800;color:var(--danger);">₹{{ number_format($marketData->max_price ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <div style="background:var(--gray-50);border-radius:10px;padding:1rem;">
                            <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:0.5rem;">Demand Level</div>
                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <div style="flex:1;height:8px;border-radius:4px;background:{{ $i <= $marketData->demand_level ? '#10b981' : '#e2e8f0' }};"></div>
                                @endfor
                            </div>
                            <div style="font-size:0.75rem;color:var(--gray-500);margin-top:0.25rem;">{{ $marketData->demand_level }}/5</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:var(--gray-50);border-radius:10px;padding:1rem;">
                            <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:0.5rem;">Supply Level</div>
                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <div style="flex:1;height:8px;border-radius:4px;background:{{ $i <= $marketData->supply_level ? '#4f46e5' : '#e2e8f0' }};"></div>
                                @endfor
                            </div>
                            <div style="font-size:0.75rem;color:var(--gray-500);margin-top:0.25rem;">{{ $marketData->supply_level }}/5</div>
                        </div>
                    </div>
                </div>

                @if($marketData->notes)
                    <div class="mt-3" style="background:var(--gray-50);border-radius:10px;padding:1rem;">
                        <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:0.5rem;">Notes</div>
                        <p style="margin:0;font-size:0.875rem;">{{ $marketData->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Price History Chart -->
        @if($priceHistory->count() > 1)
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5><i class="fas fa-chart-line me-2"></i>Price History</h5>
                </div>
                <div class="card-body-modern">
                    <div class="chart-container">
                        <canvas id="priceHistoryChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        <!-- Comments -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5><i class="fas fa-comments me-2"></i>Comments ({{ $marketData->comments->count() }})</h5>
            </div>
            <div class="card-body-modern">
                <form method="POST" action="{{ route('comments.store', $marketData) }}" class="mb-4 form-modern">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="body" class="form-control" placeholder="Add a comment..." required>
                        <button type="submit" class="btn btn-primary-modern">Post</button>
                    </div>
                </form>

                @forelse($marketData->comments as $comment)
                    <div class="d-flex gap-2 mb-3" style="padding-bottom:0.75rem;border-bottom:1px solid var(--gray-100);">
                        <div class="user-avatar" style="width:32px;height:32px;font-size:0.7rem;flex-shrink:0;">
                            @if($comment->user->avatar)
                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}">
                            @else
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:600;font-size:0.85rem;">{{ $comment->user->name }} <span style="font-weight:400;color:var(--gray-500);font-size:0.75rem;">{{ $comment->created_at->diffForHumans() }}</span></div>
                            <p style="margin:0.25rem 0 0;font-size:0.85rem;">{{ $comment->body }}</p>
                        </div>
                        @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="color:var(--danger);background:none;border:none;"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p style="text-align:center;color:var(--gray-500);font-size:0.85rem;">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card-modern mb-4">
            <div class="card-header-modern">
                <h5><i class="fas fa-info-circle me-2"></i>Details</h5>
            </div>
            <div class="card-body-modern">
                <div class="mb-3">
                    <div style="font-size:0.75rem;color:var(--gray-500);">Category</div>
                    <div style="font-weight:500;">{{ $marketData->category->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.75rem;color:var(--gray-500);">Location</div>
                    <div style="font-weight:500;"><i class="fas fa-map-marker-alt me-1" style="color:var(--danger);"></i>{{ $marketData->location->name ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.75rem;color:var(--gray-500);">Data Date</div>
                    <div style="font-weight:500;">{{ $marketData->data_date->format('F d, Y') }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.75rem;color:var(--gray-500);">Source</div>
                    <div style="font-weight:500;">{{ $marketData->source ?? 'Not specified' }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.75rem;color:var(--gray-500);">Submitted By</div>
                    <div style="font-weight:500;">{{ $marketData->user->name ?? 'Unknown' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:var(--gray-500);">Submitted On</div>
                    <div style="font-weight:500;">{{ $marketData->created_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>

        @if($relatedData->count() > 0)
            <div class="card-modern">
                <div class="card-header-modern">
                    <h5><i class="fas fa-link me-2"></i>Related Data</h5>
                </div>
                <div class="card-body-modern p-0">
                    @foreach($relatedData as $related)
                        <a href="{{ route('market-data.show', $related) }}" class="d-flex align-items-center justify-content-between px-3 py-2 text-decoration-none" style="border-bottom:1px solid var(--gray-100);">
                            <div>
                                <div style="font-weight:500;font-size:0.85rem;color:var(--gray-900);">{{ $related->location->name ?? 'N/A' }}</div>
                                <div style="font-size:0.75rem;color:var(--gray-500);">{{ $related->data_date->format('M d, Y') }}</div>
                            </div>
                            <div style="font-weight:700;color:var(--primary);">₹{{ number_format($related->price, 2) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($priceHistory->count() > 1)
<script>
    new Chart(document.getElementById('priceHistoryChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($priceHistory->pluck('data_date')->map(fn($d) => $d->format('M d'))) !!},
            datasets: [{
                label: 'Price (₹)',
                data: {!! json_encode($priceHistory->pluck('price')) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: false, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endif
@endpush
