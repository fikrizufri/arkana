<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="30" src="{{ asset('img/logo.png') }}" class="header-brand-img" title="Arkana">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment1 == 'home' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
                @canany(['view-satuan', 'view-kategori', 'view-jenis', 'view-ukuran', 'view-kategori-harga-jual'])
                    <div class="nav-lavel">{{ __('Master Data') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'satuan' ||$segment1 == 'produk' ||$segment1 == 'kategori_harga_jual' ||$segment1 == 'ukuran' ||$segment1 == 'kategori' ||$segment1 == 'jenis'? 'active open': '' }} has-sub">
                        <a href="#"><i class="ik ik-box"></i><span>{{ __('Produk') }}</span></a>
                        <div class="submenu-content">
                            @can('view-produk')
                                <a href="{{ route('produk.index') }}"
                                    class="menu-item {{ $segment1 == 'produk' ? 'active' : '' }}">
                                    Produk
                                </a>
                            @endcan
                            @can('view-satuan')
                                <a href="{{ route('satuan.index') }}"
                                    class="menu-item {{ $segment1 == 'satuan' ? 'active' : '' }}">
                                    Satuan
                                </a>
                            @endcan
                            @can('view-kategori')
                                <a href="{{ route('kategori.index') }}"
                                    class="menu-item {{ $segment1 == 'kategori' ? 'active' : '' }}">
                                    Kategori
                                </a>
                            @endcan
                            @can('view-jenis')
                                <a href="{{ route('jenis.index') }}"
                                    class="menu-item {{ $segment1 == 'jenis' ? 'active' : '' }}">
                                    Jenis
                                </a>
                            @endcan
                            {{-- @can('view-ukuran')
                                <a href="{{ route('ukuran.index') }}"
                                    class="menu-item {{ $segment1 == 'ukuran' ? 'active' : '' }}">
                                    Ukuran
                                </a>
                            @endcan
                            @can('view-kategori-harga-jual')
                                <a href="{{ route('kategori_harga_jual.index') }}"
                                    class="menu-item {{ $segment1 == 'kategori_harga_jual' ? 'active' : '' }}">
                                    Kategori Harga Jual
                                </a>
                            @endcan --}}
                        </div>
                    </div>
                @endcan
                @canany(['view-pusat', 'view-cabang', 'view-toko', 'view-gudang', 'view-supplier'])
                    <div class="nav-lavel">{{ __('Perusahaan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'pusat' ||$segment1 == 'cabang' ||$segment1 == 'toko' ||$segment1 == 'gudang' ||$segment1 == 'supplier'? 'active open': '' }} has-sub">
                        <a href="#"><i class="fa fa-building dropdown-icon"></i><span>{{ __('Perusahaan') }}</span></a>
                        <div class="submenu-content">
                            @can('view-pusat')
                                <a href="{{ route('pusat.index') }}"
                                    class="menu-item {{ $segment1 == 'pusat' ? 'active' : '' }}">
                                    Pusat
                                </a>
                            @endcan
                            @can('view-cabang')
                                <a href="{{ route('cabang.index') }}"
                                    class="menu-item {{ $segment1 == 'cabang' ? 'active' : '' }}">
                                    Cabang
                                </a>
                            @endcan
                            @can('view-toko')
                                <a href="{{ route('toko.index') }}"
                                    class="menu-item {{ $segment1 == 'toko' ? 'active' : '' }}">
                                    Toko
                                </a>
                            @endcan
                            @can('view-supplier')
                                <a href="{{ route('supplier.index') }}"
                                    class="menu-item {{ $segment1 == 'supplier' ? 'active' : '' }}">
                                    Supplier
                                </a>
                            @endcan

                        </div>
                    </div>
                @endcan
                @canany(['view-karyawan', 'view-penggajian', 'view-jabatan', 'view-shift', 'view-roster',
                    'view-absensi', 'view-kas-bon'])
                    <div class="nav-lavel">{{ __('Karyawan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'jabatan' ||$segment1 == 'penggajian' ||$segment1 == 'karyawan' ||$segment1 == 'roster' ||$segment1 == 'jadwal' ||$segment1 == 'shift' ||$segment1 == 'kas-bon' ||$segment1 == 'pembayaran-kas-bon' ||$segment1 == 'roster-import' ||$segment1 == 'absensi'? 'active open': '' }} has-sub">
                        <a href="#"><i class="fa fa-users dropdown-icon"></i><span>{{ __('Karyawan') }}</span></a>
                        <div class="submenu-content">
                            @can('view-penggajian')
                                <a href="{{ route('penggajian.index') }}"
                                    class="menu-item {{ $segment1 == 'penggajian' ? 'active' : '' }} ? 'active' : '' }}">
                                    Penggajian
                                </a>
                            @endcan
                            @can('view-karyawan')
                                <a href="{{ route('karyawan.index') }}"
                                    class="menu-item {{ $segment1 == 'karyawan' ? 'active' : '' }} ? 'active' : '' }}">
                                    Karyawan
                                </a>
                            @endcan
                            @can('view-jabatan')
                                <a href="{{ route('jabatan.index') }}"
                                    class="menu-item {{ $segment1 == 'jabatan' ? 'active' : '' }}">
                                    Jabatan
                                </a>
                            @endcan

                            @can('view-shift')
                                <a href="{{ route('shift.index') }}"
                                    class="menu-item {{ $segment1 == 'shift' ? 'active' : '' }} ? 'active' : '' }}">
                                    Shift
                                </a>
                            @endcan
                            @can('view-roster')
                                <a href="{{ route('roster.index') }}"
                                    class="menu-item {{ $segment1 == 'roster' || $segment1 == 'roster-import' ? 'active' : '' }} ? 'active' : '' }}">
                                    Roster
                                </a>
                            @endcan
                            @can('view-absensi')
                                <a href="{{ route('absensi.index') }}"
                                    class="menu-item {{ $segment1 == 'absensi' ? 'active' : '' }} ? 'active' : '' }}">
                                    Absensi
                                </a>
                            @endcan
                            @can('view-kas-bon')
                                <a href="{{ route('kas-bon.index') }}"
                                    class="menu-item {{ $segment1 == 'kas-bon' ? 'active' : '' }} ? 'active' : '' }}">
                                    Kas Bon
                                </a>
                            @endcan
                            @can('view-pembayaran-kas-bon')
                                <a href="{{ route('pembayaran-kas-bon.index') }}"
                                    class="menu-item {{ $segment1 == 'pembayaran-kas-bon' ? 'active' : '' }} ? 'active' : '' }}">
                                    Pembayaran Kas Bon
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcan


                @canany(['view-penjualan', 'view-promosi'])
                    <div class="nav-lavel">{{ __('Penjualan') }} </div>
                    @can('create-penjualan')
                        <div class="nav-item  {{ $segment1 == 'penjualan' ? 'active' : '' }}">
                            <a href="{{ route('penjualan.create') }}">
                                <i class="ik ik-shopping-cart dropdown-icon"></i>
                                <span>Penjualan</span>
                            </a>

                        </div>
                    @endcan
                    @can('view-promosi')
                        <div class="nav-item  {{ $segment1 == 'promosi' ? 'active' : '' }}">
                            <a href="{{ route('promosi.index') }}">
                                <i class="ik ik-package dropdown-icon"></i>
                                <span>Promosi</span>
                            </a>

                        </div>
                    @endcan
                @endcan
                @can('create-pembelian')
                    <div class="nav-item  {{ $segment1 == 'pembelian' ? 'active' : '' }}">
                        <a href="{{ route('pembelian.create') }}">
                            <i class="ik ik-briefcase dropdown-icon"></i>
                            <span>Pembelian</span>
                        </a>

                    </div>
                @endcan
                @can('view-metode-pembayaran')
                    <div class="nav-item  {{ $segment1 == 'metode-pembayaran' ? 'active' : '' }}">
                        <a href="{{ route('metode-pembayaran.index') }}">
                            <i class="ik ik-archive dropdown-icon"></i>
                            <span>Metode Pembayaran</span>
                        </a>

                    </div>
                @endcan
                @can('view-bank')
                    <div class="nav-item  {{ $segment1 == 'bank' ? 'active' : '' }}">
                        <a href="{{ route('bank.index') }}">
                            <i class="fa fa-credit-card dropdown-icon"></i>
                            <span>Bank</span>
                        </a>

                    </div>
                @endcan
                @can('view-pelanggan')
                    <div class="nav-item ">
                        <a href="{{ route('pelanggan.index') }}"><i class="ik ik-layout"></i><span>Pelanggan</span>
                        </a>
                    </div>
                @endcan
                @role(['admin','superadmin'])
                    <div class="nav-lavel">{{ __('Laporan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'laporan-penjualan' || $segment1 == 'laporan-pembelian' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="fa fa-file dropdown-icon"></i><span>{{ __(' Laporan') }}</span></a>
                        <div class="submenu-content">
                            @role('admin', 'superadmin')
                                <a href="{{ route('laporan.penjualan') }}"
                                    class="menu-item {{ $segment1 == 'laporan-penjualan' ? 'active' : '' }} ? 'active' : '' }}">
                                    Laporan Penjualan
                                </a>
                            @endrole
                            @role('admin', 'superadmin')
                                <a href="{{ route('laporan.pembelian') }}"
                                    class="menu-item {{ $segment1 == 'laporan-pembelian' ? 'active' : '' }} ? 'active' : '' }}">
                                    Laporan Pembelian
                                </a>
                            @endrole
                        </div>
                    </div>
                @endrole
                {{-- @role('admin')
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div class="nav-item {{ $segment1 == 'laporan-penjualan' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Laporan') }}</span></a>
                        <div class="submenu-content">
                            @role('admin')
                                <a href="{{ route('laporan-penjualan') }}"
                                    class="menu-item {{ $segment1 == 'laporan-penjualan' ? 'active' : '' }}">
                                    Laporan Penjualan
                                </a>
                            @endrole
                        </div>
                    </div>
                @endrole --}}
                @canany(['view-user', 'view-roles'])
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'user' || $segment1 == 'role' || $segment1 == 'task' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Pengguna') }}</span></a>
                        <div class="submenu-content">
                            @can('view-user')
                                <a href="{{ route('user.index') }}"
                                    class="menu-item {{ $segment1 == 'user' ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            @endcan
                            @can('view-roles')
                                <a href="{{ route('role.index') }}"
                                    class="menu-item {{ $segment1 == 'role' ? 'active' : '' }}">
                                    Hak Akses
                                </a>
                            @endcan
                            @role('superadmin')
                                <a href="{{ route('task.index') }}"
                                    class="menu-item {{ $segment1 == 'task' ? 'active' : '' }}">
                                    Task
                                </a>
                            @endrole
                        </div>
                    </div>
                @endcan
            </nav>
        </div>
    </div>
</div>
