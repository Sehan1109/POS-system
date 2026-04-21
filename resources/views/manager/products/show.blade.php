@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Details: {{ $product->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('manager.products.index') }}" class="btn btn-default btn-sm">Back</a>
                        <a href="{{ route('manager.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Product Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Barcode</th>
                                    <td>{{ $product->barcode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                </tr>
                                <tr>
                                    <th>Cost Price</th>
                                    <td>${{ number_format($product->cost_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Selling Price</th>
                                    <td>${{ number_format($product->selling_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Current Stock</th>
                                    <td>
                                        @if($product->isOutOfStock())
                                            <span class="badge badge-danger">Out of Stock (0)</span>
                                        @elseif($product->isLowStock())
                                            <span class="badge badge-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                        @else
                                            <span class="badge badge-success">In Stock ({{ $product->stock_quantity }})</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Adjustment Form -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Manual Stock Adjustment</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('manager.products.adjust-stock', $product) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Adjustment Type</label>
                                            <select name="adjustment_type" class="form-control" required>
                                                <option value="add">Add Stock (+)</option>
                                                <option value="subtract">Remove Stock (-)</option>
                                                <option value="set">Set Exact Stock (=)</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" class="form-control" min="0" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Reason (Optional)</label>
                                            <textarea name="reason" class="form-control" rows="2" placeholder="Why are you adjusting stock?"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-warning">Adjust Stock</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sales -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Recent Sales of this Product</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice #</th>
                                            <th>Customer</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSales as $item)
                                        <tr>
                                            <td>{{ $item->sale->created_at->format('Y-m-d H:i') }}</td>
                                            <td>{{ $item->sale->invoice_number }}</td>
                                            <td>{{ $item->sale->customer->name ?? 'Walk-in Customer' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>${{ number_format($item->sub_total, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No sales recorded for this product yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Movement History -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Stock Movement History</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Action</th>
                                            <th>Description</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stockMovements as $movement)
                                        <tr>
                                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $movement->action == 'stock_increase' ? 'success' : 'danger' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $movement->action)) }}
                                                </span>
                                            </td>
                                            <td>{{ $movement->description }}</td>
                                            <td>{{ $movement->user->name ?? 'System' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No stock movements recorded.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection