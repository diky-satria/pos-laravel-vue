<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>print</title>
    <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
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
    </style>
    <script>
        window.print()
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md text-center">
                <h5>PT. SARANA PRIMA MANDIRI</h5>
                <h6>Jl. Tebet Utara, Jakarta Selatan</h6>
                <h6>021 - 123456</h6>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-3">
                <div class="form-group mb-3">
                    <input type="text" class="form-control fce" name="kode" value="{{ $transaksi->kode }}" readonly>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group mb-3">
                    <input type="text" class="form-control fce" name="tgl" value="{{ date('j M Y', strtotime($transaksi->tgl)) }}" readonly>
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
                    @foreach($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d['nama_barang'] }}</td>
                        <td>{{ format_rupiah($d['harga']) }}</td>
                        <td>{{ $d['jumlah'] }}</td>
                        <td class="text-end">{{ format_rupiah($d['harga'] * $d['jumlah']) }}</td>
                    </tr>
                    @endforeach
        
                    <!-- hitung -->
                    <tr class="tr-edited">
                        <th colspan="4" class="text-end"><div class="mt-2">Total</div></th>
                        <td>
                            <input id="total" type="text" class="form-control fce text-end mt-2" value="{{ format_rupiah($transaksi->total) }}" readonly>
                        </td>
                    </tr>
                    <tr class="tr-edited">
                        <th colspan="4" class="text-end">Tunai</th>
                        <td>
                            <input id="tunai" type="text" class="form-control fce text-end" value="{{ format_rupiah($transaksi->tunai) }}" readonly>
                        </td>
                    </tr>
                    <tr class="tr-edited">
                        <th colspan="4" class="text-end">Kembali</th>
                        <td>
                            <input id="kembalian" type="text" class="form-control fce text-end" value="{{ format_rupiah($transaksi->kembalian) }}" readonly>
                        </td>
                    </tr>
                    <tr class="tr-edited">
                        <th colspan="4" class="text-end">Pelanggan</th>
                        <td><input id="kembalian" type="text" class="form-control fce text-end" value="{{ $transaksi->id_pelanggan == null ? 'Umum' : $transaksi->pelanggan->nama }}" readonly></td>
                    </tr>
        
                    <!-- hitung -->
        
                </table>
            </div>
        </div>
    </div>   
</body>
</html>

