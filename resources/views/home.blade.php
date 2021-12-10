@extends('layouts.template')

@section('content')
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col">
            <h5>Dashboard</h5> 
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body py-0 mt-2">
                    <div>Barang</div>
                    <h4>{{ $barang }}</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ url('barangs') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body py-0 mt-2">
                    <div>Supplier</div>
                    <h4>{{ $supplier }}</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ url('supplier') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body py-0 mt-2">
                    <div>Pelanggan</div>
                    <h4>{{ $pelanggan }}</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ url('pelanggan') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body py-0 mt-2">
                    <div>Penjualan Gagal</div>
                    <h4>{{ $transaksi_gagal }}</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ url('penjualan_gagal') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-5">
            <div class="chart-container" style="width:30vw">
                <canvas id="doughnut"></canvas>
            </div>
        </div>
        <div class="col-md-7">
            <div class="chart-container" style="width:100%;height:100%;">
                <canvas id="line"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('template/js/chart.js') }}"></script>
<script>

    var label_doughnut = '{!! json_encode($label_doughnut) !!}'
    var data_doughnut = '{!! json_encode($data_doughnut) !!}'
    var data_line = '{!! json_encode($data_line) !!}'
    
    // chart doughnut
    const ctx = document.getElementById('doughnut').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: JSON.parse(label_doughnut),
            datasets: [{
                label: 'My First Dataset',
                data: JSON.parse(data_doughnut),
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(100, 90, 206)',
                'rgb(50, 170, 100)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: '5 barang paling sering di beli',
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            },
            responsive: true
        }
    })

    // line chart
    const ctx2 = document.getElementById('line').getContext('2d');
    const myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'Sepetember', 'Oktober', 'November', 'Desember'],
            datasets: [{
                label: 'Penjualan',
                data: JSON.parse(data_line),
                borderColor: 'red',
                tension: 0.2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Riwayat penjualan',
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            },
            responsive: true
        }
    })

</script>
@endpush