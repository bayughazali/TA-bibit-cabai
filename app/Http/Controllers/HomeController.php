<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products (best selling or marked as featured)
        $featuredProducts = Product::active()
            ->orderByDesc('sold') // Order by most sold
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts'));
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