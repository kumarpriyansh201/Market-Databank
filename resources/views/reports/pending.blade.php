@extends('layouts.app')

@section('content')
<div style="animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
    <!-- Page Header with Stats -->
    <div style="margin-bottom: 2rem;">
        <div style="padding: 2rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 18px; color: white; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-clipboard-check" style="font-size: 2.5rem;"></i>
                <div>
                    <h1 style="margin: 0; font-size: 1.75rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">Pending Report Approvals</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Review and approve uploaded reports from contributors</p>
                </div>
            </div>
        </div>
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

    @if ($pendingReports->count() > 0)
        <!-- Reports List -->
        <div style="display: grid; gap: 1.5rem;">
            @foreach ($pendingReports as $report)
                <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 28px rgba(17, 24, 39, 0.04); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.boxShadow='0 15px 35px rgba(17, 24, 39, 0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 10px 28px rgba(17, 24, 39, 0.04)'; this.style.transform='translateY(0)';">
                    <!-- Header Bar -->
                    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde047 100%); border-bottom: 2px solid #facc15; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem;">
                        <i class="fas fa-hourglass-end" style="color: #92400e; font-size: 1.25rem;"></i>
                        <div>
                            <p style="margin: 0; font-size: 0.75rem; color: #92400e; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pending Review</p>
                            <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #b45309;">Uploaded {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.75rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.75rem;">
                            <!-- Left: Report Details -->
                            <div>
                                <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.25rem;">
                                    <!-- File Icon -->
                                    <div style="width: 3.5rem; height: 3.5rem; background: linear-gradient(135deg, rgba(13, 148, 136, 0.1) 0%, rgba(5, 150, 213, 0.1) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: #0d9488; flex-shrink: 0;">
                                        @if (str_ends_with($report->original_filename, '.pdf'))
                                            <i class="fas fa-file-pdf"></i>
                                        @elseif (str_ends_with($report->original_filename, ['.xlsx', '.xls']))
                                            <i class="fas fa-file-excel"></i>
                                        @elseif (str_ends_with($report->original_filename, '.csv'))
                                            <i class="fas fa-file-csv"></i>
                                        @elseif (str_ends_with($report->original_filename, '.docx'))
                                            <i class="fas fa-file-word"></i>
                                        @else
                                            <i class="fas fa-file-image"></i>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.15rem; font-weight: 600; color: var(--gray-900); font-family: 'Space Grotesk', sans-serif;">{{ $report->title }}</h3>
                                        @if ($report->description)
                                            <p style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: var(--gray-600); line-height: 1.5;">{{ $report->description }}</p>
                                        @endif
                                        
                                        <!-- File Info Grid -->
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.85rem;">
                                            <div style="background: #f4f1ea; padding: 0.75rem; border-radius: 8px; border-left: 3px solid #0d9488;">
                                                <p style="margin: 0 0 0.2rem 0; color: var(--gray-500); font-weight: 600; font-size: 0.75rem; text-transform: uppercase;">File Name</p>
                                                <p style="margin: 0; color: var(--gray-900); font-weight: 500; word-break: break-all;">{{ $report->original_filename }}</p>
                                            </div>
                                            <div style="background: #f4f1ea; padding: 0.75rem; border-radius: 8px; border-left: 3px solid #0d9488;">
                                                <p style="margin: 0 0 0.2rem 0; color: var(--gray-500); font-weight: 600; font-size: 0.75rem; text-transform: uppercase;">File Size</p>
                                                <p style="margin: 0; color: var(--gray-900); font-weight: 500;">{{ $report->formatted_file_size }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Contributor Info & Download -->
                            <div>
                                <!-- Contributor Info -->
                                <div style="background: linear-gradient(135deg, #e0f2fe 0%, #e0fdf4 100%); border: 1px solid #bfdbfe; border-radius: 12px; padding: 1rem; margin-bottom: 1rem;">
                                    <p style="margin: 0 0 0.5rem 0; font-size: 0.75rem; color: #0369a1; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Submitted By</p>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            {{ substr($report->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600; color: var(--gray-900);">{{ $report->user->name }}</p>
                                            <p style="margin: 0; font-size: 0.85rem; color: var(--gray-600);">{{ $report->user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Download Button -->
                                <a href="{{ route('reports.upload.download', $report) }}" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 0.85rem 1.25rem; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    <i class="fas fa-download"></i> Download & Preview
                                </a>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="border-top: 1px solid var(--gray-200); padding-top: 1.5rem; display: grid; grid-template-columns: 1fr 1.2fr; gap: 1rem;">
                            <!-- Approve Button -->
                            <form action="{{ route('reports.approve', $report) }}" method="POST" style="display: contents;">
                                @csrf
                                <button type="submit" style="padding: 0.85rem 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(16, 185, 129, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';" onclick="return confirm('Approve this report?');">
                                    <i class="fas fa-check-circle"></i> Approve Report
                                </button>
                            </form>

                            <!-- Reject Button (Toggle) -->
                            <button type="button" style="padding: 0.85rem 1.5rem; background: white; color: #ef4444; border: 2px solid #fecaca; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem; transition: all 0.3s ease;" onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='white';" onclick="toggleRejectForm({{ $report->id }})">
                                <i class="fas fa-times-circle"></i> Reject Report
                            </button>
                        </div>

                        <!-- Reject Form (Hidden) -->
                        <div id="reject-form-{{ $report->id }}" style="display: none; margin-top: 1.5rem; padding: 1.5rem; background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; animation: slideDown 0.3s ease;">
                            <form action="{{ route('reports.reject', $report) }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; color: #991b1b; margin-bottom: 0.5rem; font-size: 0.9rem;">
                                        Rejection Reason <span style="color: #ef4444;">*</span>
                                    </label>
                                    <textarea
                                        name="rejection_reason"
                                        rows="3"
                                        placeholder="Explain why this report is being rejected..."
                                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #f87171; border-radius: 8px; font-family: 'Manrope', sans-serif; font-size: 0.9rem; transition: all 0.3s ease;"
                                        required
                                        onfocus="this.style.borderColor='#ef4444'; this.style.boxShadow='0 0 0 4px rgba(239, 68, 68, 0.12)'; this.style.outline='none';"
                                        onblur="this.style.borderColor='#f87171'; this.style.boxShadow='none';"
                                    ></textarea>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <button type="submit" style="padding: 0.75rem 1.5rem; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.75rem; transition: all 0.3s ease;" onmouseover="this.style.background='#dc2626';" onmouseout="this.style.background='#ef4444';">
                                        <i class="fas fa-check"></i> Send Rejection
                                    </button>
                                    <button type="button" style="padding: 0.75rem 1.5rem; background: white; color: #991b1b; border: 1px solid #f87171; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#fecaca';" onmouseout="this.style.background='white';" onclick="toggleRejectForm({{ $report->id }})">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $pendingReports->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div style="background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(17, 24, 39, 0.08); border-radius: 18px; padding: 4rem 2rem; text-align: center;">
            <div style="color: #d1d5db; font-size: 4rem; margin-bottom: 1rem;">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 600; color: var(--gray-900); font-family: 'Space Grotesk', sans-serif;">All Reports Reviewed!</h3>
            <p style="margin: 0 0 1.5rem 0; color: var(--gray-500); max-width: 450px; font-size: 0.95rem;">There are no pending reports waiting for approval. All submissions have been reviewed and processed.</p>
            <a href="{{ route('admin.approvals') }}" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: white; padding: 0.75rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(13, 148, 136, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-arrow-left"></i> Back to Approvals
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
    --gray-500: #736b5c;
    --gray-600: #6b7280;
    --gray-900: #1c1917;
}
</style>

<script>
function toggleRejectForm(reportId) {
    const form = document.getElementById('reject-form-' + reportId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>
@endsection
