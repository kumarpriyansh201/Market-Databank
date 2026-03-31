@extends('layouts.app')
@section('title', 'Submit Market Data')

@section('content')
<div class="page-header">
    <h1>Submit Market Data</h1>
    <p>Contribute new market data to help build a comprehensive market database.</p>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <form method="POST" action="{{ route('market-data.store') }}" class="form-modern">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-select" required id="productSelect">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-category="{{ $product->category_id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required id="categorySelect">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location *</label>
                    <select name="location_id" class="form-select" required>
                        <option value="">Select Location</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}, {{ $loc->state }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Data Date *</label>
                    <input type="date" name="data_date" class="form-control" value="{{ old('data_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price (₹) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required placeholder="e.g. 150.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Min Price (₹)</label>
                    <input type="number" name="min_price" class="form-control" step="0.01" min="0" value="{{ old('min_price') }}" placeholder="Optional">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Max Price (₹)</label>
                    <input type="number" name="max_price" class="form-control" step="0.01" min="0" value="{{ old('max_price') }}" placeholder="Optional">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Demand Level * (1-5)</label>
                    <select name="demand_level" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('demand_level', 3) == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supply Level * (1-5)</label>
                    <select name="supply_level" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('supply_level', 3) == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Source</label>
                    <input type="text" name="source" class="form-control" value="{{ old('source') }}" placeholder="e.g. Local Market, APMC, Survey">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional observations or notes...">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12">
                    <div style="background:#eff6ff;border-radius:10px;padding:1rem;font-size:0.85rem;color:#1e40af;">
                        <i class="fas fa-info-circle me-1"></i>
                        Your submission will be reviewed by an admin before being published. You'll receive a notification once it's approved.
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-paper-plane me-2"></i> Submit Data
                    </button>
                    <a href="{{ route('market-data.index') }}" class="btn btn-outline-modern ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('productSelect')?.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const catId = option.dataset.category;
        if (catId) {
            document.getElementById('categorySelect').value = catId;
        }
    });
</script>
@endpush
