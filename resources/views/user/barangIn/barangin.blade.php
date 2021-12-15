@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md-8">
                <h5>Barang In</h5>
            </div>
            <div class="col-md-4 d-flex">
                <input type="date" class="form-control" id="tanggal">
                <button @click="tambah" class="btn btn-sm btn-primary float-end ms-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <table id="table" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Petugas</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Penambahan</th>
                                    <th>Keterangan</th>
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
                    <h5 class="modal-title" v-if="!editMode" id="exampleModalLabel">Tambah</h5>
                    <h5 class="modal-title" v-if="editMode" id="exampleModalLabel">Edit</h5>
                    <button type="button" @click="tutup_modal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action=""  @submit.prevent="!editMode ? actionTambah() : actionEdit(formEdit.id)" id="form">
                        <div class="form-group mb-3">
                            <label>Tanggal</label>
                            <!-- tambah -->
                            <input v-if="!editMode" type="text" name="tanggal" :value="tanggal" class="form-control" readonly>
                            <!-- edit -->
                            <input v-if="editMode" v-model="formEdit.tanggal" type="text" name="tanggal" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label>Barang</label>
                            <!-- tambah -->
                            <select v-if="!editMode" class="form-control" name="barang" id="barang">
                                <option value="">----</option>
                                <option v-for="b in barangs" :value="b.id">@{{ b.nama }}</option>
                            </select>
                            <!-- edit -->
                            <select v-if="editMode" class="form-control" name="barang" v-model="formEdit.id_barang">
                                <option v-for="b in barangs" :value="b.id" :selected="b.id == formEdit.id_barang">@{{ b.nama }}</option>
                            </select>

                            <div class="form-text text-danger" v-if="errors['barang']">@{{ errors['barang'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Penambahan</label>
                            <!-- tambah -->
                            <input v-if="!editMode" type="text" name="penambahan" class="form-control" id="penambahan">
                            <!-- edit -->
                            <input v-if="editMode" type="text" name="penambahan" class="form-control" v-model="formEdit.penambahan">

                            <div class="form-text text-danger" v-if="errors['penambahan']">@{{ errors['penambahan'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Keterangan</label>
                            <!-- tambah -->
                            <textarea v-if="!editMode" class="form-control" name="keterangan" rows="4" id="keterangan"></textarea>
                            <!-- edit -->
                            <textarea v-if="editMode" class="form-control" name="keterangan" rows="4" :value="formEdit.keterangan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-submit">
                            <div>
                                <span v-if="!editMode">Tambah</span>
                                <span v-if="editMode">Edit</span>
                            </div>
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
    var url = '{{ url("barang-in") }}'

    var component = new Vue({
        el: '#component',
        data: {
            editMode: false,
            barangs: [],
            tanggal: '',
            errors: [],
            load: false,
            formEdit: {},
            url: url
        },
        mounted(){
            this.ambilData()
            this.ambilDataBarang()
        },
        methods: {
            ambilData(){
                this.table = $('#table').DataTable({
                        processing: true,
                        language: {
                            url: '{{ asset("template/json/datatables-indonesia.json") }}'
                        },
                        ajax: {
                            type: 'GET',
                            url: this.url
                        },
                        columns   : [
                            {
                                "data" : null, "sortable" : false,
                                render: function(data, type, row, meta){
                                    return meta.row + meta.settings._iDisplayStart + 1
                                }
                            },
                            {data: 'tanggal', name: 'tanggal'},
                            {data: 'petugas', name: 'petugas'},
                            {data: 'kode', name: 'kode'},
                            {data: 'nama', name: 'nama'},
                            {data: 'penambahan', name: 'penambahan'},
                            {data: 'keterangan', name: 'keterangan'},
                            {data: 'action', name: 'action'}
                        ]
                })
            },
            async ambilDataBarang(){
                let response = await axios.get('ambil_data_barang')
                this.barangs = response.data.data,
                this.tanggal = response.data.tanggal
            },
            tutup_modal(){
                this.errors = []
                $('#barang').val('')
                $('#penambahan').val('')
                $('#keterangan').val('')
            },
            tambah(){
                this.editMode = false
            },
            async actionTambah(){
                let btn = document.getElementById('btn-submit')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    await axios.post('tambah_barang_in', new FormData($('#form')[0]))
                    $('#table').DataTable().ajax.reload()
                    $('.btn-close').click()
    
                    Toast.fire({
                        icon: 'success',
                        title: 'Barang In berhasil ditambahkan'
                    })

                    this.load = false
                    btn.removeAttribute('disabled', false)
                }catch(e){
                    this.errors = e.response.data.errors
                    this.load = false
                    btn.removeAttribute('disabled', false)
                }
            },
            async edit(id){
                this.editMode = true
                let response = await axios.get('detail_barang_in/'+id)
                this.formEdit = response.data.data
            },
            async actionEdit(id){
                let btn = document.getElementById('btn-submit')
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    await axios.post('barang_in/'+id, new FormData($('#form')[0]))
                    $('#table').DataTable().ajax.reload()
                    $('.btn-close').click()
        
                    Toast.fire({
                        icon: 'success',
                        title: 'Barang In berhasil di edit'
                    })

                    this.load = false
                    btn.removeAttribute('disabled', false)
                }catch(e){
                    this.errors = e.response.data.errors
                    this.load = false
                    btn.removeAttribute('disabled', false)
                }
            },
            hapus(id){
                Swal.fire({
                    title: 'Lanjutkan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: 'black'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('barang_in/'+ id)

                        $('#table').DataTable().ajax.reload()

                        Toast.fire({
                            icon: 'success',
                            title: 'Barang In berhasil di hapus'
                        })
                    }
                })
            },
            konfirmasi(id){
                Swal.fire({
                    title: 'Lanjutkan',
                    text: "Stok barang ini akan bertambah sesuai jumlah penambahan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi',
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: 'black'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.patch('barang_in/'+ id)

                        $('#table').DataTable().ajax.reload()

                        Toast.fire({
                            icon: 'success',
                            title: 'Stok telah bertambah'
                        })
                    }
                })
            }
        }
    })

    $('#tanggal').on('change', function(){
        tanggal = $('#tanggal').val()

        if(tanggal == ''){
            component.table.ajax.url(url).load()
        }else{
            component.table.ajax.url(url+'?tanggal='+tanggal).load()
        }
    })
</script>
@endpush