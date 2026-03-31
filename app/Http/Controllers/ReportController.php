<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\MarketData;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected const ALLOWED_MIMES = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
        'text/csv',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
    ];

    protected const ALLOWED_EXTENSIONS = ['pdf', 'xlsx', 'xls', 'csv', 'docx', 'jpg', 'jpeg', 'png'];
    protected const MAX_FILE_SIZE = 20 * 1024 * 1024;

    public function index(Request $request)
    {
        $query = Report::with('user');

        if (auth()->user()->isAdmin()) {
            // Admins see only file uploads (not manually created reports)
            $query->whereNotNull('file_size');
        } elseif (auth()->user()->isContributor()) {
            // Contributors see their own reports (all statuses) + approved reports from others
            $query->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('status', 'approved');
            });
        } else {
            // Regular users only see approved reports
            $query->where('status', 'approved');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $regions = Location::query()->select('state')->whereNotNull('state')->distinct()->orderBy('state')->pluck('state');
        $years = MarketData::query()->whereNotNull('data_date')->pluck('data_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y'))->unique()->sortDesc()->values();

        return view('reports.index', compact('reports', 'categories', 'regions', 'years'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isContributor()) {
            abort(403);
        }

        return view('reports.create');
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin() && !$request->user()->isContributor()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:market_analysis,price_trend,demand_supply,custom',
            'file' => 'nullable|file|mimes:pdf,csv,xlsx|max:10240',
        ]);

        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('reports', 'public');
        }

        Report::create($validated);

        return redirect()->route('reports.index')->with('success', 'Report created successfully!');
    }

    /**
     * Show upload form for file uploads.
     */
    public function createUpload()
    {
        if (!auth()->user()->isContributor() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('reports.upload');
    }

    /**
     * Store uploaded report file.
     */
    public function storeUpload(Request $request)
    {
        if (!$request->user()->isContributor() && !$request->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:' . implode(',', self::ALLOWED_EXTENSIONS) . '|max:' . (self::MAX_FILE_SIZE / 1024),
        ]);

        if (!$request->hasFile('file')) {
            return back()->withErrors(['file' => 'File is required.']);
        }

        $file = $request->file('file');

        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            return back()->withErrors(['file' => 'File type not allowed.']);
        }

        $timestamp = now()->timestamp;
        $userId = Auth::id();
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = "{$userId}_{$timestamp}_{uniqid()}.{$extension}";

        $path = $file->storeAs('reports', $filename, 'public');

        if (!$path) {
            return back()->withErrors(['file' => 'Failed to upload file.'])->withInput();
        }

        $user = $request->user();
        $isAdmin = $user->isAdmin();
        
        Report::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'original_filename' => $originalName,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $isAdmin ? 'approved' : 'pending',
            'approved_by' => $isAdmin ? Auth::id() : null,
            'approved_at' => $isAdmin ? now() : null,
        ]);

        $message = $isAdmin 
            ? 'Report uploaded successfully!' 
            : 'Report uploaded successfully! Awaiting admin approval.';
        
        return redirect()->route('reports.myUploads')->with('success', $message);
    }

    /**
     * List contributor's uploaded reports.
     */
    public function myUploads()
    {
        if (!auth()->user()->isContributor() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $reports = Auth::user()->reports()
            ->whereNotNull('file_size')
            ->latest()
            ->paginate(15);
        return view('reports.my-uploads', compact('reports'));
    }

    /**
     * Show pending reports for admin approval.
     */
    public function pending()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $pendingReports = Report::where('status', 'pending')
            ->whereNotNull('file_size')
            ->with('user')
            ->latest()
            ->paginate(15);
        return view('reports.pending', compact('pendingReports'));
    }

    /**
     * Approve a report (admin only).
     */
    public function approve(Report $report)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $report->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Notify contributor
        if ($report->user) {
            \App\Models\UserNotification::create([
                'user_id' => $report->user_id,
                'title' => 'Report Approved',
                'message' => "Your report '{$report->title}' has been approved.",
                'type' => 'success',
                'link' => route('reports.index'),
            ]);
        }

        // Notify all users about new approved report
        $users = \App\Models\User::where('role', 'viewer')->get();
        foreach ($users as $user) {
            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'title' => 'New Report Available',
                'message' => "New report '{$report->title}' has been uploaded by {$report->user->name}.",
                'type' => 'info',
                'link' => route('reports.index'),
            ]);
        }

        return back()->with('success', 'Report approved successfully.');
    }

    /**
     * Reject a report (admin only).
     */
    public function reject(Request $request, Report $report)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Notify contributor about rejection
        if ($report->user) {
            \App\Models\UserNotification::create([
                'user_id' => $report->user_id,
                'title' => 'Report Rejected',
                'message' => "Your report '{$report->title}' was rejected: {$request->rejection_reason}",
                'type' => 'error',
                'link' => route('reports.index'),
            ]);
        }

        return back()->with('success', 'Report rejected successfully.');
    }

    /**
     * Download a report file.
     */
    public function downloadUpload(Report $report)
    {
        if ($report->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($report->file_path, $report->original_filename);
    }

    /**
     * Delete a report (only pending reports by contributor).
     */
    public function destroyUpload(Report $report)
    {
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($report->status !== 'pending') {
            return back()->withErrors(['error' => 'Can only delete pending reports.']);
        }

        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return redirect()->route('reports.myUploads')->with('success', 'Report deleted successfully.');
    }

    /**
     * View a report file in browser.
     */
    public function view(Report $report)
    {
        if (!$report->file_path) {
            return back()->with('error', 'No file attached to this report.');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($report->file_path);
        $mimeType = Storage::disk('public')->mimeType($report->file_path);

        // For images, show directly
        if (str_starts_with($mimeType, 'image/')) {
            return response()->file($filePath);
        }

        // For PDFs, show in browser
        if ($mimeType === 'application/pdf') {
            return response()->file($filePath, ['Content-Type' => 'application/pdf']);
        }

        // For others, download
        return Storage::disk('public')->download($report->file_path, $report->original_filename);
    }

    public function download(Report $report)
    {
        if (!$report->file_path) {
            return back()->with('error', 'No file attached to this report.');
        }

        $report->increment('download_count');

        return response()->download(storage_path('app/public/' . $report->file_path));
    }

    public function destroy(Report $report)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $report->user_id) {
            abort(403);
        }

        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return back()->with('success', 'Report deleted.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $rows = $this->buildMarketDataExportQuery($request)->get();

        $filename = 'market-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product', 'Category', 'Region', 'Price', 'Demand', 'Supply', 'Date', 'Source']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->product->name ?? '-',
                    $row->category->name ?? '-',
                    $row->location->state ?? '-',
                    $row->price,
                    $row->demand_level,
                    $row->supply_level,
                    optional($row->data_date)->format('Y-m-d'),
                    $row->source,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $rows = $this->buildMarketDataExportQuery($request)->take(500)->get();

        $categoryName = null;
        if ($request->filled('category_id')) {
            $categoryName = Category::query()->whereKey($request->category_id)->value('name');
        }

        $pdf = Pdf::loadView('reports.export-pdf', [
            'rows' => $rows,
            'filters' => [
                'category' => $categoryName,
                'year' => $request->year,
                'region' => $request->region,
            ],
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('market-report-' . now()->format('Ymd-His') . '.pdf');
    }

    private function buildMarketDataExportQuery(Request $request)
    {
        $query = MarketData::approved()->with(['product', 'category', 'location']);

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

        return $query->latest('data_date');
    }
}
