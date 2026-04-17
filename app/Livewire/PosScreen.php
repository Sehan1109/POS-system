<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class PosScreen extends Component
{
    public $categories = [];
    public $products = [];
    public $activeCategoryId = null;
    public $searchQuery = '';

    // Cart state
    public $cart = [];
    public $subtotal = 0;
    public $taxRate = 0.10; // 10% VAT
    public $taxAmount = 0;
    public $total = 0;
    public $paymentMethod = 'cash';

    public function mount()
    {
        $this->categories = Category::all();
        $this->loadProducts();
    }

    public function updatedSearchQuery()
    {
        $this->loadProducts();
    }

    public function setCategory($categoryId)
    {
        $this->activeCategoryId = $categoryId;
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $query = Product::query();

        if ($this->activeCategoryId) {
            $query->where('category_id', $this->activeCategoryId);
        }

        if (strlen($this->searchQuery) > 0) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
        }

        $this->products = $query->get();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            session()->flash('error', 'Product out of stock!');
            return;
        }

        // Check if already in cart
        $existingItemKey = null;
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItemKey = $key;
                break;
            }
        }

        if ($existingItemKey !== null) {
            // Check stock limit
            if ($this->cart[$existingItemKey]['quantity'] >= $product->stock_quantity) {
                 session()->flash('error', 'Cannot add more than available stock!');
                 return;
            }
            
            $this->cart[$existingItemKey]['quantity']++;
            $this->cart[$existingItemKey]['sub_total'] = $this->cart[$existingItemKey]['quantity'] * $product->selling_price;
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_price' => $product->selling_price,
                'quantity' => 1,
                'sub_total' => $product->selling_price,
            ];
        }

        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Re-index array
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('sub_total');
        $this->taxAmount = $this->subtotal * $this->taxRate;
        $this->total = $this->subtotal + $this->taxAmount;
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        try {
            DB::beginTransaction();

            // 1. Create Sale
            $sale = Sale::create([
                'user_id' => auth()->id() ?? 1, // Fallback for testing
                'total_amount' => $this->total,
                'discount' => 0,
                'payment_method' => $this->paymentMethod,
            ]);

            // 2. Create Items and Deduct Stock
            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'sub_total' => $item['sub_total'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            // Reset Cart
            $this->cart = [];
            $this->calculateTotals();
            $this->loadProducts(); // Refresh stock counts

            session()->flash('success', 'Payment successful! Order #' . $sale->id);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pos-screen');
    }
}