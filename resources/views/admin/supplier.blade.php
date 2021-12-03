@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col">
                <h5>Supplier</h5>
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
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
                            <label>Nama</label>
                            <!-- tambah -->
                            <input type="text" v-if="editMode == false" v-model="form.nama" class="form-control">
                            <!-- edit -->
                            <input type="text" v-if="editMode == true" v-model="formEdit.nama" class="form-control">

                            <div class="form-text text-danger" v-if="errors['nama']">@{{ errors['nama'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <!-- tambah -->
                            <input type="text" v-if="editMode == false" v-model="form.email" class="form-control">
                            <!-- edit -->
                            <input type="text" v-if="editMode == true" v-model="formEdit.email" class="form-control" readonly>

                            <div class="form-text text-danger" v-if="errors['email']">@{{ errors['email'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Telepon</label>
                            <!-- tambah -->
                            <input type="text" v-if="editMode == false" v-model="form.telp" class="form-control">
                            <!-- edit -->
                            <input type="text" v-if="editMode == true" v-model="formEdit.telp" class="form-control">

                            <div class="form-text text-danger" v-if="errors['telp']">@{{ errors['telp'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat</label>
                            <!-- tambah -->
                            <textarea class="form-control" rows="4" v-if="editMode == false" v-model="form.alamat"></textarea>
                            <!-- edit -->
                            <textarea class="form-control" rows="4" v-if="editMode == true" v-model="formEdit.alamat"></textarea>

                            <div class="form-text text-danger" v-if="errors['alamat']">@{{ errors['alamat'][0] }}</div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-tambah" v-if="editMode == false">
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
                        <button type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-edit" v-if="editMode == true">
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
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('template/js/datatables.js') }}"></script>
<script>
    var component = new Vue({
        el: '#component',
        data: {
            editMode: false,
            form: {
                nama: '',
                email: '',
                telp: '',
                alamat: ''
            },
            errors: [],
            load: false,
            formEdit: {}
        },
        mounted(){
            this.ambilData()
        },
        methods: {
            ambilData(){
                $('#table').DataTable({
                    serverSide: true,
                    responsive: true,
                    ajax      : {
                        type: 'GET',
                        url : 'data/supplier'
                    },
                    columns   : [
                        {
                            "data" : null, "sortable" : false,
                            render: function(data, type, row, meta){
                                return meta.row + meta.settings._iDisplayStart + 1
                            }
                        },
                        {data: 'nama', name: 'nama'},
                        {data: 'email', name: 'email'},
                        {data: 'telp', name: 'telp'},
                        {data: 'alamat', name: 'alamat'},
                        {data: 'action', name: 'action'}
                    ]    
                })
            },
            tutupModal(){
                this.errors = []
                this.form = {}
                this.formEdit = {}
            },
            tambah(){
                this.editMode = false
            },
            async edit(id){
                this.editMode = true
                let response = await axios.get('data/supplier/'+ id)
                this.formEdit = response.data.data
            },
            async actionTambah(){
                let btn = document.getElementById('btn-tambah')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    await axios.post('data/supplier', this.form)
                    $('.btn-close').click()
                    this.tutupModal()
                    $('#table').DataTable().ajax.reload()

                    Toast.fire({
                        icon: 'success',
                        title: 'Supplier berhasil ditambahkan'
                    })

                    btn.removeAttribute('disabled', false)
                    this.load = false
                }catch(e){
                    this.errors = e.response.data.errors
                    btn.removeAttribute('disabled', false)
                    this.load = false
                }
            },
            async actionEdit(id){
                let btn = document.getElementById('btn-edit')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    await axios.patch('data/supplier/'+ id, this.formEdit)
                    $('#table').DataTable().ajax.reload()
                    $('.btn-close').click()
                    this.tutupModal()

                    Toast.fire({
                        icon: 'success',
                        title: 'Supplier berhasil di edit'
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
                    text: "ingin menghapus supplier ini",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: 'black'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('data/supplier/'+ id)

                        $('#table').DataTable().ajax.reload()

                        Toast.fire({
                            icon: 'success',
                            title: 'Supplier berhasil di hapus'
                        })
                    }
                })
            }
        }
    })
</script>
@endpush
