<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaksi->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4e73df;
        }
        
        .company-info h1 {
            color: #4e73df;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #666;
            font-size: 14px;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .detail-section h3 {
            font-size: 16px;
            color: #4e73df;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .detail-section p {
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .detail-section strong {
            display: inline-block;
            width: 120px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background: #4e73df;
            color: white;
        }
        
        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #4e73df;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        
        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        
        .total-row span:first-child {
            width: 150px;
            text-align: right;
            margin-right: 20px;
        }
        
        .total-row span:last-child {
            width: 150px;
            text-align: right;
        }
        
        .grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #4e73df;
            padding-top: 10px;
            border-top: 2px solid #4e73df;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #ffc107; color: white; }
        .status-processing { background: #17a2b8; color: white; }
        .status-shipped { background: #007bff; color: white; }
        .status-delivered { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .status-paid { background: #28a745; color: white; }
        .status-failed { background: #dc3545; color: white; }
        
        .invoice-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #4e73df;
        }
        
        .notes h4 {
            margin-bottom: 10px;
            color: #4e73df;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4e73df;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #224abe;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Print Invoice
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>🌱 Bibit Cabai</h1>
                <p>Jurang sapi, tapen, Bondowoso
                    Masuk paping sebelah toko</p>
                <p>Bondowoso, Tapen, Jawa timur</p>
                <p>Phone: 081331830561</p>
                <p>Email: bayualghozali86@gmail.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>{{ $transaksi->invoice_number }}</strong></p>
                <p>Date: {{ $transaksi->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Bill To:</h3>
                <p><strong>Name:</strong> {{ $transaksi->customer_name }}</p>
                <p><strong>Phone:</strong> {{ $transaksi->customer_phone }}</p>
                @if($transaksi->customer_email)
                <p><strong>Email:</strong> {{ $transaksi->customer_email }}</p>
                @endif
            </div>
            
            <div class="detail-section">
                <h3>Ship To:</h3>
                <p>{{ $transaksi->shipping_address }}</p>
                <p>{{ $transaksi->city }}, {{ $transaksi->province }}</p>
                <p>{{ $transaksi->postal_code }}</p>
            </div>
        </div>

        <!-- Status -->
        <div style="margin-bottom: 30px;">
            <p>
                <strong>Order Status:</strong> 
                <span class="status-badge status-{{ $transaksi->order_status }}">
                    {{ ucfirst($transaksi->order_status) }}
                </span>
            </p>
            <p>
                <strong>Payment Status:</strong> 
                <span class="status-badge status-{{ $transaksi->payment_status }}">
                    {{ ucfirst($transaksi->payment_status) }}
                </span>
            </p>
            @if($transaksi->tracking_number)
            <p style="margin-top: 10px;">
                <strong>Tracking Number:</strong> <code>{{ $transaksi->tracking_number }}</code>
            </p>
            @endif
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Product</th>
                    <th width="15%" class="text-right">Price</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="25%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $detail->product->name ?? 'Product Not Found' }}</strong>
                        @if($detail->product && $detail->product->description)
                        <br><small style="color: #666;">{{ \Illuminate\Support\Str::limit($detail->product->description, 80) }}</small>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->shipping_cost ?? 0 > 0)
            <div class="total-row">
                <span>Shipping Cost:</span>
                <span>Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaksi->total_amount + ($transaksi->shipping_cost ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Notes -->
        @if($transaksi->notes)
        <div class="notes">
            <h4>Notes:</h4>
            <p>{{ $transaksi->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any inquiries, please contact us at bayualghozali86@gmail.com
                
            </p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>