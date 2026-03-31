<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketData;
use App\Models\DatasetInteraction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isContributor()) {
            return $this->contributorDashboard($user);
        }

        if ($user->isUser()) {
            return $this->userDashboard($user);
        }

        return $this->adminDashboard($request);
    }

    private function userDashboard($user)
    {
        $recentDatasets = MarketData::approved()
            ->with(['product', 'category', 'location', 'user'])
            ->latest('data_date')
            ->take(8)
            ->get();

        $savedDatasets = $user->savedDatasets()
            ->approved()
            ->with(['product', 'category', 'location'])
            ->latest('saved_datasets.created_at')
            ->take(8)
            ->get();

        $interactionStats = DatasetInteraction::query()
            ->where('user_id', $user->id)
            ->selectRaw("SUM(CASE WHEN interaction_type = 'view' THEN 1 ELSE 0 END) as views_count")
            ->selectRaw("SUM(CASE WHEN interaction_type = 'download' THEN 1 ELSE 0 END) as downloads_count")
            ->first();

        $recentHistory = DatasetInteraction::query()
            ->where('user_id', $user->id)
            ->with(['marketData.product', 'marketData.location'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.user', [
            'stats' => [
                'recent_dataset_count' => $recentDatasets->count(),
                'saved_dataset_count' => $user->savedDatasets()->count(),
                'view_count' => (int) ($interactionStats->views_count ?? 0),
                'download_count' => (int) ($interactionStats->downloads_count ?? 0),
            ],
            'recentDatasets' => $recentDatasets,
            'savedDatasets' => $savedDatasets,
            'recentHistory' => $recentHistory,
        ]);
    }

    private function contributorDashboard($user)
    {
        $myDatasets = MarketData::query()
            ->where('user_id', $user->id)
            ->with(['product', 'category', 'location'])
            ->latest('data_date');

        $statusSummary = MarketData::query()
            ->where('user_id', $user->id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $performanceRows = MarketData::query()
            ->where('user_id', $user->id)
            ->with('product')
            ->withCount([
                'interactions as view_count' => fn ($q) => $q->where('interaction_type', 'view'),
                'interactions as download_count' => fn ($q) => $q->where('interaction_type', 'download'),
            ])
            ->orderByDesc('view_count')
            ->take(10)
            ->get();

        return view('dashboard.contributor', [
            'stats' => [
                'total_uploads' => $myDatasets->count(),
                'pending_uploads' => (int) ($statusSummary['pending'] ?? 0),
                'approved_uploads' => (int) ($statusSummary['approved'] ?? 0),
                'rejected_uploads' => (int) ($statusSummary['rejected'] ?? 0),
            ],
            'recentUploads' => $myDatasets->take(8)->get(),
            'performanceRows' => $performanceRows,
        ]);
    }

    private function adminDashboard(Request $request)
    {
        $stats = [
            'total_datasets' => MarketData::approved()->count(),
            'pending_approvals' => MarketData::pending()->count(),
            'rejected_datasets' => MarketData::rejected()->count(),
        ];

        $recentData = MarketData::with(['product', 'category', 'location', 'user'])
            ->latest('data_date')
            ->take(8)
            ->get();

        return view('dashboard.admin', [
            'stats' => $stats,
            'recentData' => $recentData,
            'isAdmin' => $request->user()->isAdmin(),
        ]);
    }
}
