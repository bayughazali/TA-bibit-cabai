<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['details.product', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by invoice or customer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksis = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => Transaksi::count(),
            'pending' => Transaksi::pending()->count(),
            'processing' => Transaksi::processing()->count(),
            'shipped' => Transaksi::shipped()->count(),
            'delivered' => Transaksi::delivered()->count(),
            'cancelled' => Transaksi::cancelled()->count(),
            'unpaid' => Transaksi::unpaid()->count(),
            'paid' => Transaksi::paid()->count(),
            'total_revenue' => Transaksi::paid()->sum('total_amount'),
        ];

        return view('admin.transaksis.index', compact('transaksis', 'stats'));
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['details.product', 'user'])->findOrFail($id);
        return view('admin.transaksis.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit($id)
    {
        $transaksi = Transaksi::with('details.product')->findOrFail($id);
        return view('admin.transaksis.edit', compact('transaksi'));
    }

    /**
     * Update the specified transaction in storage.
     * UPDATED: Menambahkan validasi dan update untuk customer info dan shipping
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            
            $updateData = [
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'order_status' => $request->order_status,
                'payment_status' => $request->payment_status,
                'tracking_number' => $request->tracking_number,
                'notes' => $request->notes
            ];

            // If payment status changed to paid, set paid_at
            if ($request->payment_status === 'paid' && $transaksi->payment_status !== 'paid') {
                $updateData['paid_at'] = now();
            }

            $transaksi->update($updateData);

            return redirect()
                ->route('admin.transaksis.show', $id)
                ->with('success', 'Transaksi berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update order status via AJAX or regular request.
     * UPDATED: Menerima field 'order_status' selain 'status'
     */
    public function updateStatus(Request $request, $id)
    {
        // Accept both 'status' and 'order_status' field names
        $statusField = $request->has('order_status') ? 'order_status' : 'status';
        
        $request->validate([
            $statusField => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            $newStatus = $request->input($statusField);
            
            // Use model method if exists, otherwise direct update
            if (method_exists($transaksi, 'updateOrderStatus')) {
                $transaksi->updateOrderStatus($newStatus);
            } else {
                $transaksi->update(['order_status' => $newStatus]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate!'
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Status berhasil diupdate!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            
            if ($request->payment_status === 'paid') {
                // Use model method if exists
                if (method_exists($transaksi, 'markAsPaid')) {
                    $transaksi->markAsPaid();
                } else {
                    $transaksi->update([
                        'payment_status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            } else {
                $transaksi->update([
                    'payment_status' => $request->payment_status
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran berhasil diupdate!'
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Status pembayaran berhasil diupdate!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update status pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Gagal update status pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the transaction.
     */
    public function cancel($id)
    {
        DB::beginTransaction();
        
        try {
            $transaksi = Transaksi::with('details')->findOrFail($id);

            // Check if can be cancelled (use model method if exists)
            if (method_exists($transaksi, 'canBeCancelled') && !$transaksi->canBeCancelled()) {
                throw new \Exception('Transaksi tidak dapat dibatalkan');
            }

            // Return stock
            foreach ($transaksi->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }

            // Update status
            $transaksi->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $transaksi = Transaksi::with('details')->findOrFail($id);

            // If order is not cancelled, return stock first
            if ($transaksi->order_status !== 'cancelled') {
                foreach ($transaksi->details as $detail) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $product->increment('stock', $detail->quantity);
                    }
                }
            }

            // Delete transaction (details will be deleted automatically due to cascade)
            $transaksi->delete();

            DB::commit();

            return redirect()
                ->route('admin.orders') // Redirect ke halaman orders
                ->with('success', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Export transactions to CSV.
     */
    public function export(Request $request)
    {
        $query = Transaksi::with('details.product')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksis = $query->get();

        $filename = 'transaksi_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // Header - UPDATED dengan info shipping
        fputcsv($output, [
            'Invoice',
            'Tanggal',
            'Customer',
            'Email',
            'Phone',
            'Shipping Address',
            'City',
            'Province',
            'Postal Code',
            'Total',
            'Payment Method',
            'Payment Status',
            'Order Status',
            'Tracking Number'
        ]);

        // Data - UPDATED dengan info shipping
        foreach ($transaksis as $transaksi) {
            fputcsv($output, [
                $transaksi->invoice_number,
                $transaksi->created_at->format('Y-m-d H:i:s'),
                $transaksi->customer_name,
                $transaksi->customer_email,
                $transaksi->customer_phone,
                $transaksi->shipping_address,
                $transaksi->city,
                $transaksi->province,
                $transaksi->postal_code,
                $transaksi->total_amount,
                $transaksi->payment_method_label ?? $transaksi->payment_method,
                $transaksi->payment_status,
                $transaksi->order_status,
                $transaksi->tracking_number ?? '-'
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * Print invoice.
     */
    public function printInvoice($id)
    {
        $transaksi = Transaksi::with(['details.product', 'user'])->findOrFail($id);
        return view('admin.transaksis.print', compact('transaksi'));
    }
}