<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function select_barang()
    {
        $barang = Barang::orderBy('nama','ASC')
                        ->where('stok', '>', 0)
                        ->get();

        return response()->json([
            'barang' => $barang
        ]);
    }

    public function select_pelanggan()
    {
        $pelanggan = Pelanggan::orderBy('nama','ASC')->get();

        return response()->json([
            'pelanggan' => $pelanggan
        ]);
    }

    public function kode_tgl()
    {
        $kode = Auth::user()->id . time();
        $tgl_sekarang = now();
        $tgl = date('j M Y', strtotime($tgl_sekarang));

        return response()->json([
            'kode' => $kode,
            'tgl' => $tgl
        ]);
    }

    public function index()
    {
        return view('user.penjualan.penjualan');
    }

    public function tambah_transaksi()
    {
        request()->validate([
            'kode' => 'required',
            'tgl' => 'required',
            'id_barang' => 'required'
        ],[
            'kode.required' => 'Kode harus di isi',
            'tgl.required' => 'Tanggal harus di isi',
            'id_barang.required' => 'Barang harus di pilih'
        ]);

        $request_tgl = request('tgl');
        $tgl = date('Y-m-d H:i', strtotime($request_tgl));

        $request_kode = request('kode');
        $trans = Transaksi::where('kode', $request_kode)->first();
        
        if($trans){
            TransaksiBarang::create([
                'id_transaksi' => $trans->id,
                'id_barang' => request('id_barang'),
                'qty' => 1
            ]);

            $barang = Barang::where('id', request('id_barang'))->first();
            $barang->update([
                'stok' => $barang->stok - 1
            ]);
        }else{
            $transaksi = Transaksi::create([
                'kode' => request('kode'),
                'tgl' => $tgl,
                'id_pelanggan' => null,
                'id_petugas' => Auth::user()->id,
                'status' => 0
            ]);

            TransaksiBarang::create([
                'id_transaksi' => $transaksi->id,
                'id_barang' => request('id_barang'),
                'qty' => 1
            ]);

            $barang = Barang::where('id', request('id_barang'))->first();
            $barang->update([
                'stok' => $barang->stok - 1
            ]);
        }

        return response()->json([
            'message' => 'transaksi berhasil ditambahkan'
        ]);
    }

    public function detail_data_transaksi($kode)
    {
        $transaksi = Transaksi::where('kode', $kode)->first();
        
        if($transaksi){
            $detail = $transaksi->transaksi_barangs()->get();

            $total = 0;
            $data = [];
            foreach($detail as $d){
                $data[] = [
                    'id' => $d->id,
                    'id_transaksi' => $transaksi->id,
                    'id_pivot' => $d->pivot->id,
                    'nama' => $d->nama,
                    'harga' => $d->harga,
                    'qty' => $d->pivot->qty
                ];

                $total = $total + ($d->harga * $d->pivot->qty);
            }

            return response()->json([
                'data' => $data,
                'total' => $total,
                'cek' => 1
            ]);
        }else{
            return response()->json([
                'data' => null,
                'total' => null,
                'cek' => 0
            ]);
        }
    }

    public function hapus_data_transaksi($id_barang, $id_pivot, $qty)
    {
        // update stok
        $barang = Barang::find($id_barang);
        $barang->update([
            'stok' => $barang->stok + $qty
        ]);

        // hapus
        $data_pivot = TransaksiBarang::find($id_pivot);
        $data_pivot->delete();

        return response()->json([
            'message' => 'barang di table pivot berhasil di hapus'
        ]);
    }

    public function tambah_data_transaksi($id_barang, $id_pivot)
    {
        $barang = Barang::find($id_barang);
        // cek
        if($barang->stok <= 0){
            return response()->json([
                'cek' => 'gagal',
                'message' => 'qty di table pivot gagal di tambah'
            ]);
        }else{
            // kurangi stok barang
            $barang->update([
                'stok' => $barang->stok - 1
            ]);
    
            // ubah qty di pivot
            $data_pivot = TransaksiBarang::find($id_pivot);
            $data_pivot->update([
                'qty' => $data_pivot->qty + 1
            ]);
    
            return response()->json([
                'cek' => 'berhasil',
                'message' => 'qty di table pivot berhasil di tambah'
            ]);
        }
    }

    public function kurang_data_transaksi($id_barang, $id_pivot)
    {
        $data_pivot = TransaksiBarang::find($id_pivot);
        // cek
        if($data_pivot->qty <= 1){
            return response()->json([
                'cek' => 'gagal',
                'message' => 'qty tidak bisa dikurangi lagi'
            ]);
        }else{
            // kurangi qty di pivot
            $data_pivot->update([
                'qty' => $data_pivot->qty - 1
            ]);
    
            // tambah stok barang
            $barang = Barang::find($id_barang);
            $barang->update([
                'stok' => $barang->stok + 1
            ]);

            return response()->json([
                'cek' => 'berhasil',
                'message' => 'qty berhasil dikurangi'
            ]);
        }
    }

    public function update_status_transaksi($kode)
    {
        $transaksi = Transaksi::where('kode', $kode)->first();

        request()->validate([
            'tunai' => 'required|numeric', 
            'id_pelanggan' => 'required'
        ],[
            'tunai.required' => 'Tunai harus di isi',
            'tunai.numeric' => 'Tunai harus angka',
            'id_pelanggan.required' => 'Pelanggan harus di pilih'
        ]);

        $transaksi->update([
            'id_pelanggan' => request('id_pelanggan') == 'umum' ? null : request('id_pelanggan'),
            'status' => 1,
            'total' => request('total'),
            'tunai' => request('tunai'),
            'kembalian' => request('kembalian')
        ]);

        return response()->json([
            'message' => 'status transaksi berhasil di update'
        ]);
    }
}
