<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $user = User::create([
         'name' => 'admin',
         'email' => 'admin@gmail.com',
         'password' => Hash::make('admin'),
         'otp' => '1111'
      ]);

      Role::create(
         ['name' => 'admin'],
      );
      Role::create(
         ['name' => 'user'],
      );
      Permission::create(
         ['name' => 'login'],
      );
      Permission::create(
         ['name' => 'berlayar'],
      );

      $user->assignRole('admin');
      $user->givePermissionTo('login');
   }
}
