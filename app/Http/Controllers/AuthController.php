<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth:api', ['except' => ['login', 'register', 'verify_otp']]);
   }

   public function login(Request $request)
   {
      $request->validate([
         'email' => 'required|string|email',
         'password' => 'required|string',
      ]);
      $credentials = $request->only('email', 'password');

      $token = Auth::attempt($credentials);
      if (!$token) {
         return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
         ], 401);
      }


      $user = Auth::user();
      if ($user->hasPermissionTo('login')) {
         return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
               'token' => $token,
               'type' => 'bearer',
            ]
         ]);
      }
      return response(["status" => 401, 'message' => 'doesnt have permission']);
   }

   public function register(Request $request)
   {
      $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users',
         'password' => 'required|string|confirmed|min:6',
      ]);

      $otp = rand(1000, 9999);

      Http::post('https://script.google.com/macros/s/AKfycbxFNsyMXW8chGL8YhdQE1Q1yBbx5XEsq-BJeNF1a6sKoowaL_9DtcUvE_Pp0r5ootgMhQ/exec', [
         'email' => $request->email,
         'subject' => 'Network Administrator',
         'message' => 'Kode OTP : ' . $otp,
         'token' => '1dy09eODblmBUCTnIwiY-hbXdzCpZC3jyR4l0ZJgqQqO9L7J3zsZOobdJ',
      ]);

      $user = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => Hash::make($request->password),
         'otp' => $otp
      ]);

      $token = Auth::login($user);
      return response()->json([
         'status' => 'success',
         'message' => 'User created successfully, check email',
      ]);
   }

   public function logout()
   {
      Auth::logout();
      return response()->json([
         'status' => 'success',
         'message' => 'Successfully logged out',
      ]);
   }

   public function refresh()
   {
      return response()->json([
         'status' => 'success',
         'user' => Auth::user(),
         'authorisation' => [
            'token' => Auth::refresh(),
            'type' => 'bearer',
         ]
      ]);
   }

   public function verify_otp(Request $request)
   {
      $user  = User::where([['email', '=', $request->email], ['otp', '=', $request->otp]])->first();
      if ($user) {
         if ($user->email_verified_at) {
            return response()->json([
               'status' => 'success',
               'user' => $user->email,
               'message' => 'already verified',
            ]);
         }
         $user->update(['email_verified_at' => Carbon::now()->timestamp]);
         return response()->json([
            'status' => 'success',
            'user' => $user->email,
            'email_verified_at' => Carbon::now(),
         ]);
      } else {
         return response(["status" => 401, 'message' => 'Invalid']);
      }
   }
}
