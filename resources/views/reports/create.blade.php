@extends('layouts.app')
@section('title', 'Create Report')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-circle-plus me-2"></i> Create Report</h1>
    <p>Upload a new market analysis report.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-body-modern">
                <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="form-modern">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="market_analysis" {{ old('type')=='market_analysis'?'selected':'' }}>Market Analysis</option>
                            <option value="price_trend" {{ old('type')=='price_trend'?'selected':'' }}>Price Trend</option>
                            <option value="demand_supply" {{ old('type')=='demand_supply'?'selected':'' }}>Demand & Supply</option>
                            <option value="custom" {{ old('type')=='custom'?'selected':'' }}>Custom</option>
                        </select>
                        @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attach File (PDF, CSV, XLSX — max 10 MB)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.csv,.xlsx">
                        @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary-modern"><i class="fas fa-save me-2"></i> Create Report</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-modern ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-body-modern">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i> Tips</h6>
                <ul class="list-unstyled text-muted" style="font-size:0.9rem;">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-1"></i> Choose the correct report type for easy filtering.</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-1"></i> Attach your analysis file (PDF, CSV, or XLSX).</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-1"></i> Add a clear description to help others understand the report.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
