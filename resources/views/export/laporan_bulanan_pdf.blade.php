<div class="laporan_harian_pdf">
    <h3 style="text-align:center;">Laporan tanggal {{ date('j M Y', strtotime($tgl_awal)) }} sampai {{ date('j M Y', strtotime($tgl_akhir)) }}</h3>
    <table style="width:100%;border-collapse: collapse;">
       <thead>
          <tr style="background-color:rgb(0, 119, 255);color:white;">
            <th style="padding:7px 0;">No</th>
            <th style="padding:7px 0;">Kode</th>
            <th style="padding:7px 0;">Tanggal</th>
            <th style="padding:7px 0;">Pelanggan</th>
            <th style="padding:7px 0;">Petugas</th>
            <th style="padding:7px 0;">Jumlah</th>
            <th style="padding:7px 0;">Status</th>
          </tr>
       </thead>
       <tbody>
       @foreach($data as $d)
          <tr>
            <td style="border-bottom:1px solid black;text-align:center;padding:4px 0;">{{ $loop->iteration }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">{{ $d->kode }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">{{ date('j M Y', strtotime($d->tgl)) }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">{{ $d->pelanggan ? $d->pelanggan->nama : 'Umum' }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">{{ $d->petugas->name }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">{{ $d->transaksi_barangs()->sum('qty') }}</td>
            <td style="border-bottom:1px solid black;padding:4px 0;">Berhasil</td>
          </tr>
       @endforeach
       </tbody>
    </table>
</div>