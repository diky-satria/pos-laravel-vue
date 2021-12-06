@extends('layouts.template')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/datatables.css') }}"/>
<style>
    .fce{
        padding:1px;
    }
    .th-edited{
        min-width:300px;
    }
    .tr-edited{
        border:0px solid transparent;
    }
</style>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md">
                <h5>Riwayat</h5>
            </div>
            <div class="col-md">
                <div class="row">
                    <div class="col-md">
                        <select class="form-control" id="status">
                            <option value="3">-- Status --</option>
                            <option value="2">Berhasil</option>
                            <option value="1">Gagal</option>
                        </select>
                    </div>
                    <div class="col-md">
                        <input type="date" id="tanggal" class="form-control">
                    </div>
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
                                    <th>Status</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">

                    <div class="row my-4">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <input type="text" :value="datas.kode" class="form-control fce" name="kode" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <input type="text" :value="datas.tgl" class="form-control fce" name="tgl" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <table class="table table-sm">
                                <tr>
                                    <th>No</th>
                                    <th class="th-edited">Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="text-end">Sub-Total</th>
                                </tr>
                                <tr v-for="(d, index) in details">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ d.nama }}</td>
                                    <td>@{{ format_rupiah(d.harga, 'Rp. ') }}</td>
                                    <td>@{{ d.qty }}</td>
                                    <td class="text-end">@{{ format_rupiah(d.harga * d.qty, 'Rp. ') }}</td>
                                </tr>
                
                                <!-- hitung -->
                                <tr class="tr-edited">
                                    <th colspan="4" class="text-end"><div class="mt-2">Total</div></th>
                                    <td>
                                        <input id="total" :value="datas.total" type="text" class="form-control fce text-end mt-2"  readonly>
                                    </td>
                                </tr>
                                <tr class="tr-edited">
                                    <th colspan="4" class="text-end">Tunai</th>
                                    <td>
                                        <input id="tunai" :value="datas.tunai" type="text" class="form-control fce text-end">
                                    </td>
                                </tr>
                                <tr class="tr-edited">
                                    <th colspan="4" class="text-end">Kembali</th>
                                    <td>
                                        <input id="kembalian" :value="datas.kembalian" type="text" class="form-control fce text-end" readonly>
                                    </td>
                                </tr>
                                <tr class="tr-edited">
                                    <th colspan="4" class="text-end">Pelanggan</th>
                                    <td><input id="kembalian" :value="datas.nama_pelanggan" type="text" class="form-control fce text-end" readonly></td>
                                </tr>

                                <!-- hitung -->
            
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button @click="cetakStruk" class="btn btn-sm btn-success float-end">Cetak</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{ asset('template/js/datatables.js') }}"></script>
<script src="{{ asset('template/js/html2pdf.js') }}"></script>
<script>
    var url = '{{ url("riwayat") }}'

    var component = new Vue({
        el: '#component',
        data:{
            datas: [],
            details: [],
            url: url
        },
        mounted(){
            this.ambilRiwayat()
        },
        methods: {
            ambilRiwayat(){
                this.table = $('#table').DataTable({
                    serveSide: true,
                    responsive: true,
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
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action'}
                    ]
                })
            },
            async detail(id){
                let response = await axios.get('riwayat/'+id)
                this.datas = response.data.data
                this.details = response.data.detail
            },
            format_rupiah(angka, prefix){
                var number_string = angka.toString()
                var split             = number_string.split(',')
                var sisa              = split[0].length % 3
                var rupiah            = split[0].substr(0, sisa)
                var ribuan            = split[0].substr(sisa).match(/\d{3}/gi)

                if(ribuan){
                    var separator = sisa ? '.' : ''
                    rupiah   += separator + ribuan.join('.')
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '')
            },
            cetakStruk(){
                var element = document.getElementById('modal-body');
                var opt = {
                    margin:       [20, 5, 0, 5],
                    filename:     'struk.pdf'
                };

                html2pdf().set(opt).from(element).save()
            },
        }
    })

    // data berdasarkan status
    $('#status').on('change', function(){
        sts = $('#status').val()

        if(sts == 3){
            component.table.ajax.url(url).load()
        }else{
            component.table.ajax.url(url + '?status='+ sts).load()
        }
    })

    // data berdasarkan tanggal
    $('#tanggal').on('change', function(){
        tgl = $('#tanggal').val()

        if(tgl == ''){
            component.table.ajax.url(url).load()
        }else{
            component.table.ajax.url(url + '?tgl='+ tgl).load()
        }
    })
</script>
@endpush