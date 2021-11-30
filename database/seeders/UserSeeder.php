<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Diky Satria Ramadanu',
            'email' => 'dikysr123456@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $admin->assignRole('admin');

        $petugas = User::create([
            'name' => 'Soleh',
            'email' => 'soleh@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $petugas->assignRole('petugas');
    }
}
