@extends('layouts.app')

@section('content')
<div style="animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div style="padding: 2rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 18px; color: white; flex: 1; min-width: 300px;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-list-check" style="font-size: 2.5rem;"></i>
                <div>
                    <h1 style="margin: 0; font-size: 1.75rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">My Uploaded Reports</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Track and manage your report submissions</p>
                </div>
            </div>
        </div>
        <a href="{{ route('reports.upload.create') }}" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); height: fit-content; margin-top: 0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <i class="fas fa-plus"></i> New Upload
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 1px solid #6ee7b7; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; animation: slideDown 0.3s ease;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem; margin-top: 0.25rem;"></i>
            <div>
                <p style="margin: 0; color: #065f46; font-weight: 500;">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; gap: 1rem;">
            <i class="fas fa-circle-exclamation" style="color: #ef4444; font-size: 1.25rem; margin-top: 0.25rem;"></i>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #991b1b; font-weight: 600;">Error</h3>
                <ul style="margin: 0; padding-left: 1.5rem; color: #b91c1c;">
                    @foreach ($errors->all() as $error)
                        <li style="margin: 0.3rem 0;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($reports->count() > 0)
        <!-- Reports Grid -->
        <div style="display: grid; gap: 1.25rem;">
            @foreach ($reports as $report)
                <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 14px; padding: 1.5rem; box-shadow: 0 10px 28px rgba(17, 24, 39, 0.04); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.boxShadow='0 15px 35px rgba(17, 24, 39, 0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 10px 28px rgba(17, 24, 39, 0.04)'; this.style.transform='translateY(0)';">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: start;">
                        <!-- Left: File Info -->
                        <div>
                            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                                <!-- File Icon -->
                                <div style="width: 3rem; height: 3rem; background: linear-gradient(135deg, rgba(13, 148, 136, 0.1) 0%, rgba(5, 150, 213, 0.1) 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #0d9488;">
                                    @if ($report->file_extension === 'pdf')
                                        <i class="fas fa-file-pdf"></i>
                                    @elseif (in_array($report->file_extension, ['xlsx', 'xls']))
                                        <i class="fas fa-file-excel"></i>
                                    @elseif ($report->file_extension === 'csv')
                                        <i class="fas fa-file-csv"></i>
                                    @elseif ($report->file_extension === 'docx')
                                        <i class="fas fa-file-word"></i>
                                    @else
                                        <i class="fas fa-file-image"></i>
                                    @endif
                                </div>

                                <!-- File Details -->
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 0.3rem 0; font-size: 1.05rem; font-weight: 600; color: var(--gray-900); font-family: 'Space Grotesk', sans-serif;">{{ $report->title }}</h3>
                                    @if ($report->description)
                                        <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: var(--gray-500); line-height: 1.4;">{{ Str::limit($report->description, 100) }}</p>
                                    @endif
                                    <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem; color: var(--gray-600);">
                                        <span><i class="fas fa-file" style="margin-right: 0.4rem; color: #0d9488;"></i>{{ $report->original_filename }}</span>
                                        <span><i class="fas fa-database" style="margin-right: 0.4rem; color: #0d9488;"></i>{{ $report->formatted_file_size }}</span>
                                        <span><i class="fas fa-calendar" style="margin-right: 0.4rem; color: #0d9488;"></i>{{ $report->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Status & Actions -->
                        <div style="text-align: right;">
                            <!-- Status Badge -->
                            <div style="display: inline-block; margin-bottom: 1rem;">
                                @if ($report->status === 'pending')
                                    <span style="padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.3px; background: #fef3c7; color: #92400e; text-transform: uppercase;">
                                        <i class="fas fa-hourglass-end"></i> {{ $report->status_label }}
                                    </span>
                                @elseif ($report->status === 'approved')
                                    <span style="padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.3px; background: #d1fae5; color: #065f46; text-transform: uppercase;">
                                        <i class="fas fa-check-circle"></i> {{ $report->status_label }}
                                    </span>
                                @else
                                    <span style="padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.3px; background: #fee2e2; color: #991b1b; text-transform: uppercase;">
                                        <i class="fas fa-times-circle"></i> {{ $report->status_label }}
                                    </span>
                                @endif
                            </div>

                            <!-- Rejection Reason -->
                            @if ($report->status === 'rejected' && $report->rejection_reason)
                                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; text-align: left;">
                                    <p style="margin: 0 0 0.4rem 0; font-size: 0.75rem; color: #991b1b; font-weight: 600; text-transform: uppercase;">Rejection Reason</p>
                                    <p style="margin: 0; font-size: 0.85rem; color: #b91c1c; line-height: 1.4;">{{ $report->rejection_reason }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div style="display: flex; gap: 0.75rem; flex-direction: column;">
                                <a href="{{ route('reports.upload.download', $report) }}" style="padding: 0.5rem 1rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(13, 148, 136, 0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    <i class="fas fa-download"></i> Download
                                </a>

                                @if ($report->status === 'pending')
                                    <form action="{{ route('reports.upload.destroy', $report) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this report? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="width: 100%; padding: 0.5rem 1rem; background: white; color: #ef4444; border: 1px solid #fecaca; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease;" onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='white';">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $reports->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 18px; padding: 4rem 2rem; text-align: center;">
            <div style="color: var(--gray-400); font-size: 4rem; margin-bottom: 1rem;">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: var(--gray-900); font-family: 'Space Grotesk', sans-serif;">No Reports Yet</h3>
            <p style="margin: 0 0 1.5rem 0; color: var(--gray-500); max-width: 400px;">You haven't uploaded any reports yet. Start sharing your insights with the community.</p>
            <a href="{{ route('reports.upload.create') }}" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; padding: 0.75rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-cloud-arrow-up"></i> Upload First Report
            </a>
        </div>
    @endif
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

:root {
    --gray-400: #bfdbfe;
    --gray-500: #736b5c;
    --gray-600: #6b7280;
    --gray-900: #1c1917;
}
</style>
@endsection
