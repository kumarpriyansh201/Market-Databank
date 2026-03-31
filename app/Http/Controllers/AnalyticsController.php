<?php

namespace App\Http\Controllers;

use App\Models\MarketData;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $locations = Location::all();
        $products = Product::where('is_active', true)->get();

        // Price trends (last 30 days)
        $priceTrends = MarketData::approved()
            ->where('data_date', '>=', now()->subDays(30))
            ->select('data_date', DB::raw('AVG(price) as avg_price'))
            ->groupBy('data_date')
            ->orderBy('data_date')
            ->get();

        // Category distribution
        $categoryDistribution = MarketData::approved()
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Top products by data points
        $topProducts = MarketData::approved()
            ->select('product_id', DB::raw('COUNT(*) as count'), DB::raw('AVG(price) as avg_price'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Location-wise average prices
        $locationPrices = MarketData::approved()
            ->select('location_id', DB::raw('AVG(price) as avg_price'), DB::raw('COUNT(*) as count'))
            ->groupBy('location_id')
            ->with('location')
            ->get();

        // Demand vs supply overview
        $demandSupply = MarketData::approved()
            ->select(
                DB::raw('AVG(demand_level) as avg_demand'),
                DB::raw('AVG(supply_level) as avg_supply')
            )
            ->first();

        return view('analytics.index', compact(
            'categories', 'locations', 'products',
            'priceTrends', 'categoryDistribution', 'topProducts',
            'locationPrices', 'demandSupply'
        ));
    }

    public function chartData(Request $request)
    {
        $type = $request->get('type', 'price_trend');

        switch ($type) {
            case 'price_trend':
                $productId = $request->get('product_id');
                $locationId = $request->get('location_id');

                $query = MarketData::approved()
                    ->where('data_date', '>=', now()->subDays(90))
                    ->select('data_date', DB::raw('AVG(price) as avg_price'));

                if ($productId) $query->where('product_id', $productId);
                if ($locationId) $query->where('location_id', $locationId);

                $data = $query->groupBy('data_date')->orderBy('data_date')->get();

                return response()->json([
                    'labels' => $data->pluck('data_date')->map(fn($d) => $d->format('M d')),
                    'data' => $data->pluck('avg_price'),
                ]);

            case 'category_pie':
                $data = MarketData::approved()
                    ->select('category_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('category_id')
                    ->with('category')
                    ->get();

                return response()->json([
                    'labels' => $data->map(fn($d) => $d->category->name ?? 'Unknown'),
                    'data' => $data->pluck('count'),
                ]);

            case 'demand_supply':
                $data = MarketData::approved()
                    ->select(
                        'product_id',
                        DB::raw('AVG(demand_level) as avg_demand'),
                        DB::raw('AVG(supply_level) as avg_supply')
                    )
                    ->groupBy('product_id')
                    ->with('product')
                    ->take(10)
                    ->get();

                return response()->json([
                    'labels' => $data->map(fn($d) => $d->product->name ?? 'Unknown'),
                    'demand' => $data->pluck('avg_demand'),
                    'supply' => $data->pluck('avg_supply'),
                ]);

            default:
                return response()->json([]);
        }
    }
}
