<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Setting;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CashierDashboard extends Component
{
    public $activeTab = 'overview';
    public $searchQuery = '';
    
    public $showFlash = false;
    public $flashMessage = null;
    public $flashType = null;

    // Overview Data
    public $total_sales = 0;
    public $total_products = 0;
    public $low_stock = 0;

    // Orders Data
    public $orders = [];
    public $viewingOrder = null;

    // Customers Data
    public $customers = [];
    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerEmail = '';
    public $newCustomerCreditLimit = 0;

    // Settings Data
    public $shop_name = '';
    public $shop_address = '';
    public $shop_phone = '';
    public $currency_symbol = '$';
    public $taxRate = 0.10;
    public $receipt_footer = '';

    public function mount()
    {
        $this->loadOverview();
        $this->loadOrders();
        $this->loadCustomers();
        $this->loadSettings();
    }

    public function updatedSearchQuery()
    {
        if ($this->activeTab == 'orders') {
            $this->loadOrders();
        } elseif ($this->activeTab == 'customers') {
            $this->loadCustomers();
        }
    }

    public function loadOverview()
    {
        $this->total_sales = Sale::sum('total_amount');
        $this->total_products = Product::count();
        $this->low_stock = Product::where('stock_quantity', '<=', 10)->count();
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

        $this->orders = $query->take(20)->get();
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

    public $customerToDeleteId = null;
    public $deleteReason = '';

    public function confirmDeleteCustomer($id)
    {
        $this->customerToDeleteId = $id;
        $this->deleteReason = '';
    }

    public function cancelDeleteCustomer()
    {
        $this->customerToDeleteId = null;
        $this->deleteReason = '';
    }

    public function deleteCustomer()
    {
        $this->validate([
            'deleteReason' => 'required|string|min:5|max:500'
        ]);

        $customer = Customer::find($this->customerToDeleteId);
        if ($customer) {
            $customer->delete_reason = $this->deleteReason;
            $customer->save();
            $customer->delete();

            $this->flashMessage = 'Customer deleted successfully.';
            $this->flashType = 'success';
        } else {
            $this->flashMessage = 'Customer not found.';
            $this->flashType = 'error';
        }

        $this->showFlash = true;
        $this->cancelDeleteCustomer();
        $this->loadCustomers();
    }

    public function loadSettings()
    {
        $this->shop_name = Setting::get('shop_name', 'My POS Shop');
        $this->shop_address = Setting::get('shop_address', '123 Main Street, City');
        $this->shop_phone = Setting::get('shop_phone', '+1 555-000-0000');
        $this->currency_symbol = Setting::get('currency_symbol', '$');
        $this->receipt_footer = Setting::get('receipt_footer', 'Thank you for shopping with us!');
        $this->taxRate = floatval(Setting::get('tax_rate', 0));
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
        $this->flashMessage = 'Settings saved successfully.';
        $this->flashType = 'success';
        $this->showFlash = true;
    }

    public function render()
    {
        return view('livewire.cashier-dashboard');
    }
}
