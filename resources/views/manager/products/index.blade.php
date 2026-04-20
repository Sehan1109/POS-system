@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('manager.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('manager.products.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name or barcode..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="stock_status" class="form-control">
                                    <option value="">All Stock Status</option>
                                    <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock (>10)</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock (1-10)</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Barcode</th>
                                    <th>Category</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td>{{ $product->barcode ?? '-' }}</td>
                                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                    <td class="text-muted">${{ number_format($product->cost_price, 2) }}</td>
                                    <td class="text-success font-weight-bold">${{ number_format($product->selling_price, 2) }}</td>
                                    <td class="text-center">
                                        @php
                                            $stock = $product->stock_quantity;
                                        @endphp
                                        
                                        @if($stock <= 0)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Out of Stock (0)
                                            </span>
                                        @elseif($stock <= 5)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Critical ({{ $stock }})
                                            </span>
                                        @elseif($stock <= 10)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-circle"></i> Low Stock ({{ $stock }})
                                            </span>
                                        @elseif($stock <= 20)
                                            <span class="badge bg-info">
                                                <i class="fas fa-check-circle"></i> In Stock ({{ $stock }})
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Well Stocked ({{ $stock }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $product->status ?? 'active';
                                        @endphp
                                        
                                        @if($status == 'active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-play-circle"></i> Active
                                            </span>
                                        @elseif($status == 'inactive')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause-circle"></i> Inactive
                                            </span>
                                        @elseif($status == 'discontinued')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-stop-circle"></i> Discontinued
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('manager.products.show', $product) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('manager.products.edit', $product) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('manager.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        No products found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection