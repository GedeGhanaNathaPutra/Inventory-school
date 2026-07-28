<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('prodi_id')->references('id')->on('prodi')->nullOnDelete();
            $table->index('role');
        });

        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan');
            $table->foreignId('prodi_id')->nullable()->constrained('prodi')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->date('tanggal_pembukuan');
            $table->string('nama_barang');
            $table->text('keterangan_nomor_ukuran')->nullable();
            $table->string('merek_type')->nullable();
            $table->integer('kuantitas');
            $table->string('nama_satuan');
            $table->string('kategori', 10); // bos, komite
            $table->string('jenis_barang', 20); // inventaris, non_inventaris
            $table->string('kelengkapan_dokumen')->nullable();
            $table->string('kondisi', 20)->default('baik');
            $table->decimal('harga', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->nullOnDelete();
            $table->string('status', 20)->default('aktif'); // aktif, dihapuskan
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();

            $table->index(['kategori', 'jenis_barang', 'kondisi', 'ruangan_id']);
        });

        Schema::create('kebutuhan_ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->text('keterangan')->nullable();
            $table->text('kebutuhan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->date('tanggal');
            $table->timestamps();

            $table->index('ruangan_id');
        });

        Schema::create('kondisi_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->string('kondisi', 20);
            $table->text('keterangan')->nullable();
            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();
            $table->foreignId('dilaporkan_oleh')->constrained('users');
            $table->date('tanggal_lapor');
            $table->timestamps();
        });

        Schema::create('serah_terima', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_berita_acara')->unique();
            $table->foreignId('dari_user_id')->constrained('users');
            $table->foreignId('ke_user_id')->constrained('users');
            $table->date('tanggal_serah_terima');
            $table->string('status', 20)->default('draft'); // draft, diproses, selesai
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
        });

        Schema::create('serah_terima_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serah_terima_id')->constrained('serah_terima')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('jumlah');
            $table->string('kondisi_saat_serah_terima', 20);
        });

        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->string('kategori', 10); // bos, komite
            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->string('status', 30)->default('diajukan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('pengajuan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('estimasi_harga', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('barang_id')->nullable()->constrained('barang');
        });

        Schema::create('pengajuan_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('status');
            $table->foreignId('updated_by')->constrained('users');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('stok_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->string('jenis', 10); // masuk, keluar
            $table->integer('jumlah');
            $table->string('referensi_tipe')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_mutasi');
        Schema::dropIfExists('pengajuan_log');
        Schema::dropIfExists('pengajuan_item');
        Schema::dropIfExists('pengajuan');
        Schema::dropIfExists('serah_terima_item');
        Schema::dropIfExists('serah_terima');
        Schema::dropIfExists('kondisi_history');
        Schema::dropIfExists('kebutuhan_ruangan');
        Schema::dropIfExists('barang');

        Schema::table('users', fn (Blueprint $t) => $t->dropForeign(['prodi_id']));

        Schema::dropIfExists('ruangan');
        Schema::dropIfExists('prodi');
    }
};
