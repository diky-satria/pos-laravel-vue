@extends('layouts.template')

@push('css')
<link rel="stylesheet" href="{{ asset('template/css/select2.css') }}"/>
<style>
    .fce{
        padding:1px;
    }
    .th-edited{
        min-width:250px;
    }
    .tr-edited{
        border:0px solid transparent;
    }
    img{
        transform: scale(0.7);
    }
</style>
@endpush

@section('content')
<div id="component">
    <div class="container-fluid px-4">

        <form action="" @submit.prevent="tambahTransaksi" id="form-tambah-transaksi">
            <div class="row my-4">
                <div class="col-md-2">

                    <div class="form-group mb-3">
                        <input type="text" class="form-control fce" name="kode" :value="kode" readonly>
                        <div class="form-text text-danger" v-if="errors['kode']">@{{ errors['kode'][0] }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control fce" name="tgl" :value="tgl" readonly>
                        <div class="form-text text-danger" v-if="errors['tgl']">@{{ errors['tgl'][0] }}</div>
                    </div>
                </div>
                <div class="col-md-2" id="cetak1">
                    <div class="form-group mb-3">
                        <select class="form-control js-example-basic-single" style="width:100%;" name="id_barang" id="id_barang">
                            <option value="">----</option>
                            <option v-for="b in barangs" :value="b.id">@{{ b.nama }}</option>
                        </select>
                        <div class="form-text text-danger" v-if="errors['id_barang']">@{{ errors['id_barang'][0] }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary d-flex" id="btn-tambahkan">
                        <div>Tambahkan</div>
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
                </div>            
            </div>
        </form>

            <div class="row" :class="cek > 0 ? 'd-none' : 'd-block'">
                <div class="col-md text-center">
                    <div>
                        <img src="{{ asset('template_gambar/dadu.png') }}" class="mt-5">
                        <h6>Halaman Penjualan</h6>
                        <h6>Anda belum memilih barang</h6>
                    </div>
                </div>                                      
            </div>
            <div class="row" :class="cek > 0 ? 'd-block' : 'd-none'">
                <div class="col-md">
                    <table class="table table-sm">
                        <tr>
                            <th>No</th>
                            <th class="th-edited">Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th class="text-end">Sub-Total</th>
                        </tr>
                        <tr v-for="(d, index) in detail">
                            <td>@{{ index + 1 }}</td>
                            <td>@{{ d.nama }}</td>
                            <td>@{{ format_rupiah(d.harga, 'Rp. ') }}</td>
                            <td>
                                <button class="btn btn-sm btn-success cetak" id="cetak3" title="Kurang" @click="kurang(d.id, d.id_pivot)"><i class="fas fa-minus"></i></button>
                                @{{ d.qty }}
                                <button class="btn btn-sm btn-success cetak" id="cetak4" title="Tambah" @click="tambah(d.id, d.id_pivot)"><i class="fas fa-plus"></i></button>
                                <button class="btn btn-sm btn-danger cetak" id="cetak5" title="Hapus" @click="hapus(d.id, d.id_pivot, d.qty)"><i class="fas fa-trash"></i></button>
                            </td>
                            <td class="text-end">@{{ format_rupiah((d.harga * d.qty), 'Rp. ') }}</td>
                        </tr>
        
                        <!-- hitung -->
                        <tr class="tr-edited">
                            <th colspan="4"></th>
                            <td>
                                
                                <form action="" @submit.prevent="updateStatusTransaksi(kode)" id="formUpdateStatusTransaksi">

                                    <div class="row mt-3">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"><div class="float-end fw-bold">Total</div></label>
                                        <div class="col-sm-9">
                                            <input id="total" type="text" class="form-control fce text-end"  v-model="total" name="total" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"><div class="float-end fw-bold">Tunai</div></label>
                                        <div class="col-sm-9">
                                            <input id="tunai" name="tunai" type="number" v-model="tunai" class="form-control fce text-end">
                                            <div class="form-text text-danger" v-if="error_pelanggan['tunai']">@{{ error_pelanggan['tunai'][0] }}</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"><div class="float-end fw-bold">Kembalian</div></label>
                                        <div class="col-sm-9">
                                            <input id="kembalian" name="kembalian" v-model="hitung" type="text" class="form-control fce text-end" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label"><div class="float-end fw-bold">Pelanggan</div></label>
                                        <div class="col-sm-9">
                                            <select name="id_pelanggan" class="form-control fce js-example-basic-single2" style="width:100%;">
                                                <option value="">----</option>
                                                <option :value="umum">Umum</option>
                                                <option v-for="p in pelanggans" :value="p.id">@{{ p.nama }}</option>
                                            </select>
                                            <div class="form-text text-danger" v-if="error_pelanggan['id_pelanggan']">@{{ error_pelanggan['id_pelanggan'][0] }}</div>
                                        </div>
                                    </div>
                                    <div class="row" id="cetak6">
                                        <div class="col-3"></div>
                                        <div class="col-9">
                                            <div class="d-grid gap-1 mt-2" :class="!print ? 'd-block' : 'd-none'">
                                                <button type="submit" class="btn btn-sm btn-primary d-flex justify-content-center" id="btn-bayar">
                                                    <div>Bayar</div>
                                                    <div>
                                                        <svg v-if="loadBayar" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(255, 255, 255, 0); display: block; shape-rendering: auto;" width="24px" height="22px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                                            <g>
                                                                <path d="M50 15A35 35 0 1 0 74.74873734152916 25.251262658470843" fill="none" stroke="#ffffff" stroke-width="12"></path>
                                                                <path d="M49 3L49 27L61 15L49 3" fill="#ffffff"></path>
                                                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="d-grid gap-2 mt-2" :class="print ? 'd-block' : 'd-none'">
                                                <a :href="link_print" target="_blank" class="btn btn-sm btn-success" id="btn-print-lanjutkan">Print</a>
                                                <a href="penjualan" class="btn btn-sm btn-primary" id="btn-print-lanjutkan">Lanjutkan</a>
                                            </div>
                                        </div>
                                    </div>

                                </form>    
                                
                            </td>
                        </tr>
                        
                        <!-- hitung -->
    
                    </table>
                </div>
            </div>
        
    </div>

    <!-- overlay -->
    <div class="overlay-custom" :class="overlay ? 'd-block' : 'd-none'">      
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('template/js/select2.js') }}"></script>
<script>
    
    $(document).ready(function(){
        $('.js-example-basic-single').select2()
        $('.js-example-basic-single2').select2()
    })

    var component  = new Vue({
        el: '#component',
        data:{
            barangs: [],
            pelanggans: [],
            kode: '',
            tgl: '',
            errors: [],
            load: false,
            detail: [],
            cek: '',
            total: 0,
            tunai: 0,
            kembalian: 0,
            null: null,
            error_pelanggan: [],
            umum: 'umum',
            loadBayar: false,
            print: false,
            overlay: false,
            link_print: ''
        },
        mounted(){
            this.ambilDataKodeTgl()
            this.detailDataTransaksi()
            this.ambilDataBarang()
            this.ambilDataPelanggan()
        },
        methods: {
            async ambilDataKodeTgl(){
                let response = await axios.get('kode_tgl')
                this.kode = response.data.kode
                this.tgl = response.data.tgl
                this.link_print = 'print/'+ response.data.kode
            },
            async ambilDataBarang(){
                let response = await axios.get('select_barang')
                this.barangs = response.data.barang
            },
            async ambilDataPelanggan(){
                let response = await axios.get('select_pelanggan')
                this.pelanggans = response.data.pelanggan
            },
            async tambahTransaksi(){
                let btn = document.getElementById('btn-tambahkan')
                try{
                    this.load = true
                    btn.setAttribute('disabled', true)

                    await axios.post('tambah_transaksi', new FormData($('#form-tambah-transaksi')[0]))
                    this.errors = []

                    this.barangs = []
                    this.ambilDataBarang()
                    this.detailDataTransaksi()
 
                    btn.removeAttribute('disabled', false)
                    this.load = false
                }catch(e){
                    this.errors = e.response.data.errors
                    this.load = false
                    btn.removeAttribute('disabled', false)
                }
            },
            async detailDataTransaksi(){
                let response = await axios.get('detail_data_transaksi/'+ this.kode)
                this.detail = response.data.data
                this.cek = response.data.cek,
                this.total = response.data.total
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
            async hapus(id_barang, id_pivot, qty){
                await axios.delete('hapus_data_transaksi/'+id_barang+'/'+id_pivot+'/'+qty)

                this.barangs = []
                this.ambilDataBarang()
                this.detailDataTransaksi()
            },
            async tambah(id_barang, id_pivot){
                let response = await axios.patch('tambah_data_transaksi/'+id_barang+'/'+id_pivot)
                if(response.data.cek == 'gagal'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Stok barang ini sudah habis'
                    })
                }else{
                    this.barangs = []
                    this.ambilDataBarang()
                    this.detailDataTransaksi()
                }
            },
            async kurang(id_barang, id_pivot){
                let response = await axios.patch('kurang_data_transaksi/'+id_barang+'/'+id_pivot)
                if(response.data.cek == 'gagal'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlahnya tidak bisa dikurangi lagi'
                    })
                }else{
                    this.barangs = []
                    this.ambilDataBarang()
                    this.detailDataTransaksi()
                }
            },
            cetakStruk(){
                let cetak1 = document.getElementById('cetak1')
                cetak1.style.display = 'none'
                let cetak2 = document.getElementById('cetak2')
                cetak2.style.display = 'none'

                let cetak3 = document.querySelectorAll('#cetak3')
                for(let i=0; i<cetak3.length; i++){
                    cetak3[i].style.display = 'none'
                }
                let cetak4 = document.querySelectorAll('#cetak4')
                for(let j=0; j<cetak4.length; j++){
                    cetak4[j].style.display = 'none'
                }
                let cetak5 = document.querySelectorAll('#cetak5')
                for(let k=0; k<cetak5.length; k++){
                    cetak5[k].style.display = 'none'
                }

                let cetak6 = document.getElementById('cetak6')
                cetak6.style.display = 'none'

                var element = document.getElementById('component');
                var opt = {
                    margin:       [20, 0, 0, 0],
                    filename:     'struk.pdf'
                };

                html2pdf().set(opt).from(element).save()
            },
            async updateStatusTransaksi(kode){
                let btn = document.getElementById('btn-bayar')
                try{
                    this.error_pelanggan = []
                    btn.setAttribute('disabled', true)
                    this.loadBayar = true

                    if(this.tunai < this.total){
                        toastFail.fire({
                            icon: 'error',
                            title: 'Uang tunai belum cukup'
                        })
                        btn.removeAttribute('disabled', false)
                    }else{
                        await axios.post('update_status_transaksi/'+kode, new FormData($('#formUpdateStatusTransaksi')[0]))
    
                        Swal.fire(
                            'Pembayaran berhasil',
                            'Selanjutnya pilih "Print" atau "Lanjutkan"',
                            'success'
                        )
                        this.overlay = true
                        this.print = true
                        btn.removeAttribute('disabled', false)
                    }


                    this.loadBayar = false
                }catch(e){
                    this.error_pelanggan = e.response.data.errors
                    this.loadBayar = false
                    btn.removeAttribute('disabled', false)
                }
            }
        },
        computed: {
            hitung(){
                let hitung = parseInt(this.tunai) - parseInt(this.total)
                if(parseInt(this.tunai) < parseInt(this.total)){
                    return this.kembalian = 0
                }else{
                    return this.kembalian = hitung
                }
            }
        }
        
    })
</script>
@endpush
