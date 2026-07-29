<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun_ajaran')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status', 10)->default('nonaktif'); // aktif, nonaktif
            $table->timestamps();

            $table->index('status');
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('ruangan_id')->constrained('tahun_ajaran')->nullOnDelete();
            $table->index('tahun_ajaran_id');
        });

        Schema::table('serah_terima', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('nomor_berita_acara')->constrained('tahun_ajaran')->nullOnDelete();
            $table->index('tahun_ajaran_id');
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('kategori')->constrained('tahun_ajaran')->nullOnDelete();
            $table->string('sumber', 30)->default('manual')->after('tahun_ajaran_id'); // manual, otomatis_kebutuhan_ruangan
            $table->foreignId('kebutuhan_ruangan_id')->nullable()->after('sumber')->constrained('kebutuhan_ruangan')->nullOnDelete();
            $table->index('tahun_ajaran_id');
        });

        Schema::table('kebutuhan_ruangan', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('ruangan_id')->constrained('tahun_ajaran')->nullOnDelete();
            $table->integer('jumlah_dibutuhkan')->nullable()->after('nama_barang');
            $table->string('status', 20)->default('cukup')->after('jumlah_dibutuhkan'); // cukup, kurang, sudah_diajukan
            $table->foreignId('pengajuan_id')->nullable()->after('status')->constrained('pengajuan')->nullOnDelete();
            $table->index(['ruangan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropIndex(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::table('serah_terima', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropIndex(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['kebutuhan_ruangan_id']);
            $table->dropIndex(['tahun_ajaran_id']);
            $table->dropColumn(['tahun_ajaran_id', 'sumber', 'kebutuhan_ruangan_id']);
        });

        Schema::table('kebutuhan_ruangan', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['pengajuan_id']);
            $table->dropIndex(['ruangan_id', 'status']);
            $table->dropColumn(['tahun_ajaran_id', 'jumlah_dibutuhkan', 'status', 'pengajuan_id']);
        });

        Schema::dropIfExists('tahun_ajaran');
    }
};
