<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('kapals', function (Blueprint $table) {
         $table->id();
         $table->unsignedInteger('id_user');
         $table->text('kode');
         $table->text('nama');
         $table->text('pemilik');
         $table->text('pemilik_alamat');
         $table->integer('ukuran');
         $table->text('kapten');
         $table->integer('jml_anggota');
         $table->text('foto');
         $table->integer('no_izin');
         $table->text('doc_izin');
         $table->text('catatan')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('kapals');
   }
};
