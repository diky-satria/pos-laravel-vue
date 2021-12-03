<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Kategori::orderBy('id','DESC')->get();

        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('action', function($data){
                                    $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('.$data->id.')">Edit</button>';
                                    $button .= '<button class="btn btn-sm btn-danger ms-1" onclick="component.hapus('. $data->id .')">Hapus</button>';

                                    return $button;
                                })
                                ->rawColumns(['action'])
                                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nama' => 'required'
        ],[
            'nama.required' => 'Nama harus di isi'
        ]);

        Kategori::create([
            'nama' => ucwords(request('nama'))
        ]);

        return response()->json([
            'message' => 'kategori berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Kategori::find($id);

        return response()->json([
            'message' => 'detail kategori',
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'nama' => 'required'
        ],[
            'nama.required' => 'Nama harus di isi'
        ]);

        $data = Kategori::find($id);

        $data->update([
            'nama' => ucwords(request('nama'))
        ]);

        return response()->json([
            'message' => 'kategori berhasil di edit'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Kategori::find($id);

        $data->delete();

        return response()->json([
            'message' => 'kategori berhasil di hapus'
        ]);
    }
}
