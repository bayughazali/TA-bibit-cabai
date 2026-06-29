<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\OrderCancellation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderShippedNotification;
use App\Notifications\OrderStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        'order_status'   => 'required|in:pending,processing,shipped,delivered,cancelled',
        'payment_status' => 'required|in:pending,paid,failed',
        'tracking_number'=> 'nullable|string|max:100',
        'notes'          => 'nullable|string',
    ]);

    try {
        $transaksi = Transaksi::findOrFail($id);

        $oldStatus = $transaksi->order_status;

        $updateData = [
            'order_status'    => $request->order_status,
            'payment_status'  => $request->payment_status,
            'tracking_number' => $request->tracking_number,
            'notes'           => $request->notes,

            // Field customer & shipping TIDAK diupdate
            'customer_name'   => $transaksi->customer_name,
            'customer_phone'  => $transaksi->customer_phone,
            'customer_email'  => $transaksi->customer_email,
            'shipping_address'=> $transaksi->shipping_address,
            'city'            => $transaksi->city,
            'province'        => $transaksi->province,
            'postal_code'     => $transaksi->postal_code,
        ];

        if ($request->payment_status === 'paid' && $transaksi->payment_status !== 'paid') {
            $updateData['paid_at'] = now();
        }

        $transaksi->update($updateData);

        // Kirim notifikasi jika order_status berubah
        if ($transaksi->user_id && $request->order_status !== $oldStatus) {
            try {
                $transaksi->user->notify(new OrderStatusChanged($transaksi, $request->order_status));
            } catch (\Exception $notifError) {
                Log::warning('Gagal simpan notifikasi: ' . $notifError->getMessage());
            }
        }

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
    try {
        $transaksi = Transaksi::with('details')->findOrFail($id);

        // Handle order_status
        if ($request->has('order_status')) {
            $request->validate([
                'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);

           $newStatus = $request->order_status;
            $oldStatus = $transaksi->order_status;

            // Jika sudah cancelled, tidak bisa diubah
            if (in_array($oldStatus, ['cancelled', 'delivered'])) {
            return response()->json([
            'success' => false,
            'message' => 'Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah kembali.'
                ], 422);
            }

            $transaksi->update(['order_status' => $newStatus]);

            if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                try {
                    Mail::to($transaksi->customer_email)
                        ->send(new OrderShippedNotification($transaksi));
                } catch (\Exception $mailError) {
                    Log::warning('Gagal kirim email shipped: ' . $mailError->getMessage());
                }
            }

            if ($transaksi->user_id) {
                try {
                    $transaksi->user->notify(new OrderStatusChanged($transaksi, $newStatus));
                } catch (\Exception $notifError) {
                    Log::warning('Gagal simpan notifikasi: ' . $notifError->getMessage());
                }
            }
        }

        // Handle payment_status
        if ($request->has('payment_status')) {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed',
            ]);

            $updateData = ['payment_status' => $request->payment_status];
            if ($request->payment_status === 'paid' && $transaksi->payment_status !== 'paid') {
                $updateData['paid_at'] = now();
            }
            $transaksi->update($updateData);
        }

        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate!']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

// ← tambah di bagian use atas

/**
 * Daftar pengajuan pembatalan (Admin)
 */
public function cancellations()
{
    $cancellations = OrderCancellation::with(['transaksi', 'user'])
        ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('admin.cancellations', compact('cancellations'));
}

/**
 * Setujui pengajuan pembatalan
 */
public function approveCancellation(Request $request, $id)
{
    $cancellation = OrderCancellation::with('transaksi')->findOrFail($id);

    $cancellation->update([
        'status'      => 'approved',
        'admin_note'  => $request->admin_note ?? 'Pengajuan pembatalan disetujui oleh admin.',
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    // Kembalikan stok produk
    foreach ($cancellation->transaksi->details as $detail) {
        $product = Product::find($detail->product_id);
        if ($product) {
            $product->increment('stock', $detail->quantity);
        }
    }

    // Update status pesanan jadi cancelled
    $cancellation->transaksi->update(['order_status' => 'cancelled']);

    return back()->with('success', 'Pengajuan disetujui. Pesanan dibatalkan & stok dikembalikan.');
}

/**
 * Tolak pengajuan pembatalan
 */
public function rejectCancellation(Request $request, $id)
{
    $request->validate([
        'admin_note' => ['required', 'string', 'max:500'],
    ], [
        'admin_note.required' => 'Alasan penolakan wajib diisi.',
    ]);

    $cancellation = OrderCancellation::findOrFail($id);

    $cancellation->update([
        'status'      => 'rejected',
        'admin_note'  => $request->admin_note,
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
    ]);

    return back()->with('success', 'Pengajuan pembatalan berhasil ditolak.');
}

/**
 * Pembeli upload bukti pembayaran
 */
public function konfirmasiPembayaran(Request $request, $id)
{
    $request->validate([
        'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
        'payment_proof.image'    => 'File harus berupa gambar.',
        'payment_proof.mimes'    => 'Format gambar harus JPG atau PNG.',
        'payment_proof.max'      => 'Ukuran gambar maksimal 2MB.',
    ]);

    $transaksi = Transaksi::findOrFail($id);

    $path = $request->file('payment_proof')->store('payment_proofs', 'public');

    $transaksi->update([
        'payment_proof'  => $path,
        'payment_status' => 'waiting_confirmation',
    ]);

    // Kirim notif WA ke admin via Fonnte
    try {
        $adminPhone  = env('ADMIN_PHONE', '628131830561');
        $fonnteToken = env('FONNTE_TOKEN');

        $pesan = "🔔 *Konfirmasi Pembayaran Masuk!*\n\n"
               . "📋 Invoice: *{$transaksi->invoice_number}*\n"
               . "👤 Pembeli: {$transaksi->customer_name}\n"
               . "📞 Telepon: {$transaksi->customer_phone}\n"
               . "💳 Metode: " . strtoupper($transaksi->payment_method) . "\n"
               . "💰 Total: Rp " . number_format($transaksi->total_amount, 0, ',', '.') . "\n\n"
               . "Silakan approve di panel admin:\n"
               . url("/admin/transaksis/{$transaksi->id}");

        Http::withHeaders(['Authorization' => $fonnteToken])
            ->post('https://api.fonnte.com/send', [
                'target'  => $adminPhone,
                'message' => $pesan,
            ]);
    } catch (\Exception $e) {
        Log::warning('Gagal kirim notif WA admin: ' . $e->getMessage());
    }

    return back()->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.');
}

/**
 * Admin approve pembayaran → tandai lunas + notif WA ke pembeli
 */
public function approvePembayaran($id)
{
    $transaksi = Transaksi::findOrFail($id);

    $transaksi->update([
        'payment_status' => 'paid',
        'paid_at'        => now(),
        'confirmed_at'   => now(),
    ]);

    // Kirim notif WA ke pembeli via Fonnte
    try {
        $fonnteToken = env('FONNTE_TOKEN');

        $pesan = "✅ *Pembayaran Dikonfirmasi!*\n\n"
               . "Halo *{$transaksi->customer_name}*,\n"
               . "Pembayaran Anda untuk invoice *{$transaksi->invoice_number}* telah dikonfirmasi.\n\n"
               . "📦 Pesanan Anda sedang kami proses dan akan segera dikirim.\n"
               . "Terima kasih telah berbelanja di *Shop Bibit Cabai Bondowoso*! 🌶️";

        Http::withHeaders(['Authorization' => $fonnteToken])
            ->post('https://api.fonnte.com/send', [
                'target'  => $transaksi->customer_phone,
                'message' => $pesan,
            ]);
    } catch (\Exception $e) {
        Log::warning('Gagal kirim notif WA pembeli: ' . $e->getMessage());
    }

    return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan pembeli telah dinotifikasi!');
}


}