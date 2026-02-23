<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Bibit Cabai Merah Super',
                'description' => 'Premium Quality - Bibit cabai merah unggul dengan hasil panen melimpah. Tahan terhadap hama dan penyakit, cocok untuk berbagai cuaca.',
                'category' => 'Sayuran',
                'price' => 15000,
                'stock' => 100,
                'sold' => 250,
                'status' => 'aktif',
                'label' => 'terlaris',
                'image' => null // You can add image path here
            ],
            [
                'name' => 'Bibit Cabai Hijau Jumbo',
                'description' => 'Varietas cabai hijau dengan ukuran besar dan rasa pedas yang pas. Ideal untuk masakan rumahan dan komersial.',
                'category' => 'Sayuran',
                'price' => 12000,
                'stock' => 85,
                'sold' => 180,
                'status' => 'aktif',
                'label' => 'terlaris',
                'image' => null
            ],
            [
                'name' => 'Bibit Cabai Rawit Super Pedas',
                'description' => 'Cabai rawit dengan tingkat kepedasan tinggi. Cocok untuk penggemar makanan pedas dan industri bumbu.',
                'category' => 'Sayuran',
                'price' => 8000,
                'stock' => 150,
                'sold' => 320,
                'status' => 'aktif',
                'label' => 'terlaris',
                'image' => null
            ],
            [
                'name' => 'Bibit Cabai Keriting Premium',
                'description' => 'Cabai keriting dengan tekstur unik dan rasa yang khas. Sangat diminati pasar lokal dan ekspor.',
                'category' => 'Sayuran',
                'price' => 18000,
                'stock' => 75,
                'sold' => 120,
                'status' => 'aktif',
                'label' => 'tersedia',
                'image' => null
            ],
            [
                'name' => 'Bibit Cabai Hias Ornamental',
                'description' => 'Cabai hias dengan warna-warni menarik. Cocok untuk dekorasi taman dan dapat dikonsumsi.',
                'category' => 'Hias',
                'price' => 25000,
                'stock' => 50,
                'sold' => 45,
                'status' => 'aktif',
                'label' => 'tersedia',
                'image' => null
            ],
            [
                'name' => 'Bibit Cabai Paprika Mini',
                'description' => 'Paprika mini dengan rasa manis dan warna cerah. Kaya vitamin dan cocok untuk salad.',
                'category' => 'Sayuran',
                'price' => 22000,
                'stock' => 60,
                'sold' => 90,
                'status' => 'aktif',
                'label' => 'tersedia',
                'image' => null
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}