<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;

class ProductRecommendationController extends Controller
{
    public function index()
    {
        // Query produk terlaris dari SEMUA status transaksi
        $bestSellingProducts = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->join('products', 'transaksi_details.product_id', '=', 'products.id')
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

        // Hitung statistik
        $statistics = [
            'total_products_sold' => DB::table('transaksi_details')->sum('quantity'),
            'total_completed_orders' => Transaksi::where('order_status', 'delivered')->count(),
            'most_popular_product' => $bestSellingProducts->first()
        ];

        return view('products.best-selling', compact('bestSellingProducts', 'statistics'));
    }

    public function updateToDelivered(Request $request)
    {
        $transaksiIds = $request->input('ids', []);
        
        if (empty($transaksiIds)) {
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