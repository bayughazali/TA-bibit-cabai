<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
       $validated = $request->validate([
    'product_id' => 'required|exists:products,id',
    'quantity' => 'required|integer|min:1',
    'name' => 'required|string|max:255',
    'phone' => 'required|string|max:20',
    'email' => 'required|email|max:255',
    'address' => 'required|string',
    'city' => 'required|string|max:100',
    'postal_code' => 'required|string|max:10',
    'payment_method' => 'required|in:qris,bri,dana,seabank,shopee,cod', // TAMBAHKAN 'qris'
    'shipping_cost' => 'required|numeric|min:0',
    'notes' => 'nullable|string|max:1000'
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
            
            $subtotal = $product->price * $request->quantity;
            $ongkir = 15000;
            $total = $subtotal + $ongkir;
            
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            $transaksi = new Transaksi();
            $transaksi->invoice_number = $invoiceNumber;
            $transaksi->user_id = Auth::id();
            $transaksi->customer_name = $request->name;
            $transaksi->customer_phone = $request->phone;
            $transaksi->customer_email = $request->email;
            $transaksi->shipping_address = $request->address;
            $transaksi->province = $request->province;
            $transaksi->city = $request->city;
            $transaksi->postal_code = $request->postal_code;
            $transaksi->subtotal = $subtotal;
            $transaksi->shipping_cost = $ongkir;
            $transaksi->total_amount = $total;
            $transaksi->payment_method = $request->payment_method;
            $transaksi->payment_status = 'pending';
            $transaksi->order_status = 'pending';
            $transaksi->notes = $request->notes;
            $transaksi->save();
            
            $detail = new TransaksiDetail();
            $detail->transaksi_id = $transaksi->id;
            $detail->product_id = $product->id;
            $detail->product_name = $product->name;
            $detail->quantity = $request->quantity;
            $detail->price = $product->price;
            $detail->subtotal = $subtotal;
            $detail->save();
            
            $product->stock = $product->stock - $request->quantity;
            $product->save();
            
            DB::commit();
            
            return redirect()->route('checkout.success', ['id' => $transaksi->id])
                ->with('success', 'Pesanan berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout Process Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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