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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan');
            $table->year('tahun');
            $table->string('nomor_surat')->nullable();
            $table->string('nomor_surat_lengkap')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->enum('status', ['draf', 'final'])->default('draf');
            $table->string('pdf_file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
