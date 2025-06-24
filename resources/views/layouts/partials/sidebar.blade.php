<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="{{ asset('../img/admin-avatar.png') }}" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong">{{ Auth::user()->nama_user }}</div>
                <small>{{ Auth::user()->role }}</small>
            </div>
        </div>
        <ul class="side-menu metismenu">

            {{-- Dashboard: Semua Role --}}
            <li>
                <a class="{{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="sidebar-item-icon fa fa-home"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            {{-- BACK OFFICE --}}
            @if(Auth::user()->role === 'back office')
                <li class="heading">DATA MASTER</li>
                <li>
                    <a class="{{ Route::is('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                        <i class="sidebar-item-icon fa fa-user"></i>
                        <span class="nav-label">User</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('supplier.*') ? 'active' : '' }}" href="{{ route('supplier.index') }}">
                        <i class="sidebar-item-icon fa fa-truck"></i>
                        <span class="nav-label">Supplier</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        <i class="sidebar-item-icon fa fa-tags"></i>
                        <span class="nav-label">Kategori</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                        <i class="sidebar-item-icon fa fa-box"></i>
                        <span class="nav-label">Barang</span>
                    </a>
                </li>

                <li class="heading">TRANSAKSI</li>
                <li>
                    <a class="{{ Route::is('pembelian.*') ? 'active' : '' }}" href="{{ route('pembelian.index') }}">
                        <i class="sidebar-item-icon fa fa-shopping-cart"></i>
                        <span class="nav-label">Pembelian</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('kasir.*') ? 'active' : '' }}" href="{{ route('kasir.penjualan') }}">
                        <i class="sidebar-item-icon fa fa-cash-register"></i>
                        <span class="nav-label">Penjualan</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('permintaan.*') ? 'active' : '' }}" href="{{ route('permintaan.index') }}">
                        <i class="sidebar-item-icon fa fa-clipboard-list"></i>
                        <span class="nav-label">Permintaan</span>
                    </a>
                </li>
                <li>
                    <a class="{{ Route::is('retur.*') ? 'active' : '' }}" href="{{ route('retur.index') }}">
                        <i class="sidebar-item-icon fa fa-undo"></i>
                        <span class="nav-label">Retur</span>
                    </a>
                </li>

                <li class="heading">LAPORAN</li>
                <li>
                    <a href="{{ route('laporan.barang.index') }}">
                        <i class="sidebar-item-icon fa fa-file-alt"></i>
                        <span class="nav-label">Laporan Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.pembelian.index') }}">
                        <i class="sidebar-item-icon fa fa-file-download"></i>
                        <span class="nav-label">Laporan Pembelian</span>
                    </a>
                </li>
                <li>
                    {{-- <a href="{{ route('laporan.penjualan') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                        <span class="nav-label">Laporan Penjualan</span>
                    </a> --}}
                     <a href="{{ route('laporan.kasir.index') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                         <span class="nav-label">Laporan Penjualan</span>
                    </a>
                </li>
            @endif

            {{-- KASIR --}}
            @if(Auth::user()->role === 'kasir')
                <li class="heading">TRANSAKSI</li>
                <li>
                    <a class="{{ Route::is('kasir.*') ? 'active' : '' }}" href="{{ route('kasir.penjualan') }}">
                        <i class="sidebar-item-icon fa fa-cash-register"></i>
                        <span class="nav-label">Penjualan</span>
                    </a>
                </li>
                <li class="heading">LAPORAN</li>
                <li>
                    {{-- <a href="{{ route('laporan.penjualan') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                        <span class="nav-label">Laporan Penjualan</span>
                    </a> --}}
                     <a href="{{ route('laporan.kasir.index') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                         <span class="nav-label">Laporan Penjualan</span>
                    </a>
                </li>
            @endif

            {{-- OWNER --}}
            {{-- {{dd(Auth::user()->role)}} --}}
            @if(Auth::user()->role === 'owner')
                <li class="heading">LAPORAN</li>
                <li>
                    <a href="{{ route('laporan.barang.index') }}">
                        <i class="sidebar-item-icon fa fa-file-alt"></i>
                        <span class="nav-label">Laporan Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan.pembelian.index') }}">
                        <i class="sidebar-item-icon fa fa-file-download"></i>
                        <span class="nav-label">Laporan Pembelian</span>
                    </a>
                </li>
                <li>
                    {{-- <a href="{{ route('laporan.penjualan') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                        <span class="nav-label">Laporan Penjualan</span>
                    </a> --}}
                    <a href="{{ route('laporan.kasir.index') }}">
                        <i class="sidebar-item-icon fa fa-file-upload"></i>
                         <span class="nav-label">Laporan Penjualan</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</nav>
