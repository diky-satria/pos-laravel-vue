<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanBulananExport implements FromView
{
    protected $awal;
    protected $akhir;

    public function __construct($awal, $akhir)
    {
        $this->awal = $awal;
        $this->akhir = $akhir;
    }

    public function view(): View
    {
        $data = Transaksi::whereBetween('tgl', [$this->awal, $this->akhir])->where('status', 1)->orderBy('id', 'DESC')->get();

        return view('export.laporan_bulanan_excel', [
            'awal_tanggal' => $this->awal,
            'sampai_tanggal' => $this->akhir,
            'data' => $data
        ]);
    }
}
