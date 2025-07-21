<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // kita buat dulu schemannya dari dataabase 
    // unsigned interger dia haru positif  jgn negatif
    // forengin id relasi ke city_id
    // laravel otomatis cari relasi dnegan id ke city _id dengan constrained
    // slug itu akan buat unik untuk seo tiap halaman
    // soft deletes itu untuk menambahkan deleted at jadi jika kita hapus data kanotr di gak hilang di dtabse bisa kit apulihkan waktu waktu jika butuh

    public function up(): void
    {
        Schema::create('office_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('thumbnail');
            $table->string('address');
            $table->boolean('is_open');
            $table->boolean('is_full_booked');
            $table->unsignedInteger('price');
            $table->unsignedInteger('duration');
            $table->text('about');
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_spaces');
    }
};
