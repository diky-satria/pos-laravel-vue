@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md-8">
                <h5>Barang Out</h5>
            </div>
            <div class="col-md-4 d-flex">
                <input type="date" class="form-control" id="tanggal">
                <button class="btn btn-sm btn-primary float-end ms-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
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
                                    <th>Pengurangan</th>
                                    <th>Keterangan</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                    <button type="button" @click="tutupModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" @submit.prevent="actionTambah" id="form">
                        <div class="form-group mb-3">
                            <label>Tanggal</label>
                            <input type="text" name="tanggal" :value="tanggal" class="form-control" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label>Barang</label>
                            <select class="form-control" name="barang" id="barang">
                                <option value="">----</option>
                                <option v-for="b in barangs" :value="b.id">@{{ b.nama }}</option>
                            </select>
                            <div class="form-text text-danger" v-if="errors['barang']">@{{ errors['barang'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Pengurangan</label>
                            <input type="text" name="pengurangan" class="form-control" id="pengurangan">
                            <div class="form-text text-danger" v-if="errors['pengurangan']">@{{ errors['pengurangan'][0] }}</div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="4" id="keterangan" placeholder="Hilang/Rusak/Di curi"></textarea>
                            <div class="form-text text-danger" v-if="errors['keterangan']">@{{ errors['keterangan'][0] }}</div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-submit">
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
    var url = '{{ url("barang-out") }}'

    var component = new Vue({
        el: '#component',
        data: {
            barangs: [],
            tanggal: '',
            url: url,
            load: false,
            errors: []
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
                            {data: 'pengurangan', name: 'pengurangan'},
                            {data: 'keterangan', name: 'keterangan'}
                        ]
                })
            },
            async ambilDataBarang(){
                let response = await axios.get('barang_tidak_kosong')
                this.barangs = response.data.data,
                this.tanggal = response.data.tanggal
            },
            tutupModal(){
                this.errors = []
                $('#barang').val('')
                $('#pengurangan').val('')
                $('#keterangan').val('')
            },
            async actionTambah(){
                let btn = document.getElementById('btn-submit')
                this.errors = []
                try{
                    btn.setAttribute('disabled', true)
                    this.load = true

                    let response = await axios.post('barang_out', new FormData($('#form')[0]))

                    if(response.data.message == 'berhasil'){
                        $('#table').DataTable().ajax.reload()
                        $('.btn-close').click()
    
                        Toast.fire({
                            icon: 'success',
                            title: 'Barang Out ditambahkan, stok barang berkurang'
                        })
                    }else{
                        toastFail.fire({
                            icon: 'error',
                            title: 'Jumlah pengurangan tidak boleh lebih besar dari stok barang nya'
                        })
                    }

                    btn.removeAttribute('disabled', false)
                    this.load = false
                }catch(e){
                    this.errors = e.response.data.errors
                    btn.removeAttribute('disabled', false)
                    this.load = false
                }
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