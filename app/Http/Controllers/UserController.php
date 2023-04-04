<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth:api', ['except' => ['index']]);
   }

   public function index()
   {
      $user = User::all();
      return response()->json([
         'status' => 'success',
         'data' => $user,
      ]);
   }
   public function terima(Request $request)
   {
      if (Auth::user()->hasRole('admin')) {
         $user = User::find($request->id);
         if ($user) {
            $user->givePermissionTo('login');
            return response()->json([
               'status' => 'success',
               'message' => $user->email . ' sudah dapat melakukan login',
            ]);
         }
         return response(["status" => 401, 'message' => 'user not found']);
      }
      return response(["status" => 401, 'message' => 'doesnt have permission']);
   }

   public function show($id)
   {
      $user = User::find($id);
      return response()->json([
         'status' => 'success',
         'user' => $user,
      ]);
   }

   public function update(Request $request, $id)
   {

      $user = User::find($id);

      $user->update([
         'name' => $request->name,
         'email' => $request->email,
      ]);

      return response([
         "status" => 200,
         'message' => 'user berhasil update',
      ]);
   }

   public function destroy($id)
   {
      $user = User::find($id);
      $user->delete();

      return response()->json([
         'status' => 'success',
         'message' => 'Kapal deleted successfully',
         'user' => $user,
      ]);
   }
}
