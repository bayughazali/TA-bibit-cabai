<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan daftar produk (Admin) - UPDATED dengan search dan filter
    public function index(Request $request)
    {
        $query = Product::orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(10);
        
        // Append query parameters to pagination links
        $products->appends($request->query());

        return view('admin.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        return view('admin.products.create');
    }

    // Simpan produk baru - UPDATED dengan auto label
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:4048'
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'category.required' => 'Kategori wajib dipilih',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'stock.required' => 'Stok wajib diisi',
            'stock.integer' => 'Stok harus berupa angka bulat',
            'status.required' => 'Status wajib dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.max' => 'Ukuran gambar maksimal 4MB',
            'image.mimes' => 'Format gambar harus JPG, PNG, JPEG, atau GIF'
        ]);

        $data = $request->only([
            'name', 
            'category', 
            'price', 
            'stock', 
            'status', 
            'description'
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $gambar = $request->file('image');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $path = $gambar->storeAs('products', $filename, 'public');
            $data['image'] = $path;
        }

        // Set default values
        $data['sold'] = 0;

        try {
            $product = Product::create($data);
            
            // Auto set label based on stock
           $product->updateLabelBasedOnStock();
            $product->save();
            $this->syncLabelAfterSave($product);

            return redirect()->route('admin.products.index')
                           ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => $e->getMessage()])
                           ->withInput();
        }
    }

    // Tampilkan detail produk
    // public function show($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return view('admin.products.show', compact('product'));
    // }

    // Form edit produk
public function edit(Product $product)
{
    return view('admin.products.edit', compact('product'));
}

// Ganti method update yang lama dengan yang ini:
public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:100',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:aktif,nonaktif',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
    ], [
        'name.required' => 'Nama produk wajib diisi',
        'category.required' => 'Kategori wajib dipilih',
        'price.required' => 'Harga wajib diisi',
        'price.numeric' => 'Harga harus berupa angka',
        'stock.required' => 'Stok wajib diisi',
        'stock.integer' => 'Stok harus berupa angka bulat',
        'status.required' => 'Status wajib dipilih',
        'image.image' => 'File harus berupa gambar',
        'image.max' => 'Ukuran gambar maksimal 2MB',
        'image.mimes' => 'Format gambar harus JPG, PNG, JPEG, atau GIF'
    ]);

    $data = $request->only([
        'name', 
        'category', 
        'price', 
        'stock', 
        'status', 
        'description'
    ]);

    // Upload gambar baru jika ada
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('products', $filename, 'public');
        $data['image'] = $path;
    }

    try {
        $product->update($data);
        
        // Auto update label based on new stock
       $product->updateLabelBasedOnStock();
        $product->save();
        $this->syncLabelAfterSave($product);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Produk berhasil diupdate!');
    } catch (\Exception $e) {
        return redirect()->back()
                       ->withErrors(['error' => 'Terjadi kesalahan saat mengupdate produk'])
                       ->withInput();
    }
}

// Juga ubah method show dan destroy untuk konsistensi:
public function show(Product $product)
{
    return view('admin.products.show', compact('product'));
}

public function destroy(Product $product)
{
    // Hapus gambar dari storage sebelum menghapus record
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }
    
    $product->delete();

    return redirect()->route('admin.products.index')
                    ->with('success', 'Produk berhasil dihapus');
}
// Tampilkan detail produk untuk user (public)
public function publicShow($id)
{
    $product = Product::where('status', 'aktif')->findOrFail($id);
    return view('products.show', compact('product'));
}
    // NEW: Method untuk debugging gambar
    public function debugImage($id)
    {
        $product = Product::findOrFail($id);
        
        $debug = [
            'product_id' => $product->id,
            'image_field' => $product->image,
            'image_url' => $product->image_url,
            'storage_path' => $product->image ? storage_path('app/public/' . $product->image) : null,
            'file_exists' => $product->image ? Storage::disk('public')->exists($product->image) : false,
            'public_path' => $product->image ? public_path('storage/' . $product->image) : null,
        ];

        return response()->json($debug);
    }
/**
 * Cek apakah produk ini termasuk top 10 terlaris, jika iya set label terlaris
 */
private function syncLabelAfterSave(Product $product)
{
    if ($product->stock <= 0) {
        return;
    }

    $bestSellingIds = \Illuminate\Support\Facades\DB::table('transaksi_details')
        ->select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))// ← ini rumus (1)
        ->groupBy('product_id')
        ->having('total_sold', '>=', 100)
        ->orderByDesc('total_sold') // ← ini rumus (2): si > sj
        ->take(4)                  
        ->pluck('product_id')
        ->toArray();

    if (in_array($product->id, $bestSellingIds)) {
        $product->label = 'terlaris';
        $product->save();
    } else {
        $product->label = 'tersedia';
        $product->save();
    }
}
}