<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminOrderNotification;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        try {
            $productId = $request->get('product_id');
            $quantity = $request->get('quantity', 1);
            
            if (!$productId) {
                return redirect()->route('home')->with('error', 'Produk tidak ditemukan');
            }
            
            $product = Product::find($productId);
            
            if (!$product) {
                return redirect()->route('home')->with('error', 'Produk tidak ditemukan');
            }
            
            if ($product->stock < $quantity) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
            }
            
            $subtotal = $product->price * $quantity;
            $ongkir = 15000;
            $total = $subtotal + $ongkir;
            
            // Panggil view checkout.index (form checkout)
            return view('checkout.index', compact('product', 'quantity', 'subtotal', 'ongkir', 'total'));
            
        } catch (\Exception $e) {
            Log::error('Checkout Index Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function process(Request $request)
{
    $request->validate([
        'product_id'     => 'required|exists:products,id',
        'quantity'       => 'required|integer|min:1',
        'name'           => 'required|string|min:8|max:255',
        'phone'          => ['required', 'string', 'min:11', 'regex:/^\+62[0-9]{8,}$/'],
        'email'          => 'required|email|min:8|max:255',
        'address'        => ['required', 'string', 'min:20',
                             'regex:/(?=.*[Jj]l\.?|.*[Jj]alan)(?=.*[Nn]o\.?)(?=.*[Rr][Tt])(?=.*[Rr][Ww])/'],
        'city'           => 'required|string|max:100',
        'postal_code'    => 'required|string|max:10',
        'payment_method' => 'required|in:qris,bri,dana,seabank,shopee,cod',
        'shipping_cost'  => 'required|numeric|min:0',
        'notes'          => 'nullable|string|max:1000',
    ], [
        'name.required'          => 'Nama lengkap wajib diisi.',
        'name.min'               => 'Nama lengkap minimal 8 karakter.',
        'phone.required'         => 'Nomor telepon wajib diisi.',
        'phone.min'              => 'Nomor telepon minimal 11 karakter.',
        'phone.regex'            => 'Nomor telepon harus diawali +62 dan hanya berisi angka. Contoh: +6281234567890',
        'email.required'         => 'Email wajib diisi.',
        'email.email'            => 'Format email tidak valid.',
        'email.min'              => 'Email minimal 8 karakter.',
        'address.required'       => 'Alamat lengkap wajib diisi.',
        'address.min'            => 'Alamat terlalu pendek, harap isi lengkap.',
        'address.regex'          => 'Alamat harus lengkap: Nama Jalan, No. Rumah, RT/RW. Contoh: Jl. Merdeka No. 12, RT 02/RW 05, Desa Kademangan',
        'city.required'          => 'Kecamatan wajib dipilih.',
        'postal_code.required'   => 'Kode pos wajib diisi.',
        'payment_method.required'=> 'Metode pembayaran wajib dipilih.',
        'shipping_cost.required' => 'Ongkos kirim belum dihitung, pilih kecamatan terlebih dahulu.',
    ]);

    DB::beginTransaction();

    try {
        $product = Product::lockForUpdate()->find($request->product_id);

        if (!$product) {
            throw new \Exception('Produk tidak ditemukan');
        }

        if ($product->stock < $request->quantity) {
            throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        $ongkirData = [
            'Bondowoso'              => 20000,
            'Grujugan'               => 30000,
            'Jambesari Darus Sholah' => 250000,
            'Klabang'                => 10000,
            'Tenggarang'             => 12000,
            'Binakal'                => 12000,
            'Prajekan'               => 13000,
            'Botolinggo'             => 13000,
            'Maesan'                 => 50000,
            'Tamanan'                => 40000,
            'Wonosari'               => 10000,
            'Pujer'                  => 15000,
            'Tlogosari'              => 250000,
            'Sukosari'               => 18000,
            'Sumberwringin'          => 20000,
            'Tegalampel'             => 30000,
            'Sempol'                 => 80000,
            'Pakem'                  => 50000,
            'Curahdami'              => 40000,
            'Ijen'                   => 100000,
            'Tapen'                  => 10000,
            'Wringin'                => 300000,
            'Taman Krocok'           => 28000,
        ];

        $subtotal   = $product->price * $request->quantity;
        $baseOngkir = $ongkirData[$request->city] ?? 15000;
        $ongkir     = $baseOngkir;

        $diskon = 0;
        if ($request->quantity >= 1000) {
            $ongkir = 0;
            $diskon = $subtotal * 0.15;
        } elseif ($request->quantity >= 800) {
            $ongkir = 0;
        }

        $total = ($subtotal - $diskon) + $ongkir;

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $transaksi = new Transaksi();
        $transaksi->invoice_number  = $invoiceNumber;
        $transaksi->user_id         = Auth::id();
        $transaksi->customer_name   = $request->name;
        $transaksi->customer_phone  = $request->phone;
        $transaksi->customer_email  = $request->email;
        $transaksi->shipping_address= $request->address;
        $transaksi->province        = $request->province;
        $transaksi->city            = $request->city;
        $transaksi->postal_code     = $request->postal_code;
        $transaksi->subtotal        = $subtotal;
        $transaksi->discount        = $diskon;
        $transaksi->shipping_cost   = $ongkir;
        $transaksi->total_amount    = $total;
        $transaksi->payment_method  = $request->payment_method;
        $transaksi->payment_status  = 'pending';
        $transaksi->order_status    = 'pending';
        $transaksi->notes           = $request->notes;
        $transaksi->save();

        $detail = new TransaksiDetail();
        $detail->transaksi_id  = $transaksi->id;
        $detail->product_id    = $product->id;
        $detail->product_name  = $product->name;
        $detail->quantity      = $request->quantity;
        $detail->price         = $product->price;
        $detail->subtotal      = $subtotal;
        $detail->save();

        $product->stock = $product->stock - $request->quantity;
        $product->save();

        DB::commit();

        // Kirim notifikasi email ke admin
        try {
            Mail::to(config('mail.admin_email', 'bcom0508@gmail.com'))
                ->send(new AdminOrderNotification($transaksi->load('details')));
        } catch (\Exception $mailError) {
            Log::warning('Gagal kirim notifikasi email: ' . $mailError->getMessage());
        }

        return redirect()->route('checkout.success', ['id' => $transaksi->id])
            ->with('success', 'Pesanan berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Checkout Process Error', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine()
        ]);

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}
    
    public function success($id)
    {
        try {
            $transaksi = Transaksi::with(['details.product'])->find($id);
            
            if (!$transaksi) {
                return redirect()->route('home')
                    ->with('error', 'Transaksi tidak ditemukan');
            }
            
            // Panggil view checkout.success (halaman sukses)
            return view('checkout.success', compact('transaksi'));
            
        } catch (\Exception $e) {
            Log::error('Success Page Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}