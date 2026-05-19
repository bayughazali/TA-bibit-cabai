<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index()
{
    $featuredProducts = DB::table('transaksi_details')
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
        ->take(2)
        ->get();

    // Ambil semua produk aktif
    $allProducts = Product::active()->orderByDesc('created_at')->get();

    return view('home', compact('featuredProducts', 'allProducts'));
}

    public function bestSellingProducts()
    {
        $products = Product::active()
            ->where('sold', '>', 0)
            ->orderByDesc('sold')
            ->paginate(12);

        return view('products.best-selling', compact('products'));
    }

    public function allProducts()
    {
        $products = Product::active()
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('products.all', compact('products'));
    }
}