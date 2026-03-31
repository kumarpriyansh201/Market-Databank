<?php

namespace App\Http\Controllers;

use App\Models\MarketData;
use App\Models\DatasetInteraction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MarketDataController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketData::with(['product', 'category', 'location', 'user'])->approved();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('year')) {
            $query->whereYear('data_date', $request->year);
        }
        if ($request->filled('region')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('state', $request->region);
            });
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('date_from')) {
            $query->where('data_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('data_date', '<=', $request->date_to);
        }

        $marketData = $query->latest('data_date')->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();
        $regions = Location::query()->select('state')->whereNotNull('state')->distinct()->orderBy('state')->pluck('state');
        $years = MarketData::query()->whereNotNull('data_date')->pluck('data_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y'))->unique()->sortDesc()->values();

        return view('market-data.index', compact('marketData', 'categories', 'locations', 'regions', 'years'));
    }

    public function mine(Request $request)
    {
        $query = MarketData::with(['product', 'category', 'location'])
            ->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $datasets = $query->latest('data_date')->paginate(15)->withQueryString();

        return view('market-data.mine', compact('datasets'));
    }

    public function saved(Request $request)
    {
        $savedDatasets = $request->user()->savedDatasets()
            ->with(['product', 'category', 'location', 'user'])
            ->approved()
            ->latest('market_data.data_date')
            ->paginate(15);

        return view('market-data.saved', compact('savedDatasets'));
    }

    public function show(MarketData $marketData)
    {
        if (!$this->canViewDataset($marketData, request()->user())) {
            abort(403);
        }

        $marketData->load(['product', 'category', 'location', 'user', 'comments.user', 'approvedBy']);

        DatasetInteraction::create([
            'market_data_id' => $marketData->id,
            'user_id' => request()->user()->id,
            'interaction_type' => 'view',
        ]);

        $relatedData = MarketData::approved()
            ->where('product_id', $marketData->product_id)
            ->where('id', '!=', $marketData->id)
            ->latest('data_date')
            ->take(5)
            ->get();

        $priceHistory = MarketData::approved()
            ->where('product_id', $marketData->product_id)
            ->where('location_id', $marketData->location_id)
            ->orderBy('data_date')
            ->select('data_date', 'price', 'min_price', 'max_price')
            ->take(30)
            ->get();

        return view('market-data.show', compact('marketData', 'relatedData', 'priceHistory'));
    }

    public function download(Request $request, MarketData $marketData): StreamedResponse
    {
        if (!$this->canViewDataset($marketData, $request->user())) {
            abort(403);
        }

        DatasetInteraction::create([
            'market_data_id' => $marketData->id,
            'user_id' => $request->user()->id,
            'interaction_type' => 'download',
        ]);

        $filename = 'dataset-' . $marketData->id . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($marketData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Dataset ID', 'Product', 'Category', 'Location', 'Price', 'Min Price', 'Max Price',
                'Demand Level', 'Supply Level', 'Status', 'Data Date', 'Source', 'Contributor', 'Notes'
            ]);

            fputcsv($handle, [
                $marketData->id,
                $marketData->product?->name,
                $marketData->category?->name,
                $marketData->location?->name,
                $marketData->price,
                $marketData->min_price,
                $marketData->max_price,
                $marketData->demand_level,
                $marketData->supply_level,
                $marketData->status,
                optional($marketData->data_date)->format('Y-m-d'),
                $marketData->source,
                $marketData->user?->name,
                $marketData->notes,
            ]);

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function toggleSave(Request $request, MarketData $marketData)
    {
        if ($marketData->status !== 'approved') {
            return back()->with('error', 'Only approved datasets can be saved.');
        }

        $user = $request->user();

        if ($user->savedDatasets()->where('market_data_id', $marketData->id)->exists()) {
            $user->savedDatasets()->detach($marketData->id);

            return back()->with('success', 'Dataset removed from saved list.');
        }

        $user->savedDatasets()->attach($marketData->id);

        return back()->with('success', 'Dataset saved successfully.');
    }

    public function create()
    {
        if (!$this->canContribute(auth()->user())) {
            abort(403);
        }

        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();

        return view('market-data.create', compact('products', 'categories', 'locations'));
    }

    public function store(Request $request)
    {
        if (!$this->canContribute($request->user())) {
            abort(403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'price' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'demand_level' => 'required|integer|min:1|max:5',
            'supply_level' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:255',
            'data_date' => 'required|date',
        ]);

        $validated['user_id'] = $request->user()->id;

        if (!$request->user()->isAdmin()) {
            $validated['status'] = 'pending';
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
            $validated['rejection_reason'] = null;
        }

        MarketData::create($validated);

        return redirect()->route('market-data.index')
            ->with('success', 'Market data submitted successfully! It will be visible after admin approval.');
    }

    public function edit(MarketData $marketData)
    {
        if (!$this->canManageDataset(auth()->user(), $marketData)) {
            abort(403);
        }

        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();

        return view('market-data.edit', compact('marketData', 'products', 'categories', 'locations'));
    }

    public function update(Request $request, MarketData $marketData)
    {
        if (!$this->canManageDataset(auth()->user(), $marketData)) {
            abort(403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'price' => 'required|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'demand_level' => 'required|integer|min:1|max:5',
            'supply_level' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:255',
            'data_date' => 'required|date',
        ]);

        if (!$request->user()->isAdmin()) {
            $validated['status'] = 'pending';
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
            $validated['rejection_reason'] = null;
        }

        $marketData->update($validated);

        return redirect()->route('market-data.index')
            ->with('success', 'Market data updated successfully!');
    }

    public function destroy(MarketData $marketData)
    {
        if (!$this->canManageDataset(auth()->user(), $marketData)) {
            abort(403);
        }

        $marketData->delete();

        return redirect()->route('market-data.index')
            ->with('success', 'Market data deleted successfully!');
    }

    private function canContribute($user): bool
    {
        return $user->isAdmin() || $user->isContributor();
    }

    private function canManageDataset($user, MarketData $marketData): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isContributor() && $marketData->user_id === $user->id;
    }

    private function canViewDataset(MarketData $marketData, $user): bool
    {
        if ($marketData->status === 'approved') {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isContributor() && $marketData->user_id === $user->id;
    }
}
