<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KapalController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth:api');
   }

   public function index()
   {
      $kapal = Kapal::all();
      return response()->json([
         'status' => 'success',
         'kapal' => $kapal,
      ]);
   }

   public function store(Request $request)
   {

      if ($foto = $request->file('foto')) {
         $path_foto = $foto->store('public/files');
      }
      if ($doc_izin = $request->file('foto')) {
         $path_doc = $doc_izin->store('public/files');
      }

      $kapal = Kapal::create([
         'id_user' => Auth::user()->id,
         'kode' => $request->kode,
         'nama' => $request->nama,
         'pemilik' => $request->pemilik,
         'pemilik_alamat' => $request->pemilik_alamat,
         'ukuran' => $request->ukuran,
         'kapten' => $request->kapten,
         'jml_anggota' => $request->jml_anggota,
         'foto' => $path_foto,
         'no_izin' => $request->no_izin,
         'doc_izin' => $path_doc,
      ]);

      return response()->json([
         'status' => 'success',
         'message' => 'Kapal created successfully',
         'kapal' => $kapal,
      ]);
   }

   public function terima(Request $request)
   {
      if (Auth::user()->hasRole('admin')) {
         $kapal = Kapal::find($request->id);

         if (!$kapal) {
            return response(["status" => 401, 'message' => 'kapal not found']);
         }

         if ($request->status == 1) {
            if ($kapal) {
               $kapal->givePermissionTo('berlayar');
               return response()->json([
                  'status' => 'success',
                  'message' => 'kapal ' . $kapal->nama . ' sudah dapat berlayar',
               ]);
            }
         }
         $kapal->update(['catatan' => $request->catatan]);

         return response([
            "status" => 401,
            'message' => 'kapal gagal berlayar',
            'catatan' => $request->catatan
         ]);
      }
      return response(["status" => 401, 'message' => 'doesnt have permission']);
   }

   public function show($id)
   {
      $kapal = Kapal::find($id);
      return response()->json([
         'status' => 'success',
         'kapal' => $kapal,
      ]);
   }

   public function update(Request $request, $id)
   {

      $kapal = Kapal::find($id);
      if ($foto = $request->file('foto')) {
         $path_foto = $foto->store('public/files');
      }
      if ($doc_izin = $request->file('foto')) {
         $path_doc = $doc_izin->store('public/files');
      }

      $kapal->update([
         'id_user' => Auth::user()->id,
         'kode' => $request->kode,
         'nama' => $request->nama,
         'pemilik' => $request->pemilik,
         'pemilik_alamat' => $request->pemilik_alamat,
         'ukuran' => $request->ukuran,
         'kapten' => $request->kapten,
         'jml_anggota' => $request->jml_anggota,
         'foto' => $path_foto,
         'no_izin' => $request->no_izin,
         'doc_izin' => $path_doc,
      ]);

      return response([
         "status" => 200,
         'message' => 'kapal berhasil update',
         'catatan' => $request->catatan
      ]);
   }

   public function destroy($id)
   {
      $kapal = Kapal::find($id);
      $kapal->delete();

      return response()->json([
         'status' => 'success',
         'message' => 'Kapal deleted successfully',
         'kapal' => $kapal,
      ]);
   }
}
