<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiBarang;

class PenjualanGagalController extends Controller
{
    public function index()
    {
        $data = Transaksi::where('status', 0)->orderBy('id', 'DESC')->get();

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
                                    return $data->transaksi_barangs()->sum('qty');
                                })
                                ->addColumn('status', function($data){
                                    if($data->status == 0){
                                        return '<span class="badge rounded-pill bg-danger">Gagal</span>';
                                    }else{
                                        return '<span class="badge rounded-pill bg-success">Berhasil</span>';
                                    }
                                })
                                ->addColumn('action', function($data){
                                    $button = '<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.detail('. $data->id .')">Detail</button>';

                                    $button .= '<button class="btn btn-sm btn-warning ms-1" onclick="component.kembalikan('. $data->id .')">Kembalikan Barang</button>';

                                    return $button;
                                })
                                ->rawColumns(['pelanggan','petugas','jumlah','status','action'])
                                ->make(true);
        }
    }

    public function show($id)
    {
        // data transaksi
        $transaksi = Transaksi::find($id);
        $data = [
            'id' => $transaksi->id,
            'kode' => $transaksi->kode,
            'tgl' => date('j M Y', strtotime($transaksi->tgl)),
            'id_pelanggan' => $transaksi->id_pelanggan,
            'nama_pelanggan' => $transaksi->id_pelanggan == null ? 'Umum' : $transaksi->pelanggan->nama,
            'id_petugas' => $transaksi->id_petugas,
            'nama_petugas' => $transaksi->petugas->name,
            'status' => $transaksi->status,
            'total' => $transaksi->total == null ? '---' : format_rupiah($transaksi->total),
            'tunai' => $transaksi->tunai == null ? '---' : format_rupiah($transaksi->tunai),
            'kembalian' => $transaksi->kembalian == null ? '---' : format_rupiah($transaksi->kembalian),
        ];

        // data detail transaksi
        $transaksi_barang = $transaksi->transaksi_barangs()->get();
        $detail = [];
        foreach($transaksi_barang as $tb){
            $detail[] = [
                'nama' => $tb->nama,
                'harga' => $tb->harga,
                'qty' => $tb->pivot->qty
            ];
        }

        return response()->json([
            'data' => $data,
            'detail' => $detail
        ]);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        $trans_bar = TransaksiBarang::where('id_transaksi', $id)->get();

        // kembalikan stok barang
        foreach($trans_bar as $tb){
            $barang = Barang::find($tb->id_barang);

            $barang->update([
                'stok' => $barang->stok + $tb->qty
            ]);
        }

        // hapus data di table transaksi barang
        TransaksiBarang::where('id_transaksi', $id)->delete();

        // hapus data di table transaksi
        $transaksi->delete();

        return response()->json([
            'message' => 'berhasil'
        ]);
    }
}
