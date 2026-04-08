<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function dashboard()
    {
        return view('Main.petugas');
    }

    public function transaksi()
    {
        return view('Petugas.transaksi');
    }
}
