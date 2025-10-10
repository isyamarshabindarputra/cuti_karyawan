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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            // relasi ke tabel Users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip', 50)->nullable();;
            $table->string('name');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('jabatan', 100)->nullable();
            $table->enum('bidang', ['Sekre','TIK','Stasan','PT']);
            $table->integer('sisa_cuti')->default(20);
            // misal awalnya 20 hari cuti per tahun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
