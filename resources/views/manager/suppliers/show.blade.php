@extends('layouts.manager')

@section('title', 'Supplier Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Supplier Information</h3>
                    <div class="card-tools">
                        <a href="{{ route('manager.suppliers.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name:</th>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $supplier->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $supplier->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $supplier->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $supplier->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('manager.suppliers.edit', $supplier) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Supplier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection