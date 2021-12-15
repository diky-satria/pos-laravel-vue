<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangInController extends Controller
{
    public function index()
    {
        $tanggal = request('tanggal');

        if($tanggal){
            $data = BarangIn::whereDate('tanggal', $tanggal)->orderBy('id','DESC')->get();
        }else{
            $data = BarangIn::whereDate('tanggal', now())->orderBy('id','DESC')->get();
        }
        
        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('petugas', function($data){
                                    return $data->petugas->name;
                                })
                                ->addColumn('tanggal', function($data){
                                    return date('j M Y', strtotime($data->tanggal));
                                })
                                ->addColumn('kode', function($data){
                                    return $data->barang->kode;
                                })
                                ->addColumn('nama', function($data){
                                    return $data->barang->nama;
                                })
                                ->addColumn('keterangan', function($data){
                                    if($data->keterangan == null){
                                        return '----';
                                    }else{
                                        return $data->keterangan;
                                    }
                                })
                                ->addColumn('action', function($data){
                                    if($data->status == 0){
                                        $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('.$data->id.')">Edit</button>';
                                        $button .= '<button class="btn btn-sm btn-danger mx-1" onclick="component.hapus('.$data->id.')">Hapus</button>';
                                        $button .= '<button class="btn btn-sm btn-warning" onclick="component.konfirmasi('.$data->id.')">Konfirmasi</button>';
                                    }else{
                                        $button = 'Terkonfirmasi';
                                    }

                                    return $button;
                                })
                                ->rawColumns(['petugas','tanggal','kode','nama','ketrangan','action'])
                                ->make(true);
        }

        return view('user.barangIn.barangin');
    }

    public function ambil_data_barang()
    {
        $data = Barang::orderBy('nama','ASC')->get();
        $tanggal = date('j M Y', strtotime(now()));
        return response()->json([
            'data' => $data,
            'tanggal' => $tanggal
        ]);
    }

    public function tambah_barang_in()
    {
        request()->validate([
            'barang' => 'required',
            'penambahan' => 'required|numeric'
        ],[
            'barang.required' => 'Barang harus di pilih',
            'penambahan.required' => 'Penambahan harus di isi',
            'penambahan.numeric' => 'Penambahan harus angka'
        ]);

        BarangIn::create([
            'tanggal' => date('Y-m-d', strtotime(request('tanggal'))),
            'id_petugas' => Auth::user()->id,
            'id_barang' => request('barang'),
            'penambahan' => request('penambahan'),
            'keterangan' => request('keterangan') == '' ? null : request('keterangan'),
            'status' => 0
        ]);

        return response()->json([
            'message' => 'barang_in berhasil ditambahkan'
        ]);
    }
 
    public function show($id)
    {
        $barang = BarangIn::find($id);

        $data = [
            'id' => $barang->id,
            'tanggal' => date('j M Y', strtotime($barang->tanggal)),
            'id_barang' => $barang->id_barang,
            'penambahan' => $barang->penambahan,
            'keterangan' => $barang->keterangan == null ? '' : $barang->keterangan,
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    public function update($id)
    {
        $data = BarangIn::find($id);

        request()->validate([
            'penambahan' => 'required|numeric'
        ],[
            'penambahan.required' => 'Penambahan harus di isi',
            'penambahan.numeric' => 'Penambahan harus angka'
        ]);

        $data->update([
            'id_barang' => request('barang'),
            'penambahan' => request('penambahan'),
            'keterangan' => request('keterangan')
        ]);

        return response()->json([
            'message' => 'barang_in berhasil di edit'
        ]);
    }

    public function destroy($id)
    {
        $data = BarangIn::find($id);

        $data->delete();

        return response()->json([
            'message' => 'barang_in berhasil di hapus'
        ]);
    }

    public function konfirmasi($id)
    {
        $barangIn = BarangIn::find($id);

        // update stok barang
        $barang = Barang::find($barangIn->id_barang);
        $barang->update([
            'stok' => $barang->stok + $barangIn->penambahan
        ]);

        // update status barang_in
        $barangIn->update([
            'status' => 1
        ]);

        return response()->json([
            'message' => 'stok barang bertambah dan status barang_in di update menjadi 1'
        ]);
    }
}
