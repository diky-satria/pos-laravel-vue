<h5 style="text-align:center;">Laporan tanggal {{ date('j M Y', strtotime($awal_tanggal)) }} sampai {{ date('j M Y', strtotime($sampai_tanggal)) }}</h5>
<table>
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
   <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $d->kode }}</td>
            <td>{{ date('j M Y', strtotime($d->tgl)) }}</td>
            <td>{{ $d->pelanggan ? $d->pelanggan->nama : 'Umum' }}</td>
            <td>{{ $d->petugas->name }}</td>
            <td>{{ $d->transaksi_barangs()->sum('qty') }}</td>
            <td>Berhasil</td>
        </tr>
    @endforeach
   </tbody>
</table>