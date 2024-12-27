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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_model');
            $table->integer('kategorikejadian_id');
            $table->integer('kecamatan_id');
            $table->integer('desa_id');
            $table->date('tanggal_kejadian');
            $table->date('tanggal_laporan');
            $table->string('lokus_kejadian');
            $table->text('isi_laporan');
            $table->integer('level_laporan');
            $table->string('status');
            $table->text('latitude_lokus');
            $table->text('longitude_lokus');
            $table->integer('is_ditindak');
            $table->date('tanggal_ditindak');
            $table->text('isi_tindakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
