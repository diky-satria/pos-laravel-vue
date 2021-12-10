<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance. 
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $barang = Barang::count();
        $supplier = Supplier::count();
        $pelanggan = Pelanggan::count();
        $transaksi_gagal = Transaksi::where('status', 0)->count();

        // doughnut chart
        $doughnut = TransaksiBarang::select('nama', DB::raw('SUM(qty) as jumlah'))
                                    ->join('barangs', 'barangs.id', '=', 'transaksi_barangs.id_barang')
                                    ->join('transaksis', 'transaksis.id', '=', 'transaksi_barangs.id_transaksi')
                                    ->where('status', 1)
                                    ->groupBy('nama')
                                    ->orderBy('jumlah','DESC')
                                    ->limit(5)
                                    ->get();
        $label_doughnut = [];
        $data_doughnut = [];
        foreach($doughnut as $d){
            $label_doughnut[] = $d->nama;
            $data_doughnut[] = $d->jumlah;
        }

        // line chart
        $data_line = [];
        foreach(range(1,12) as $month){
            $data_line[] = Transaksi::select(DB::raw('COUNT(*) as total'))->whereMonth('tgl', $month)->whereYear('tgl', now())->first()->total;
        }
        
        return view('home', [
            'barang' => $barang,
            'supplier' => $supplier,
            'pelanggan' => $pelanggan,
            'transaksi_gagal' => $transaksi_gagal,
            'label_doughnut' => $label_doughnut,
            'data_doughnut' => $data_doughnut,
            'data_line' => $data_line
        ]);
    }
}
