<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::orderBy('id','DESC')->with('roles')->get();

        if(request()->ajax()){
            return datatables()->of($data)
                                ->addColumn('hak_akses', function($data){
                                    return $data->roles->pluck('name')[0];
                                })
                                ->addColumn('action', function($data){
                                    if($data->roles->pluck('name')[0] == 'admin'){
                                        $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('. $data->id .')">Edit</button>';
                                    }else{
                                        $button = '<button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="component.edit('. $data->id .')">Edit</button>';
                                        $button .= '<button class="btn btn-sm btn-danger ms-1" onclick="component.hapus('. $data->id .')">Hapus</button>';
                                    }

                                    return $button;
                                })
                                ->rawColumns(['hak_access','action'])
                                ->make(true);
        }

        return view('user.petugas.petugas');
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|same:password'
        ],[
            'name.required' => 'Nama harus di isi',
            'email.required' => 'Email harus di isi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus di isi',
            'password.min' => 'Password minimal 8 karakter',
            'konfirmasi_password.required' => 'Konfirmasi password harus di isi',
            'konfirmasi_password.same' => 'Konformasi password salah'
        ]);

        $petugas = User::create([
            'name' => ucwords(request('name')),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        $petugas->assignRole('petugas');

        return response()->json([
            'message' => 'petugas berhasil ditambahkan'
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
        $data = User::find($id);

        return response()->json([
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
        
        $petugas = User::find($id);
        
        $pass = request('password');
        if($pass){
            request()->validate([
                'name' => 'required',
                'email' => request('email') == $petugas->email ? 'required|email' : 'required|email|unique:users,email',
                'password' => 'min:8',
                'konfirmasi_password' => 'required|same:password'
            ],[
                'name.required' => 'Nama harus di isi',
                'email.required' => 'Email harus di isi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.min' => 'Password minimal 8 karakter',
                'konfirmasi_password.required' => 'Konfirmasi password harus di isi',
                'konfirmasi_password.same' => 'Konfirmasi password salah'
            ]);

            $petugas->update([
                'name' => ucwords(request('name')),
                'email' => request('email'),
                'password' => bcrypt(request('password'))
            ]);
        }else{
            request()->validate([
                'name' => 'required',
                'email' => request('email') == $petugas->email ? 'required|email' : 'required|email|unique:users,email',
            ],[
                'name.required' => 'Nama harus di isi',
                'email.required' => 'Email harus di isi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
            ]);

            $petugas->update([
                'name' => ucwords(request('name')),
                'email' => request('email'),
            ]);
        }

        return response()->json([
            'message' => 'petugas berhasil di edit'
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
        $data = User::find($id);

        $data->delete();

        return response()->json([
            'message' => 'petugas berhasil di hapus'
        ]);
    }
}
