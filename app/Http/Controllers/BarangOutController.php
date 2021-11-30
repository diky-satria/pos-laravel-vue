<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangOutController extends Controller
{
    public function index()
    {
        return view('user.barangOut.barangout');
    }
}
