<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('home');
    }

    public function supplier()
    {
        return view('admin.supplier');
    }

    public function kategori()
    {
        return view('admin.kategori');
    }

    public function barangs()
    {
        return view('admin.barangs');
    }
}
