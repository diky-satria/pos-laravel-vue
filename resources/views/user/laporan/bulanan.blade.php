@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md">
                <h5>Laporan Bulanan</h5>
            </div>
            <div class="col-md">
                <div class="float-end">
                    <a :href="hrefPdf" target="_blank" class="btn btn-sm btn-primary">PDF</a>
                    <a :href="hrefExcel" class="btn btn-sm btn-success">EXCEL</a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md">
                <div class="float-end d-flex">
                    <input type="date" id="tgl_awal" name="tgl_awal" class="form-control">
                    <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control mx-1">
                    <button id="btn-cari" class="btn btn-sm btn-primary">Cari</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md text-center">
                <h5>Laporan tanggal <span class="text-danger">@{{ tgl_awal }}</span> sampai <span class="text-danger">@{{ tgl_sekarang }}</span></h5>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md">
                <div class="card box-sd">
                    <div class="card-body">
                        <table id="table" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Petugas</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('template/js/datatables.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/moment.js') }}"></script>
<script>
    var url = '{{ url("laporan-bulanan") }}'

    var component = new Vue({
        el: '#component',
        data: {
            tgl_awal: '',
            tgl_sekarang: '',
            url: url,
            hrefExcel: '',
            hrefPdf: ''
        },
        mounted(){
            this.ambilData()
            this.ambilTanggal()
            this.manipulasiHref()
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
                        {data: 'kode', name: 'kode'},
                        {data: 'tgl', name: 'tgl'},
                        {data: 'pelanggan', name: 'pelanggan'},
                        {data: 'petugas', name: 'petugas'},
                        {data: 'jumlah', name: 'jumlah'},
                        {data: 'status', name: 'status'}
                    ]
                })
            },
            async ambilTanggal(){
                let response = await axios.get('laporan-bulanan-data')
                this.tgl_awal = response.data.tgl_awal
                this.tgl_sekarang = response.data.tgl_sekarang
            },
            manipulasiHref(){
                this.hrefExcel = 'export-bulanan-excel'
                this.hrefPdf = 'export-bulanan-pdf'
            }
        }
    })

    $('#btn-cari').on('click', function(){
        tgl_awalan = $('#tgl_awal').val()
        tgl_akhiran = $('#tgl_akhir').val()

        if(tgl_awalan == '' || tgl_akhiran == ''){
            component.table.ajax.url(url).load()
            component.ambilTanggal()
            component.manipulasiHref()
        }else{
            component.table.ajax.url(url+'?tgl_awal='+ tgl_awalan + '&&tgl_akhir='+ tgl_akhiran).load()
            component.tgl_awal = moment(tgl_awalan).format('D MMM YYYY')
            component.tgl_sekarang = moment(tgl_akhiran).format('D MMM YYYY')
            component.hrefExcel = 'export-bulanan-excel?awal='+tgl_awalan+'&&akhir='+tgl_akhiran
            component.hrefPdf = 'export-bulanan-pdf?awal='+tgl_awalan+'&&akhir='+tgl_akhiran
        }
    })

</script>
@endpush