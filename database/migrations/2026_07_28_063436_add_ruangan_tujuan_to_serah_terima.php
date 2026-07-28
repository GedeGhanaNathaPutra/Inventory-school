<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terima', function (Blueprint $table) {
            $table->foreignId('ruangan_tujuan_id')->nullable()->after('ke_user_id')->constrained('ruangan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('serah_terima', function (Blueprint $table) {
            $table->dropForeign(['ruangan_tujuan_id']);
            $table->dropColumn('ruangan_tujuan_id');
        });
    }
};
