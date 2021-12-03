<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Supplier::orderBy('id','DESC')->get();

        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('action', function($data){
                                    $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('. $data->id .')">Edit</button>';
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
            'nama' => 'required',
            'email' => 'required|email|unique:suppliers,email',
            'telp' => 'required|numeric|digits_between:1,15',
            'alamat' => 'required'
        ],[
            'nama.required' => 'Nama harus di isi',
            'email.required' => 'Email harus di isi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'telp.required' => 'Telepon harus di isi',
            'telp.numeric' => 'Telepon harus angka',
            'telp.digits_between' => 'Telepon maksimal 15 digit',
            'alamat.required' => 'Alamat harus di isi'
        ]);

        Supplier::create([
            'nama' => ucwords(request('nama')),
            'email' => request('email'),
            'telp' => request('telp'),
            'alamat' => request('alamat')
        ]);

        return response()->json([
            'message' => 'supplier berhasil ditambahkan'
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
        $data = Supplier::find($id);

        return response()->json([
            'message' => 'detail supplier',
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
            'nama' => 'required',
            'telp' => 'required|numeric|digits_between:1,15',
            'alamat' => 'required'
        ],[
            'nama.required' => 'Nama harus di isi',
            'telp.required' => 'Telepon harus di isi',
            'telp.numeric' => 'Telepon harus angka',
            'telp.digits_between' => 'Telepon maksimal 15 digit',
            'alamat.required' => 'Alamat harus di isi'
        ]);

        $data = Supplier::find($id);

        $data->update([
            'nama' => ucwords(request('nama')),
            'telp' => request('telp'),
            'alamat' => request('alamat')
        ]);

        return response()->json([
            'message' => 'supplier berhasil di edit'
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
        $data = Supplier::find($id);

        $data->delete();

        return response()->json([
            'message' => 'supplier berhasil di hapus'
        ]);
    }
}
