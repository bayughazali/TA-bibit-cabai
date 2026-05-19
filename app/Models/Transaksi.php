<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'province',
        'city',
        'postal_code',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'subtotal'     => 'decimal:2',
        'shipping_cost'=> 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function cancellation()
    {
        return $this->hasOne(OrderCancellation::class);
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // ── Methods ──────────────────────────────────────────────────

    /**
     * Cek apakah pesanan bisa dibatalkan.
     * Syarat: status pending/processing DAN belum ada pengajuan cancel.
     */
   public function canBeCancelled(): bool
{
    $this->loadMissing('cancellation');

    // Sudah shipped/delivered/cancelled → tidak bisa dibatalkan
    if (in_array($this->order_status, ['shipped', 'delivered', 'cancelled'])) {
        return false;
    }

    // Sudah ada pengajuan cancel → tidak bisa duplikat
    if ($this->cancellation !== null) {
        return false;
    }

    return true; // ← ini yang hilang
}
    public function isAutoCancel(): bool
    {
        return $this->payment_method === 'cod'
            && $this->order_status === 'pending';
    }

    public function hasPendingCancellation(): bool
    {
        $this->loadMissing('cancellation');

        return $this->cancellation !== null
            && $this->cancellation->status === 'pending';
    }

    public function markAsPaid(): bool
    {
        return $this->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);
    }

    public function updateOrderStatus(string $status): bool
    {
        return $this->update(['order_status' => $status]);
    }

    // ── Accessors ────────────────────────────────────────────────
    public function getPaymentMethodLabelAttribute(): string
    {
        return $this->payment_method === 'transfer' ? 'Transfer Bank' : 'COD';
    }
}