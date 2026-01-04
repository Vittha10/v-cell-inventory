<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('stock_opnames', function (Blueprint $table) {
        $table->id();
        $table->integer('product_id');
        $table->date('tanggal_so'); // Kolom ini sangat penting
        $table->integer('stok_sistem');
        $table->integer('qty_tambah')->default(0);
        $table->integer('qty_kurang')->default(0);
        $table->integer('stok_fisik');
        $table->integer('selisih');
        $table->string('alasan');
        $table->string('status');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
