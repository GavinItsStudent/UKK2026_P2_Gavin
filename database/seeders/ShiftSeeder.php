<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'nama_shift' => 'Pagi',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '11:55:00'
        ]);

        Shift::create([
            'nama_shift' => 'Sore',
            'jam_masuk' => '12:00:00',
            'jam_keluar' => '18:10:00'
        ]);

        Shift::create([
            'nama_shift' => 'Malam',
            'jam_masuk' => '18:15:00',
            'jam_keluar' => '21:00:00'
        ]);
    }
}
