<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function data_select()
    {
        $data = Kategori::orderBy('nama', 'ASC')->get();

        return response()->json([
            'data' => $data
        ]);
    }
    
    public function index()
    {
        $data = Barang::orderBy('id', 'DESC')->get();

        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('gambar', function($data){
                                    $gambar = asset('barang/'. $data->gambar);
                                    return "<img src='".$gambar."' width='80' height='50' style='border-radius:8px;'>";
                                })
                                ->addColumn('kategori', function($data){
                                    return $data->kategori->nama;
                                })
                                ->addColumn('harga', function($data){
                                    return format_rupiah($data->harga);
                                })
                                ->addColumn('action', function($data){
                                    $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('. $data->id .')">Edit</button>';
                                    $button .= '<button class="btn btn-sm btn-danger ms-1" onclick="component.hapus('. $data->id .')">Hapus</button>';

                                    return $button;
                                })
                                ->rawColumns(['gambar','kategori','harga','action'])
                                ->make(true);
        }
    }

    public function store(Request $request)
    {
        request()->validate([
            'kode' => 'required|unique:barangs,kode',
            'nama' => 'required',
            'id_kategori' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',
            'gambar' => 'required|mimes:jpg,png,jpeg,gif|max:2048' 
        ],[
            'kode.required' => 'Kode harus di isi',
            'kode.unique' => 'Kode sudah terdaftar',
            'nama.required' => 'Nama harus di isi',
            'id_kategori.required' => 'Kategori harus di pilih',
            'stok.required' => 'Stok harus di isi',
            'stok.numeric' => 'Stok harus angka',
            'harga.required' => 'Harga harus di isi',
            'harga.numeric' => 'Harga harus angka',
            'gambar.required' => 'Gambar harus di isi',
            'gambar.mimes' => 'Format file harus jpg/jpeg/png/gif',
            'gambar.max' => 'Ukuran gambar maximal 2 MB'
        ]);

        //upload gambar
        $gambar = request()->file('gambar');
        $extension = $gambar->getClientOriginalExtension();
        $upload = time() .'.'. $extension;
        $gambar->move(public_path('barang/'), $upload);

        Barang::create([
            'kode' => strtoupper(request('kode')),
            'nama' => ucwords(request('nama')),
            'id_kategori' => request('id_kategori'),
            'stok' => request('stok'),
            'harga' => request('harga'),
            'gambar' => $upload
        ]);

        return response()->json([
            'message' => 'barang berhasil ditambahkan'
        ]);
        
    }

    public function show($id)
    {
        $data = Barang::find($id);

        return response()->json([
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'nama' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric', 
        ],[
            'nama.required' => 'Nama harus di isi',
            'stok.required' => 'Stok harus di isi',
            'stok.numeric' => 'Stok harus angka',
            'harga.required' => 'Harga harus di isi',
            'harga.numeric' => 'Harga harus angka',
        ]);

        $data = Barang::find($id);

        $gambar = request()->file('gambar');
        if($gambar){
            // jika ada gambar nya maka validasi 
            request()->validate([
                'gambar' => 'mimes:jpg,png,jpeg,gif|max:2048'
            ],[
                'gambar.mimes' => 'Format file harus jpg/jpeg/png/gif',
                'gambar.max' => 'Ukuran gambar maximal 2 MB'
            ]);
            
            // jika ada gambar yang akan diedit maka hapus gambar lama
            $gambar_lama = $data->gambar;
            if($gambar_lama){
                unlink('barang/'. $gambar_lama);
            }

            // upload gambar lama dengan yang baru
            $extension = $gambar->getClientOriginalExtension();
            $upload = time() .'.'. $extension;
            $gambar->move(public_path('barang/'), $upload);

            // insert ke database
            $data->update([
                'nama' => ucwords(request('nama')),
                'id_kategori' => request('id_kategori'),
                'stok' => request('stok'),
                'harga' => request('harga'),
                'gambar' => $upload
            ]);
        }else{
            // jika tidak ada gambar yang akan di edit maka hanya insert ke database
            $data->update([
                'nama' => ucwords(request('nama')),
                'id_kategori' => request('id_kategori'),
                'stok' => request('stok'),
                'harga' => request('harga')
            ]);
        }

    }

    public function destroy($id)
    {
        $data = Barang::find($id);

        $gambar_lama = $data->gambar;
        if($gambar_lama){
            unlink('barang/'.$gambar_lama);
        }

        $data->delete();

        return response()->json([
            'message' => 'barang berhasil dihapus'
        ]);
    }

}
