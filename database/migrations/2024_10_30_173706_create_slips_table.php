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
        Schema::create('slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('in_gaji_pokok');
            $table->integer('in_upah_lembur');
            $table->integer('in_uang_makan');
            $table->integer('in_uang_transport');
            $table->integer('in_lain');
            $table->string('in_keterangan');
            $table->integer('out_telat');
            $table->integer('out_kerusakan_barang');
            $table->integer('out_kasbon');
            $table->integer('out_lain');
            $table->string('out_keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slips');
    }
};
