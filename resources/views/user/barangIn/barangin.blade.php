@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col">
                <h5>Barang In</h5>
            </div>
            <div class="col">
                <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group mb-3">
                            <label>Tanggal</label>
                            <!-- tambah -->
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Barang</label>
                            <!-- tambah -->
                            <select class="form-control">
                                <option value="">----</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Penambahan</label>
                            <!-- tambah -->
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Keterangan</label>
                            <!-- tambah -->
                            <textarea class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-end d-flex" id="btn-tambah">
                            <div>Tambah</div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(255, 255, 255, 0); display: block; shape-rendering: auto;" width="24px" height="22px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
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
    })
</script>
@endpush