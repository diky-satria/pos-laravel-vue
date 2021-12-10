
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>App</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('template/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('template/css/select2.css') }}">
        @stack('css')
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="{{ url('dashboard') }}">POS</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
            <div class="ms-auto">
               <a class="nav-link" href="{{ route('logout') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Logout"
                  onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                     <i style="color:rgba(255, 255, 255, 0.5);" class="fas fa-sign-out-alt ms-auto me-0 me-md-3 my-2 my-md-0"></i>
               </a>

               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
               </form>
            </div>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            
                            @if(Auth()->user()->hasRole('admin'))
                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link {{ request()->is('dashboard') || request()->is('home') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Master Data
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="supplier_active" class="nav-link {{ request()->is('supplier') ? 'active' : '' }}" href="{{ url('supplier') }}">
                                        Supplier
                                    </a>
                                    <a id="kategori_active" class="nav-link {{ request()->is('kategori') ? 'active' : '' }}" href="{{ url('kategori') }}">Kategori</a>
                                    <a id="barang_active" class="nav-link {{ request()->is('barangs') ? 'active' : '' }}" href="{{ url('barangs') }}">Barang</a>
                                </nav>
                            </div>
                            <a class="nav-link {{ request()->is('penjualan_gagal') ? 'active' : '' }}" href="{{ url('penjualan_gagal') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Penjualan Gagal
                            </a>
                            @endif

                            <div class="sb-sidenav-menu-heading">Petugas</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Transaksi
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="penjualan_active" class="nav-link {{ request()->is('penjualan') ? 'active' : '' }}" href="{{ url('penjualan') }}">Penjualan</a>
                                    <a id="riwayat_active" class="nav-link {{ request()->is('riwayat') ? 'active' : '' }}" href="{{ url('riwayat') }}">Riwayat</a>
                                    <a id="pelanggan_active" class="nav-link {{ request()->is('pelanggan') ? 'active' : '' }}" href="{{ url('pelanggan') }}">Pelanggan</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Barang In/Out
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="barang_in_active" class="nav-link {{ request()->is('barang-in') ? 'active' : '' }}" href="{{ url('barang-in') }}">Barang In</a>
                                    <a id="barang_out_active" class="nav-link {{ request()->is('barang-out') ? 'active' : '' }}" href="{{ url('barang-out') }}">Barang Out</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Laporan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a id="laporan_harian_active" class="nav-link {{ request()->is('laporan-harian') ? 'active' : '' }}" href="{{ url('laporan-harian') }}">Harian</a>
                                    <a id="laporan_bulanan_active" class="nav-link {{ request()->is('laporan-bulanan') ? 'active' : '' }}" href="{{ url('laporan-bulanan') }}">Bulanan</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Pengaturan</div>

                            @if(Auth()->user()->hasRole('admin'))
                            <a class="nav-link {{ request()->is('data/petugas') ? 'active' : '' }}" href="{{ url('data/petugas') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Petugas
                            </a>
                            @endif
                            
                            <div>
                              <a class="nav-link" href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <div class="sb-nav-link-icon">
                                       <i style="color:rgba(255, 255, 255, 0.5);" class="fas fa-sign-out-alt"></i>
                                    </div>
                                    Logout
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                              </form>
                           </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Login sebagai : {{ Auth::user()->roles()->pluck('name')[0] }}</div>
                        {{ Auth::user()->name; }}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                   @yield('content')
                </main>
                <footer class="py-4 bg-light mt-5">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-center small">
                            <div class="text-muted">Copyright &copy; Tugas Besar</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="{{ asset('template/js/jquery.js') }}"></script>
        <script src="{{ asset('template/js/custom_link_active.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('template/js/scripts.js') }}"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <!-- <script src="js/datatables-simple-demo.js"></script> -->

        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
               var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
               return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>

        <!-- vue js -->
        <script src="{{ asset('template/js/vue.js') }}"></script>
        <script src="{{ asset('template/js/axios.js') }}"></script>
        <script src="{{ asset('template/js/sweetalert.js') }}"></script>
        <script src="{{ asset('template/js/select2.js') }}"></script>

        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                iconColor: 'green',
                background: 'rgb(91, 255, 96)'
            })

            // toast fail
            const toastFail = Swal.mixin({
                toast: true,
                position: 'top-start',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                iconColor: 'green',
                background: 'rgb(255, 71, 71)'
            })

        </script>

        @stack('js')
    </body>
</html>
