@extends('layouts.manager')

@section('title', 'Manage Suppliers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Suppliers List</h3>
                    <div class="card-tools">
                        <a href="{{ route('manager.suppliers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Supplier
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email ?? '-' }}</td>
                                        <td>{{ Str::limit($supplier->address, 30) ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('manager.suppliers.show', $supplier) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('manager.suppliers.edit', $supplier) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->name }}')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            
                                            <form id="delete-form-{{ $supplier->id }}" 
                                                  action="{{ route('manager.suppliers.destroy', $supplier) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No suppliers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete supplier "${name}"? This action cannot be undone.`)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}
</script>
@endpush
@endsection