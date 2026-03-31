<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MarketData;
use App\Models\Category;
use App\Models\Product;
use App\Models\Location;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ── Users ──
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            if ($request->role === 'user') {
                $query->whereIn('role', ['user', 'viewer', 'contributor']);
            } else {
                $query->where('role', $request->role);
            }
        }
        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean',
        ]);

        // Persist as viewer for compatibility with the existing users role enum.
        $validated['role'] = $validated['role'] === 'user' ? 'viewer' : 'admin';
        $validated['is_active'] = $request->has('is_active');
        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    // ── Approvals ──
    public function approvals(Request $request)
    {
        $query = MarketData::with(['product', 'location', 'user', 'category']);

        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $marketData = $query->latest()->paginate(15)->withQueryString();

        return view('admin.approvals', compact('marketData', 'status'));
    }

    public function approve(MarketData $marketData)
    {
        $marketData->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        // Notify contributor
        UserNotification::create([
            'user_id' => $marketData->user_id,
            'title' => 'Data Approved',
            'message' => "Your market data for {$marketData->product->name} has been approved.",
            'type' => 'success',
            'link' => route('market-data.show', $marketData),
        ]);

        // Notify all users about new approved dataset
        $users = User::where('role', 'viewer')->get();
        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'title' => 'New Dataset Available',
                'message' => "New dataset '{$marketData->product->name}' has been uploaded by {$marketData->user->name}.",
                'type' => 'info',
                'link' => route('market-data.show', $marketData),
            ]);
        }

        return back()->with('success', 'Market data approved!');
    }

    public function reject(Request $request, MarketData $marketData)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $marketData->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Notify contributor about rejection
        UserNotification::create([
            'user_id' => $marketData->user_id,
            'title' => 'Data Rejected',
            'message' => "Your market data for {$marketData->product->name} was rejected: {$request->rejection_reason}",
            'type' => 'error',
            'link' => route('market-data.show', $marketData),
        ]);

        return back()->with('success', 'Market data rejected.');
    }

    // ── Categories ──
    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
        ]);

        Category::create($validated);

        return back()->with('success', 'Category created!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $category->update($validated);

        return back()->with('success', 'Category updated!');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted!');
    }

    // ── Products ──
    public function products(Request $request)
    {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        Product::create($validated);

        return back()->with('success', 'Product created!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $product->update($validated);

        return back()->with('success', 'Product updated!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted!');
    }

    // ── Locations ──
    public function locations()
    {
        $locations = Location::withCount('marketData')->get();
        return view('admin.locations', compact('locations'));
    }

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        Location::create($validated);

        return back()->with('success', 'Location created!');
    }

    public function deleteLocation(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted!');
    }
}
