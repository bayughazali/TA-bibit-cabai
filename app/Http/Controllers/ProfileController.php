<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Halaman profil pengguna
     */
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update profil pengguna
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z\s]+$/'],
            'phone'   => ['required', 'string', 'min:11', 'regex:/^(\+62|08)[0-9]+$/'],
            'address' => ['required', 'string', 'min:12'],
        ], [
            'name.required'    => 'Nama lengkap wajib diisi.',
            'name.min'         => 'Nama lengkap minimal 8 karakter.',
            'name.regex'       => 'Nama hanya boleh menggunakan huruf dan spasi.',
            'phone.required'   => 'Nomor telepon wajib diisi.',
            'phone.min'        => 'Nomor telepon minimal 11 karakter.',
            'phone.regex'      => 'Nomor telepon harus diawali +62 atau 08 dan hanya boleh berisi angka.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min'      => 'Alamat minimal 12 karakter.',
        ]);

        $user->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password pengguna
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])\S+$/'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password.regex'            => 'Password harus terdiri dari huruf dan angka, tanpa spasi.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang kamu masukkan tidak sesuai.'
            ])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Halaman daftar pesanan pengguna
     */
    public function orders(Request $request)
    {
        $user   = auth()->user();
        $status = $request->get('status', 'all');

        $query = Transaksi::with(['details.product', 'cancellation'])  // ← tambah cancellation
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('order_status', $status);
        }

        $orders = $query->paginate(10);

        $totalOrders    = Transaksi::where('user_id', $user->id)->count();
        $pendingOrders  = Transaksi::where('user_id', $user->id)->where('order_status', 'pending')->count();
        $processOrders  = Transaksi::where('user_id', $user->id)->where('order_status', 'processing')->count();
        $shippedOrders  = Transaksi::where('user_id', $user->id)->where('order_status', 'shipped')->count();
        $doneOrders     = Transaksi::where('user_id', $user->id)->where('order_status', 'delivered')->count();
        $cancelOrders   = Transaksi::where('user_id', $user->id)->where('order_status', 'cancelled')->count();

        return view('profile.orders', compact(
            'orders', 'status',
            'totalOrders', 'pendingOrders', 'processOrders',
            'shippedOrders', 'doneOrders', 'cancelOrders'
        ));
    }

    /**
     * Detail pesanan — dengan relasi cancellation
     */
    public function orderDetail($id)
    {
        $user  = auth()->user();
        $order = Transaksi::with(['details.product', 'cancellation'])  // ← tambah cancellation
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('profile.order-detail', compact('order'));
    }
// Tambahkan di ProfileController.php — setelah method orderDetail()

/**
 * Tampilkan form batalkan pesanan
 */
public function cancelForm($id)
{
    $user = auth()->user();
    
    $transaksi = Transaksi::with(['details.product', 'cancellation'])
        ->where('user_id', $user->id)
        ->findOrFail($id);

    if (!$transaksi->canBeCancelled()) {
        return redirect()->route('orders.detail', $id)
            ->with('error', 'Pesanan ini tidak dapat dibatalkan.');
    }

    $reasons = \App\Models\OrderCancellation::reasons();

    return view('profile.cancel', compact('transaksi', 'reasons'));
}

/**
 * Simpan pengajuan pembatalan
 */
public function cancelStore(Request $request, $id)
{
    $user = auth()->user();

    $transaksi = Transaksi::with('cancellation')
        ->where('user_id', $user->id)
        ->findOrFail($id);

    if (!$transaksi->canBeCancelled()) {
        return redirect()->route('orders.detail', $id)
            ->with('error', 'Pesanan ini tidak dapat dibatalkan.');
    }

    $request->validate([
        'reason'      => ['required', 'string'],
        'description' => ['nullable', 'string', 'max:500'],
    ], [
        'reason.required' => 'Pilih alasan pembatalan terlebih dahulu.',
    ]);

    // ── COD + pending → langsung cancel tanpa persetujuan admin ──
    if ($transaksi->isAutoCancel()) {
        $transaksi->update(['order_status' => 'cancelled']);

        // Simpan catatan pembatalan (opsional, untuk riwayat)
        \App\Models\OrderCancellation::create([
            'transaksi_id' => $transaksi->id,
            'user_id'      => $user->id,
            'reason'       => $request->reason,
            'description'  => $request->description,
            'status'       => 'approved', // langsung approved
            'admin_note'   => 'Dibatalkan otomatis (COD, belum diproses)',
            'reviewed_at'  => now(),
        ]);

        return redirect()->route('orders.my')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // ── Selain itu → kirim pengajuan ke admin ──
    \App\Models\OrderCancellation::create([
        'transaksi_id' => $transaksi->id,
        'user_id'      => $user->id,
        'reason'       => $request->reason,
        'description'  => $request->description,
        'status'       => 'pending',
    ]);

    return redirect()->route('orders.detail', $id)
        ->with('success', 'Pengajuan pembatalan berhasil dikirim. Tunggu konfirmasi admin.');
}
}