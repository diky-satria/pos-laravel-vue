<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanHarianExport implements FromView
{
    public function view(): View
    {
        $tgl_sekarang = date('Y-m-d');
        $tanggal = date('j M Y', strtotime($tgl_sekarang));
        $data = Transaksi::whereDate('tgl', now())->where('status', 1)->orderBy('id','DESC')->get();

        return view('export.laporan_harian_excel', [
            'data' => $data,
            'tanggal' => $tanggal
        ]);
    }
}
