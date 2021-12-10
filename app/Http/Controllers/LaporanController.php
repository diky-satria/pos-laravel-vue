<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\LaporanHarianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBulananExport;

class LaporanController extends Controller
{
    public function laporan_harian()
    {
        $data = Transaksi::whereDate('tgl', now())->where('status', 1)->orderBy('id','DESC')->get();
    
        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('tgl', function($data){ 
                                    return date('j M Y', strtotime($data->tgl));
                                })
                                ->addColumn('pelanggan', function($data){
                                    if($data->id_pelanggan == null){
                                        return 'Umum';
                                    }else{
                                        return $data->pelanggan->nama;
                                    }
                                })
                                ->addColumn('petugas', function($data){
                                    return $data->petugas->name;
                                })
                                ->addColumn('jumlah', function($data){
                                    $val = $data->transaksi_barangs()->sum('qty');
                                    
                                    return $val;
                                })
                                ->addColumn('status', function(){
                                    return 'Berhasil';
                                })
                                ->rawColumns(['pelanggan','petugas','jumlah','status'])
                                ->make(true);
        }

        return view('user.laporan.harian');
    }

    public function exportHarianExcel() 
    {
        return Excel::download(new LaporanHarianExport, 'laporan_harian.xlsx');
    }

    public function exportHarianPdf()
    {
        $tgl_sekarang = date('Y-m-d');
        $tanggal = date('j M Y', strtotime($tgl_sekarang));
        $data = Transaksi::whereDate('tgl', now())->where('status', 1)->orderBy('id','DESC')->get();
        
        $pdf = PDF::loadView('export.laporan_harian_pdf', ['data' => $data, 'tanggal' => $tanggal]);
        return $pdf->stream('Laporan harian.pdf');
    }

    public function laporan_bulanan()
    {
        $tgl_awal = request('tgl_awal');
        $tgl_akhir = request('tgl_akhir');

        if($tgl_awal && $tgl_akhir){
            $data = Transaksi::whereBetween('tgl', [$tgl_awal, $tgl_akhir])->where('status', 1)->orderBy('id','DESC')->get();
        }else{
            $data = Transaksi::whereBetween('tgl', [now()->firstOfMonth(), now()])->where('status', 1)->orderBy('id','DESC')->get();
        }
    
        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('tgl', function($data){ 
                                    return date('j M Y', strtotime($data->tgl));
                                })
                                ->addColumn('pelanggan', function($data){
                                    if($data->id_pelanggan == null){
                                        return 'Umum';
                                    }else{
                                        return $data->pelanggan->nama;
                                    }
                                })
                                ->addColumn('petugas', function($data){
                                    return $data->petugas->name;
                                })
                                ->addColumn('jumlah', function($data){
                                    $val = $data->transaksi_barangs()->sum('qty');
                                    
                                    return $val;
                                })
                                ->addColumn('status', function(){
                                    return 'Berhasil';
                                })
                                ->rawColumns(['pelanggan','petugas','jumlah','status'])
                                ->make(true);
        }

        return view('user.laporan.bulanan');
    }

    public function laporan_bulanan_data()
    {
        $tgl_awal = now()->firstOfMonth()->format('j M Y');
        $tgl_sekarang = now()->format('j M Y');

        return response()->json([
            'tgl_awal' => $tgl_awal,
            'tgl_sekarang' => $tgl_sekarang
        ]);
    }

    public function exportBulananExcel() 
    {
        $awal = request('awal') ? request('awal') : now()->firstOfMonth() ;
        $akhir = request('akhir') ? request('akhir') : now() ;
        return Excel::download(new LaporanBulananExport($awal, $akhir), 'laporan_bulanan.xlsx');
    }

    public function exportBulananPdf()
    {
        $tgl_awal = request('awal') ? request('awal') : now()->firstOfMonth();
        $tgl_akhir = request('akhir') ? request('akhir') : now();

        if($tgl_awal && $tgl_akhir){
            $data = Transaksi::whereBetween('tgl', [$tgl_awal, $tgl_akhir])->where('status', 1)->orderBy('id', 'DESC')->get();
        }else{
            $data = Transaksi::whereBetween('tgl', [now()->firstOfMonth(), now()])->where('status', 1)->orderBy('id', 'DESC')->get();
        }
        
        
        $pdf = PDF::loadView('export.laporan_bulanan_pdf', ['data' => $data, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]);
        return $pdf->stream('Laporan bulanan.pdf');
    }
}
