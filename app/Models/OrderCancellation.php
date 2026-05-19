<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancellation extends Model
{
    protected $fillable = [
        'transaksi_id',
        'user_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public static function reasons(): array
    {
        return [
            'Ingin mengubah produk / varian'   => 'Ingin mengubah produk / varian',
            'Ingin mengubah alamat pengiriman' => 'Ingin mengubah alamat pengiriman',
            'Menemukan harga lebih murah'      => 'Menemukan harga lebih murah',
            'Tidak jadi membeli'               => 'Tidak jadi membeli',
            'Proses pengiriman terlalu lama'   => 'Proses pengiriman terlalu lama',
            'Lainnya'                          => 'Lainnya',
        ];
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool  { return $this->status === 'pending';  }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}