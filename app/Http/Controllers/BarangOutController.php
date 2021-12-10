<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangOutController extends Controller
{
    public function index()
    {
        $tanggal = request('tanggal');
        if($tanggal){
            $data = BarangOut::whereDate('tanggal', $tanggal)->orderBy('id','DESC')->get();
        }else{
            $data = BarangOut::whereDate('tanggal', now())->orderBy('id','DESC')->get();
        }
        
        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('tanggal', function($data){
                                    return date('j M Y', strtotime($data->tanggal));
                                })
                                ->addColumn('petugas', function($data){
                                    return $data->petugas->name;
                                })
                                ->addColumn('kode', function($data){
                                    return $data->barang->kode;
                                })
                                ->addColumn('nama', function($data){
                                    return $data->barang->nama;
                                })
                                ->rawColumns(['tanggal','petugas','kode','nama'])
                                ->make(true);
        }

        return view('user.barangOut.barangout');
    }

    public function barang_tidak_kosong()
    {
        $data = Barang::where('stok', '>', 0)->orderBy('nama','ASC')->get();
        $tanggal = date('j M Y', strtotime(now()));
        return response()->json([
            'data' => $data,
            'tanggal' => $tanggal
        ]);
    }

    public function store()
    {
        request()->validate([
            'barang' => 'required',
            'pengurangan' => 'required|numeric',
            'keterangan' => 'required'
        ],[
            'barang.required' => 'Barang harus di pilih',
            'pengurangan.required' => 'Pengurangan harus di isi',
            'pengurangan.numeric' => 'Pengurangan harus angka',
            'keterangan.required' => 'Keterangan harus di isi'
        ]);

        $req_barang = request('barang');
        $req_pengurangan = request('pengurangan');

        $barang = Barang::find($req_barang);

        // cek apakah jumlah pengurangan yang di input lebih besar dari stok barang nya

        // jika lebih besar lakukan ini
        if($req_pengurangan > $barang->stok){
            return response()->json([
                'message' => 'gagal' 
            ]);
        }else{
            // jika tidak lakukan ini
            $barang->update([
                'stok' => $barang->stok - $req_pengurangan
            ]);

            BarangOut::create([
                'tanggal' => date('Y-m-d', strtotime(request('tanggal'))),
                'id_petugas' => Auth::user()->id,
                'id_barang' => request('barang'),
                'pengurangan' => request('pengurangan'),
                'keterangan' => request('keterangan')
            ]);

            return response()->json([
                'message' => 'berhasil' 
            ]);
        }
    }
}
