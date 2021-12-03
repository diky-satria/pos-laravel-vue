@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col">
                <h5>Barang</h5>
            </div>
            <div class="col">
                <button @click="tambah" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card box-sd">
                    <div class="card-body">
                        <table id="table" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal -->
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" v-if="editMode == false">Tambah</h5>
                    <h5 class="modal-title" id="exampleModalLabel" v-if="editMode == true">Edit</h5>
                    <button type="button" @click="tutupModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" @submit.prevent="editMode == true ? actionEdit(formEdit.id) : actionTambah()">
                        <div class="form-group mb-3">
                            <label>Kode</label>
                            <!-- tambah -->
                            <input type="text" v-if="editMode == false" v-model="form.kode" class="form-control">
                            <!-- edit -->
                            <input type="text" v-if="editMode == true" v-model="formEdit.kode" class="form-control" readonly>

                            <div class="form-text text-danger" v-if="errors['kode']">@{{ errors['kode'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nama</label>
                            <!-- tambah -->
                            <input type="text" v-if="editMode == false" v-model="form.nama" class="form-control">
                            <!-- edit -->
                            <input type="text" v-if="editMode == true" v-model="formEdit.nama" class="form-control">

                            <div class="form-text text-danger" v-if="errors['nama']">@{{ errors['nama'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Kategori</label>
                            <!-- tambah -->
                            <select class="form-control" v-if="editMode == false" v-model="form.id_kategori">
                                <option v-for="k in kategoris" :value="k.id">@{{ k.nama }}</option>
                            </select>
                            <!-- edit -->
                            <select class="form-control" v-if="editMode == true" v-model="formEdit.id_kategori">
                                <option v-for="k in kategoris" :value="k.id" :selected="formEdit.id_kategori == k.id">@{{ k.nama }}</option>
                            </select>

                            <div class="form-text text-danger" v-if="errors['id_kategori']">@{{ errors['id_kategori'][0] }}</div>
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <div class="form-group mb-3">
                                    <label>Stok</label>
                                    <!-- tambah -->
                                    <input type="text" v-if="editMode == false" v-model="form.stok" class="form-control">
                                    <!-- edit -->
                                    <input type="text" v-if="editMode == true" v-model="formEdit.stok" class="form-control">

                                    <div class="form-text text-danger" v-if="errors['stok']">@{{ errors['stok'][0] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group mb-3">
                                    <label>Harga</label>
                                    <!-- tambah -->
                                    <input type="text" v-if="editMode == false" v-model="form.harga" class="form-control">
                                    <!-- edit -->
                                    <input type="text" v-if="editMode == true" v-model="formEdit.harga" class="form-control">

                                    <div class="form-text text-danger" v-if="errors['harga']">@{{ errors['harga'][0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Gambar</label>
                            <!-- tambah -->
                            <input type="file" v-if="" @change="ambilGambar" class="form-control">

                            <div class="form-text text-danger" v-if="errors['gambar']">@{{ errors['gambar'][0] }}</div>
                        </div>
                        <button v-if="editMode == false" type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-tambah">
                            <div>Tambah</div>
                            <div>
                                <svg v-if="load" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(255, 255, 255, 0); display: block; shape-rendering: auto;" width="24px" height="22px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                    <g>
                                        <path d="M50 15A35 35 0 1 0 74.74873734152916 25.251262658470843" fill="none" stroke="#ffffff" stroke-width="12"></path>
                                        <path d="M49 3L49 27L61 15L49 3" fill="#ffffff"></path>
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                    </g>
                                </svg>
                            </div>
                        </button>
                        <button v-if="editMode == true" type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-edit">
                            <div>Edit</div>
                            <div>
                                <svg v-if="load" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(255, 255, 255, 0); display: block; shape-rendering: auto;" width="24px" height="22px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                    <g>
                                        <path d="M50 15A35 35 0 1 0 74.74873734152916 25.251262658470843" fill="none" stroke="#ffffff" stroke-width="12"></path>
                                        <path d="M49 3L49 27L61 15L49 3" fill="#ffffff"></path>
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                    </g>
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('template/js/datatables.js') }}"></script>
<script>
    var component = new Vue({
        el: '#component',
        data: {
            editMode: false,
            load: false,
            kategoris: [],
            form: {
                kode: '',
                nama: '',
                id_kategori: '',
                stok: '',
                harga: '',
                gambar: ''
            },
            fileGambar: '',
            errors: [],
            formEdit: {},
        },
        mounted(){
            this.ambilData()
            this.data_select()
        },
        methods: {
            async data_select(){
                let response = await axios.get('data/data_select')
                this.kategoris = response.data.data
            },
            ambilData(){
                this.table = $('#table').DataTable({
                    serverSide: true,
                    responsive: true,
                    ajax      : {
                        type: 'GET',
                        url : 'data/barang'
                    },
                    columns   : [
                        {
                            "data" : null, "sortable" : false,
                            render: function(data, type, row, meta){
                                return meta.row + meta.settings._iDisplayStart + 1
                            }
                        },
                        {data: 'gambar', name: 'gambar'},
                        {data: 'kode', name: 'kode'},
                        {data: 'nama', name: 'nama'},
                        {data: 'kategori', name: 'kategori'},
                        {data: 'stok', name: 'stok'},
                        {data: 'harga', name: 'harga'},
                        {data: 'action', name: 'action'}
                    ]    
                })
            },
            tutupModal(){
                this.form = {
                    kode: '',
                    nama: '',
                    id_kategori: '',
                    stok: '',
                    harga: '',
                    gambar: ''
                }
                this.errors = []
                this.fileGambar = ''
                this.formEdit = {}
            },
            tambah(){
                this.editMode = false
            },
            ambilGambar(e){
                if(e.target.files.length === 0){
                    this.fileGambar = ''
                    return
                }
                this.fileGambar = e.target.files[0]
            },
            async actionTambah(){
                let btn = document.getElementById('btn-tambah')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    const data = new FormData()
                    data.append('kode', this.form.kode)
                    data.append('nama', this.form.nama)
                    data.append('id_kategori', this.form.id_kategori)
                    data.append('stok', this.form.stok)
                    data.append('harga', this.form.harga)
                    data.append('gambar', this.fileGambar)
    
                    let response = await axios.post('data/barang', data)
                    $('#table').DataTable().ajax.reload()
                    $('.btn-close').click()
                    this.data_select()

                    Toast.fire({
                        icon: 'success',
                        title: 'Barang berhasil ditambahkan'
                    })

                    btn.removeAttribute('disabled', false)
                    this.load = false
                }catch(e){
                    this.errors = e.response.data.errors
                    btn.removeAttribute('disabled', false)
                    this.load = false
                }
            },
            async edit(id){
                this.editMode = true
                let response = await axios.get('data/barang/'+ id)
                this.formEdit = response.data.data
            },
            async actionEdit(id){
                let btn = document.getElementById('btn-edit')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    const data = new FormData()
                    data.append('nama', this.formEdit.nama)
                    data.append('id_kategori', this.formEdit.id_kategori)
                    data.append('stok', this.formEdit.stok)
                    data.append('harga', this.formEdit.harga)
                    data.append('gambar', this.fileGambar)

                    await axios.post('data/barang/'+ id, data)
                    $('#table').DataTable().ajax.reload()
                    $('.btn-close').click()
                    this.data_select()

                    Toast.fire({
                        icon: 'success',
                        title: 'Barang berhasil di edit'
                    })

                    btn.removeAttribute('disabled', false)
                    this.load = false
                }catch(e){
                    this.errors = e.response.data.errors
                    btn.removeAttribute('disabled', false)
                    this.load = false
                }
            },
            hapus(id){
                Swal.fire({
                    title: 'Apa kamu yakin?',
                    text: "ingin menghapus barang ini",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: 'black'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('data/barang/'+ id)

                        $('#table').DataTable().ajax.reload()

                        Toast.fire({
                            icon: 'success',
                            title: 'Barang berhasil di hapus'
                        })
                    }
                })
            }
        }
    })
</script>
@endpush