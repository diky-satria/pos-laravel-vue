<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangInController extends Controller
{
    public function index()
    {
        return view('user.barangIn.barangin');
    }
}
