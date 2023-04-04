<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Kapal extends Model
{
   use HasFactory, HasRoles;
   protected $fillable = [
      'id_user',
      'kode',
      'nama',
      'pemilik',
      'pemilik_alamat',
      'ukuran',
      'kapten',
      'jml_anggota',
      'foto',
      'no_izin',
      'doc_izin',
      'catatan'
   ];
}
