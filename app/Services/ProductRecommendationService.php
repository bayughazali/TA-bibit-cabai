<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class ProductRecommendationService
{
    /**
     * Mendapatkan produk terlaris berdasarkan frekuensi pembelian
     */
    public function getBestSellingProducts(int $limit = 6): Collection
    {
        return Cache::remember('best_selling_products', 3600, function () use ($limit) {
            return Product::select([
                'products.id',
                'products.name',
                'products.price',
                'products.image',
                'products.stock',
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'),
                DB::raw('COALESCE(COUNT(DISTINCT order_details.order_id), 0) as total_orders')
            ])
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('order_details.order_id', '=', 'orders.id')
                     ->where('orders.status', '=', 'completed');
            })
            ->where('products.is_active', true)
            ->where('products.stock', '>', 0)
            ->groupBy([
                'products.id', 
                'products.name', 
                'products.price', 
                'products.image', 
                'products.stock'
            ])
            ->orderBy('total_sold', 'desc')
            ->orderBy('total_orders', 'desc')
            ->limit($limit)
            ->get();
        });
    }

    /**
     * Mendapatkan statistik penjualan untuk dashboard
     */
    public function getSalesStatistics(): array
    {
        return Cache::remember('sales_statistics', 1800, function () {
            return [
                'total_products_sold' => DB::table('order_details')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->where('orders.status', 'completed')
                    ->sum('order_details.quantity'),
                
                'total_completed_orders' => DB::table('orders')
                    ->where('status', 'completed')
                    ->count(),
                
                'most_popular_product' => Product::select([
                        'products.name',
                        DB::raw('SUM(order_details.quantity) as total_sold')
                    ])
                    ->join('order_details', 'products.id', '=', 'order_details.product_id')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->where('orders.status', 'completed')
                    ->groupBy('products.id', 'products.name')
                    ->orderBy('total_sold', 'desc')
                    ->first()
            ];
        });
    }

    /**
     * Clear cache ketika ada transaksi baru
     */
    public function clearCache(): void
    {
        Cache::forget('best_selling_products');
        Cache::forget('sales_statistics');
    }
}