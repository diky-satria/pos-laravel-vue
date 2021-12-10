@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md">
                <h5>Laporan Harian</h5>
            </div>
            <div class="col-md">
                <div class="float-end">
                    <a href="{{ url('export-harian-pdf') }}" target="_blank" class="btn btn-sm btn-primary">PDF</a>
                    <a href="{{ url('export-harian-excel') }}" class="btn btn-sm btn-success">EXCEL</a>
                </div>
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
<script>
    $('#table').DataTable({
        processing: true,
        language: {
            url: '{{ asset("template/json/datatables-indonesia.json") }}'
        },
        ajax: {
            type: 'GET',
            url: 'laporan-harian'
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
</script>
@endpush