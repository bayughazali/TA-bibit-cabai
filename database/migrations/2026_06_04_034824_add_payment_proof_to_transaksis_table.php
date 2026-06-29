<?php
// database/migrations/xxxx_add_payment_proof_to_transaksis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        if (!Schema::hasColumn('transaksis', 'payment_proof')) {
            $table->string('payment_proof')->nullable()->after('payment_method');
        }
        if (!Schema::hasColumn('transaksis', 'confirmed_at')) {
            $table->timestamp('confirmed_at')->nullable()->after('paid_at');
        }
        // Tambah status waiting_confirmation
        // (tidak perlu ubah kolom, cukup allow value baru di payment_status)
    });
}

public function down(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn(['payment_proof', 'confirmed_at']);
    });
}
};