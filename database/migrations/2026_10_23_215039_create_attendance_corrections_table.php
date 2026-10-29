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
        Schema::create('perbaikan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perbaikan_id')->constrained('perbaikan')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status_presensi', ['TM', 'HD']);
            $table->time('jam_datang_asli')->nullable();
            $table->time('jam_datang_ubah')->nullable();
            $table->time('jam_pulang_asli')->nullable();
            $table->time('jam_pulang_ubah')->nullable();
            $table->string('foto_datang')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan_detail');
    }
};
