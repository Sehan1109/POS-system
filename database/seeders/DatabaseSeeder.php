<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Create Admin, Manager and Cashier
        User::firstOrCreate(['email' => 'admin@pos.com'], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'manager@pos.com'], [
            'name' => 'Manager User',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        User::firstOrCreate(['email' => 'cashier@pos.com'], [
            'name' => 'Alex Cashier',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        // 2. Create Categories & Products
        $categories = ['Beverages', 'Snacks', 'Electronics', 'Stationary'];

        foreach ($categories as $categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
            if ($category->wasRecentlyCreated) {
                Product::factory(10)->create(['category_id' => $category->id]);
            }
        }

        // 3. Seed default System Settings
        $defaults = [
            'shop_name' => 'My POS Shop',
            'shop_address' => '123 Main Street, City',
            'shop_phone' => '+1 555-000-0000',
            'currency_symbol' => '$',
            'tax_rate' => '0',
            'receipt_footer' => 'Thank you for shopping with us!',
        ];
        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // 4. Sample Supplier
        Supplier::firstOrCreate(['name' => 'Default Supplier'], [
            'contact_person' => 'John Doe',
            'phone' => '+1 555-111-2222',
            'email' => 'supplier@example.com',
            'address' => '456 Warehouse Road',
        ]);

        // 5. Sample Customer
        Customer::firstOrCreate(['email' => 'customer@example.com'], [
            'name' => 'Walk-in Customer',
            'phone' => '+1 555-333-4444',
            'credit_limit' => 500.00,
        ]);
    }
}
