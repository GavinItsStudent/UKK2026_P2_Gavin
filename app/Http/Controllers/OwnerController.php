<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
     public function dashboard()
    {
        return view('Main.owner');
    }

    public function rekap()
    {
        return view('Owner.rekap');
    }
}
