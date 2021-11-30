<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function laporan_harian()
    {
        return view('user.laporan.harian');
    }

    public function laporan_bulanan()
    {
        return view('user.laporan.bulanan');
    }
}
