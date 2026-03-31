<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\MarketData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::updateOrCreate(['email' => 'admin@market.com'], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create standard user account
        User::updateOrCreate(['email' => 'user@market.com'], [
            'name' => 'Platform User',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'is_active' => true,
        ]);

        // Categories
        $categories = [
            ['name' => 'Economy', 'slug' => 'economy', 'icon' => 'fas fa-chart-line', 'description' => 'GDP, inflation, trade and macro indicators'],
            ['name' => 'Technology', 'slug' => 'technology', 'icon' => 'fas fa-microchip', 'description' => 'Digital adoption, internet, AI and software metrics'],
            ['name' => 'Population', 'slug' => 'population', 'icon' => 'fas fa-users', 'description' => 'Demographics, migration and workforce indicators'],
            ['name' => 'Business', 'slug' => 'business', 'icon' => 'fas fa-briefcase', 'description' => 'Company growth, market value and commercial activity'],
        ];
        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Locations
        $locations = [
            ['name' => 'Mumbai', 'state' => 'Maharashtra', 'slug' => 'mumbai'],
            ['name' => 'Delhi', 'state' => 'Delhi', 'slug' => 'delhi'],
            ['name' => 'Bangalore', 'state' => 'Karnataka', 'slug' => 'bangalore'],
            ['name' => 'Chennai', 'state' => 'Tamil Nadu', 'slug' => 'chennai'],
            ['name' => 'Kolkata', 'state' => 'West Bengal', 'slug' => 'kolkata'],
            ['name' => 'Hyderabad', 'state' => 'Telangana', 'slug' => 'hyderabad'],
            ['name' => 'Pune', 'state' => 'Maharashtra', 'slug' => 'pune'],
            ['name' => 'Ahmedabad', 'state' => 'Gujarat', 'slug' => 'ahmedabad'],
        ];
        foreach ($locations as $loc) {
            Location::updateOrCreate(['slug' => $loc['slug']], $loc);
        }

        // Products
        $products = [
            ['name' => 'GDP Growth Rate', 'slug' => 'gdp-growth-rate', 'category_slug' => 'economy', 'unit' => 'percent'],
            ['name' => 'Inflation Index', 'slug' => 'inflation-index', 'category_slug' => 'economy', 'unit' => 'index'],
            ['name' => 'Internet Penetration', 'slug' => 'internet-penetration', 'category_slug' => 'technology', 'unit' => 'percent'],
            ['name' => 'Cloud Adoption', 'slug' => 'cloud-adoption', 'category_slug' => 'technology', 'unit' => 'percent'],
            ['name' => 'Urban Population Rate', 'slug' => 'urban-population-rate', 'category_slug' => 'population', 'unit' => 'percent'],
            ['name' => 'Workforce Participation', 'slug' => 'workforce-participation', 'category_slug' => 'population', 'unit' => 'percent'],
            ['name' => 'SME Revenue Growth', 'slug' => 'sme-revenue-growth', 'category_slug' => 'business', 'unit' => 'percent'],
            ['name' => 'Retail Sales Index', 'slug' => 'retail-sales-index', 'category_slug' => 'business', 'unit' => 'index'],
        ];
        foreach ($products as $prod) {
            $category = Category::where('slug', $prod['category_slug'])->first();
            Product::updateOrCreate(
                ['slug' => $prod['slug']],
                [
                    'name' => $prod['name'],
                    'category_id' => $category?->id,
                    'unit' => $prod['unit'],
                ]
            );
        }

        // Sample Market Data
        $user = User::where('email', 'user@market.com')->first();
        $admin = User::where('role', 'admin')->first();
        $productIds = Product::pluck('id')->all();
        $locationIds = Location::pluck('id')->all();

        for ($i = 0; $i < 300; $i++) {
            $productId = Arr::random($productIds);
            $product = Product::find($productId);
            $price = rand(65, 980) + (rand(0, 99) / 100);

            MarketData::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'category_id' => $product->category_id,
                'location_id' => Arr::random($locationIds),
                'price' => $price,
                'min_price' => $price * 0.9,
                'max_price' => $price * 1.1,
                'demand_level' => rand(1, 5),
                'supply_level' => rand(1, 5),
                'source' => ['Official Bureau', 'Industry Survey', 'Partner Dataset', 'Regulatory Filing'][rand(0, 3)],
                'data_date' => now()->subDays(rand(0, 90)),
                'status' => ['pending', 'approved', 'approved', 'approved'][rand(0, 3)],
                'approved_by' => rand(0, 1) ? $admin->id : null,
                'approved_at' => rand(0, 1) ? now()->subDays(rand(0, 30)) : null,
            ]);
        }
    }
}
