<?php

namespace App\Notifications;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderStatusChanged extends Notification
{
    use Queueable;

    public Transaksi $transaksi;
    public string $newStatus;

    public function __construct(Transaksi $transaksi, string $newStatus)
    {
        $this->transaksi = $transaksi;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $messages = [
            'processing' => '🔄 Pesanan Anda sedang diproses',
            'shipped'    => '📦 Pesanan Anda sedang dalam pengiriman',
            'delivered'  => '✅ Pesanan Anda telah diterima',
            'cancelled'  => '❌ Pesanan Anda dibatalkan',
        ];

        return [
            'invoice'     => $this->transaksi->invoice_number,
            'status'      => $this->newStatus,
            'message'     => $messages[$this->newStatus] ?? 'Status pesanan diperbarui',
            'transaksi_id'=> $this->transaksi->id,
        ];
    }
}