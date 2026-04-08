<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'Administrator',
            'email' => 'admin@ukk2026.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'status_aktif' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}