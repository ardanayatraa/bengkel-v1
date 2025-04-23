<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id_user' => '1',
            'nama_user' => 'Admin',
            'level' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('password123'), // Pastikan password di-hash
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id_user' => '2',
            'nama_user' => 'Kasir',
            'level' => 'kasir',
            'username' => 'kasir',
            'password' => Hash::make('password123'), // Pastikan password di-hash
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
