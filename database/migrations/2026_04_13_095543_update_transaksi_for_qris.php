<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->enum('status', [
                'masuk',
                'menunggu_pembayaran',
                'keluar'
            ])->default('masuk')->change();

            $table->enum('metode_bayar', ['cash', 'qris'])
                ->nullable()
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->enum('status', ['masuk', 'keluar'])->change();

            $table->dropColumn('metode_bayar');
        });
    }
};
