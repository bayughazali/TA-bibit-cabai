@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Order Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Orders</a></li>
                    <li class="breadcrumb-item active">{{ $transaksi->invoice_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ route('admin.transaksis.edit', $transaksi->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.transaksis.print', $transaksi->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> Print Invoice
            </a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Invoice & Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    <div>
                        @php
                            $statusClass = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ][$transaksi->order_status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $statusClass }} badge-lg">
                            {{ ucfirst($transaksi->order_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Invoice Details</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Invoice Number:</strong></td>
                                    <td>{{ $transaksi->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        @php
                                            $paymentClass = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger'
                                            ][$transaksi->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $paymentClass }}">
                                            {{ ucfirst($transaksi->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Update Status</h6>
                            <form action="{{ route('admin.transaksis.update-status', $transaksi->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="order_status">Order Status</label>
                                    <select name="order_status" id="order_status" class="form-control">
                                        <option value="pending" {{ $transaksi->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $transaksi->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $transaksi->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $transaksi->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $transaksi->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Update Order Status
                                </button>
                            </form>

                            <form action="{{ route('admin.transaksis.update-payment-status', $transaksi->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="form-group">
                                    <label for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status" class="form-control">
                                        <option value="pending" {{ $transaksi->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $transaksi->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ $transaksi->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-save"></i> Update Payment Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong>
                                    </td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Amount:</th>
                                    <th>Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><i class="fas fa-user text-primary"></i></td>
                            <td><strong>Name:</strong></td>
                            <td>{{ $transaksi->customer_name }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-phone text-primary"></i></td>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $transaksi->customer_phone }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-envelope text-primary"></i></td>
                            <td><strong>Email:</strong></td>
                            <td>{{ $transaksi->customer_email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Shipping Address:</strong></p>
                    <address class="mb-3">
                        {{ $transaksi->shipping_address }}<br>
                        {{ $transaksi->city }}, {{ $transaksi->province }}<br>
                        {{ $transaksi->postal_code }}
                    </address>

                    @if($transaksi->shipping_method)
                    <p class="mb-1"><strong>Shipping Method:</strong></p>
                    <p>{{ $transaksi->shipping_method }}</p>
                    @endif

                    @if($transaksi->tracking_number)
                    <p class="mb-1"><strong>Tracking Number:</strong></p>
                    <p><code>{{ $transaksi->tracking_number }}</code></p>
                    @endif
                </div>
            </div>

            <!-- Additional Notes -->
            @if($transaksi->notes)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $transaksi->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
                <p><strong>Invoice: {{ $transaksi->invoice_number }}</strong></p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.transaksis.destroy', $transaksi->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    
    address {
        line-height: 1.6;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when status changes
    document.getElementById('order_status').addEventListener('change', function() {
        if(confirm('Update order status?')) {
            this.form.submit();
        }
    });
    
    document.getElementById('payment_status').addEventListener('change', function() {
        if(confirm('Update payment status?')) {
            this.form.submit();
        }
    });
</script>
@endpush
@endsection