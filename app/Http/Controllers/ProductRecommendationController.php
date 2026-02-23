<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductRecommendationController extends Controller
{
    /**
     * VERSI UNTUK DEBUGGING
     * Menampilkan produk terlaris dengan informasi lengkap untuk debugging
     */
    public function index()
    {
        // Log untuk debugging
        Log::info('🔍 Mengakses halaman produk terlaris');

        // Cek total transaksi
        $totalTransaksi = Transaksi::count();
        $totalDelivered = Transaksi::where('order_status', 'delivered')->count();
        $totalDetails = TransaksiDetail::count();

        Log::info("📊 Total Transaksi: {$totalTransaksi}");
        Log::info("✅ Delivered: {$totalDelivered}");
        Log::info("📦 Detail: {$totalDetails}");

        // Query produk terlaris HANYA dari transaksi delivered
        $bestSellingProducts = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->join('products', 'transaksi_details.product_id', '=', 'products.id')
            ->where('transaksis.order_status', 'delivered')
            ->where('products.status', 'aktif')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.stock',
                DB::raw('SUM(transaksi_details.quantity) as total_sold'),
                DB::raw('COUNT(DISTINCT transaksis.id) as total_orders')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.stock'
            )
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        Log::info("🏆 Produk terlaris ditemukan: " . $bestSellingProducts->count());

        // FALLBACK: Jika tidak ada produk delivered, tampilkan dari semua status
        if ($bestSellingProducts->isEmpty()) {
            Log::warning('⚠️ Tidak ada produk dari transaksi delivered, menggunakan semua status');
            
            $bestSellingProducts = DB::table('transaksi_details')
                ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
                ->join('products', 'transaksi_details.product_id', '=', 'products.id')
                // TANPA FILTER STATUS - untuk testing
                ->where('products.status', 'aktif')
                ->select(
                    'products.id',
                    'products.name',
                    'products.image',
                    'products.price',
                    'products.stock',
                    DB::raw('SUM(transaksi_details.quantity) as total_sold'),
                    DB::raw('COUNT(DISTINCT transaksis.id) as total_orders')
                )
                ->groupBy(
                    'products.id',
                    'products.name',
                    'products.image',
                    'products.price',
                    'products.stock'
                )
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();

            Log::info("🔄 Produk dari semua status: " . $bestSellingProducts->count());
        }

        // Hitung statistik
        $statistics = [
            'total_products_sold' => DB::table('transaksi_details')
                ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
                ->where('transaksis.order_status', 'delivered')
                ->sum('transaksi_details.quantity'),
                
            'total_completed_orders' => Transaksi::where('order_status', 'delivered')->count(),
            
            'most_popular_product' => $bestSellingProducts->first()
        ];

        // Debug info
        $debugInfo = [
            'total_transaksi' => $totalTransaksi,
            'total_delivered' => $totalDelivered,
            'total_details' => $totalDetails,
            'products_found' => $bestSellingProducts->count()
        ];

        return view('products.best-selling', compact('bestSellingProducts', 'statistics', 'debugInfo'));
    }

    /**
     * Method untuk update status transaksi ke delivered (untuk testing)
     */
    public function updateToDelivered(Request $request)
    {
        $transaksiIds = $request->input('ids', []);
        
        if (empty($transaksiIds)) {
            // Update semua transaksi yang ada detail
            $transaksiIds = TransaksiDetail::distinct()
                ->pluck('transaksi_id')
                ->toArray();
        }

        $updated = Transaksi::whereIn('id', $transaksiIds)
            ->update(['order_status' => 'delivered']);

        return response()->json([
            'success' => true,
            'message' => "{$updated} transaksi berhasil diupdate ke delivered",
            'updated' => $updated
        ]);
    }

    /**
     * Method untuk cek status sistem
     */
    public function checkStatus()
    {
        $status = [
            'total_transaksi' => Transaksi::count(),
            'total_delivered' => Transaksi::where('order_status', 'delivered')->count(),
            'total_pending' => Transaksi::where('order_status', 'pending')->count(),
            'total_processing' => Transaksi::where('order_status', 'processing')->count(),
            'total_details' => TransaksiDetail::count(),
            'transaksi_dengan_detail' => TransaksiDetail::distinct()->count('transaksi_id'),
            'status_breakdown' => Transaksi::select('order_status', DB::raw('count(*) as total'))
                ->groupBy('order_status')
                ->get(),
        ];

        return response()->json($status);
    }
}