@extends('layouts.app')
@section('title', 'Edit Market Data')

@section('content')
<div class="page-header">
    <h1>Edit Market Data</h1>
    <p>Update the market data entry for {{ $marketData->product->name ?? 'this product' }}.</p>
</div>

<div class="card-modern">
    <div class="card-body-modern">
        <form method="POST" action="{{ route('market-data.update', $marketData) }}" class="form-modern">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-select" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $marketData->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $marketData->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location *</label>
                    <select name="location_id" class="form-select" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ $marketData->location_id == $loc->id ? 'selected' : '' }}>{{ $loc->name }}, {{ $loc->state }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Data Date *</label>
                    <input type="date" name="data_date" class="form-control" value="{{ $marketData->data_date->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price (₹) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ $marketData->price }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Min Price (₹)</label>
                    <input type="number" name="min_price" class="form-control" step="0.01" min="0" value="{{ $marketData->min_price }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Max Price (₹)</label>
                    <input type="number" name="max_price" class="form-control" step="0.01" min="0" value="{{ $marketData->max_price }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Demand Level *</label>
                    <select name="demand_level" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $marketData->demand_level == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supply Level *</label>
                    <select name="supply_level" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $marketData->supply_level == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Very Low','Low','Medium','High','Very High'][$i-1] }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Source</label>
                    <input type="text" name="source" class="form-control" value="{{ $marketData->source }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $marketData->notes }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i> Update Data
                    </button>
                    <a href="{{ route('market-data.show', $marketData) }}" class="btn btn-outline-modern ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
