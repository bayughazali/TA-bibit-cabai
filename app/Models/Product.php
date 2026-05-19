<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category', 
        'price',
        'stock',
        'status',
        'description',
        'image',
        'label',
        'weight',
        'care_instructions',
        'season',
        'harvest_time',
        'sold'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
        'harvest_time' => 'integer',
        'sold' => 'integer'
    ];

    // Accessor untuk format harga - DIPERBAIKI
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // NEW: Accessor untuk URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Cek apakah file gambar ada di storage
            if (Storage::disk('public')->exists($this->image)) {
                return asset('storage/' . $this->image);
            }
        }
        
        // Return placeholder jika gambar tidak ada
        return 'https://via.placeholder.com/60x60?text=No+Image&color=6c757d&background=f8f9fa';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'aktif' 
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-danger">Non-aktif</span>';
    }

    // NEW: Accessor untuk status badge class (untuk styling CSS)
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'aktif' ? 'status-badge success' : 'status-badge danger';
    }

    // NEW: Accessor untuk label badge class
    public function getLabelBadgeClassAttribute()
    {
        if (!$this->label) {
            return 'label-badge bg-secondary';
        }

        switch (strtolower($this->label)) {
            case 'terlaris':
                return 'label-badge bg-danger';
            case 'tersedia':
                return 'label-badge bg-success';
            case 'habis':
                return 'label-badge bg-secondary';
            case 'baru':
                return 'label-badge bg-primary';
            default:
                return 'label-badge bg-secondary';
        }
    }

    // NEW: Method untuk mendapatkan label berdasarkan stok
   public function updateLabelBasedOnStock()
{
    if ($this->stock <= 0) {
        $this->label = 'habis';
    } else {
        $this->label = 'tersedia';
    }
    return $this;
}

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk produk dengan stok rendah - DIPERBAIKI
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }

    // NEW: Scope untuk search
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
    }

    // NEW: Scope untuk filter kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    // Relasi ke transaksi details untuk menghitung total terjual
public function transaksiDetails()
{
    return $this->hasMany(TransaksiDetail::class);
}
}