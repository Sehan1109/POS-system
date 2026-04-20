<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class PosScreen extends Component
{
    public $categories = [];
    public $products = [];
    public $activeCategoryId = null;
    public $searchQuery = '';
    public $activeTab = 'catalog';
    public $showFlash = true;
    public $flashMessage = null;
    public $flashType = null;

    public $orders = [];
    public $customers = [];
    public $selectedCustomerId = null;

    public $shop_name = '';
    public $shop_address = '';
    public $shop_phone = '';
    public $currency_symbol = '$';
    public $taxRate = 0.10; // 10% VAT
    public $receipt_footer = '';

    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerEmail = '';
    public $newCustomerCreditLimit = 0;

    // Cart state
    public $cart = [];
    public $subtotal = 0;
    public $taxAmount = 0;
    public $total = 0;
    public $paymentMethod = 'cash';
    public $viewingOrder = null;

    public function mount()
    {
        $this->categories = Category::all();
        $this->loadProducts();
        $this->loadOrders();
        $this->loadCustomers();
        $this->loadSettings();
    }

    public function updatedSearchQuery()
    {
        if ($this->activeTab == 'catalog') {
            $this->loadProducts();
        } elseif ($this->activeTab == 'orders') {
            $this->loadOrders();
        } elseif ($this->activeTab == 'customers') {
            $this->loadCustomers();
        }
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

        if ($this->activeTab == 'catalog' && strlen($this->searchQuery) > 0) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('barcode', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $this->products = $query->get();
    }

    public function loadOrders()
    {
        $query = Sale::with(['customer', 'user'])->latest();

        if ($this->activeTab == 'orders' && strlen($this->searchQuery) > 0) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }

        $this->orders = $query->take(12)->get();
    }

    public function viewOrder($orderId)
    {
        $this->viewingOrder = Sale::with(['customer', 'user', 'items.product'])
            ->find($orderId);

        if (!$this->viewingOrder) {
            $this->flashMessage = 'Order not found.';
            $this->flashType = 'error';
            $this->showFlash = true;
        }
    }

    public function closeOrderModal()
    {
        $this->viewingOrder = null;
    }

    public function loadCustomers()
    {
        $query = Customer::query();

        if ($this->activeTab == 'customers' && strlen($this->searchQuery) > 0) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('phone', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $this->customers = $query->latest()->get();
    }

    public function loadSettings()
    {
        $this->shop_name = Setting::get('shop_name', 'My POS Shop');
        $this->shop_address = Setting::get('shop_address', '123 Main Street, City');
        $this->shop_phone = Setting::get('shop_phone', '+1 555-000-0000');
        $this->currency_symbol = Setting::get('currency_symbol', '$');
        $this->receipt_footer = Setting::get('receipt_footer', 'Thank you for shopping with us!');
        $this->taxRate = floatval(Setting::get('tax_rate', 0));
        $this->calculateTotals();
    }

    public function addCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required|string|max:255',
            'newCustomerPhone' => 'nullable|string|max:50',
            'newCustomerEmail' => 'nullable|email|max:255',
            'newCustomerCreditLimit' => 'nullable|numeric|min:0',
        ]);

        Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
            'email' => $this->newCustomerEmail,
            'credit_limit' => $this->newCustomerCreditLimit ?: 0,
            'credit_used' => 0,
        ]);

        $this->reset(['newCustomerName', 'newCustomerPhone', 'newCustomerEmail', 'newCustomerCreditLimit']);
        $this->loadCustomers();

        $this->flashMessage = 'Customer added successfully.';
        $this->flashType = 'success';
        $this->showFlash = true;
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            $this->flashMessage = 'Customer not found.';
            $this->flashType = 'error';
            $this->showFlash = true;
            return;
        }

        $this->selectedCustomerId = $customer->id;
        $this->flashMessage = 'Selected customer: ' . $customer->name;
        $this->flashType = 'success';
        $this->showFlash = true;
    }

    public function clearCustomer()
    {
        $this->selectedCustomerId = null;
        $this->flashMessage = 'Customer selection cleared.';
        $this->flashType = 'success';
        $this->showFlash = true;
    }

    public function saveSettings()
    {
        $this->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string|max:500',
            'shop_phone' => 'nullable|string|max:100',
            'currency_symbol' => 'required|string|max:10',
            'receipt_footer' => 'nullable|string|max:500',
            'taxRate' => 'required|numeric|min:0',
        ]);

        Setting::set('shop_name', $this->shop_name);
        Setting::set('shop_address', $this->shop_address);
        Setting::set('shop_phone', $this->shop_phone);
        Setting::set('currency_symbol', $this->currency_symbol);
        Setting::set('receipt_footer', $this->receipt_footer);
        Setting::set('tax_rate', $this->taxRate);

        $this->loadSettings();
        $this->flashMessage = 'Settings saved.';
        $this->flashType = 'success';
        $this->showFlash = true;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            $this->flashMessage = 'Product out of stock!';
            $this->flashType = 'error';
            $this->showFlash = true;
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
                $this->flashMessage = 'Cannot add more than available stock!';
                $this->flashType = 'error';
                $this->showFlash = true;
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
        $this->taxAmount = $this->subtotal * ($this->taxRate / 100);
        $this->total = $this->subtotal + $this->taxAmount;
    }

    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            $this->flashMessage = 'Cart is empty!';
            $this->flashType = 'error';
            $this->showFlash = true;
            return;
        }

        try {
            DB::beginTransaction();

            // 1. Create Sale
            $sale = Sale::create([
                'user_id' => auth()->id() ?? 1, // Fallback for testing
                'customer_id' => $this->selectedCustomerId,
                'invoice_number' => 'POS-' . now()->format('YmdHis'),
                'total_amount' => $this->total,
                'discount' => 0,
                'tax_amount' => $this->taxAmount,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
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
            $this->loadOrders();

            $this->flashMessage = 'Payment successful! Order #' . $sale->id;
            $this->flashType = 'success';
            $this->showFlash = true;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->flashMessage = 'Checkout failed: ' . $e->getMessage();
            $this->flashType = 'error';
            $this->showFlash = true;
        }
    }

    public function render()
    {
        return view('livewire.pos-screen');
    }
}