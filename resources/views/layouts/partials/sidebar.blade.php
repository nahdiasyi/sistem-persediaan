<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                {{-- {{dd(Auth::user())}} --}}
                <img src="{{ asset('../img/admin-avatar.png') }}" width="45px" />
            </div>
            <div class="admin-info">

                <div class="font-strong">{{ Auth::user()->nama_user }}</div>
                <small>{{ Auth::user()->Jabatan }}</small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="{{ Route::is('dashboard') }}" href="{{ route('dashboard') }}"><i
                        class="sidebar-item-icon fa fa-home"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading">DATA MASTER</li>
            <li>
                <a class="{{ Route::is('user.index', 'user.create', 'user.edit', 'user.*') ? 'active' : '' }}"
                    href="{{ route('user.index') }}">
                    <i class="sidebar-item-icon fa fa-user"></i>
                    <span class="nav-label">User</span>
                </a>

            </li>
            <li>
                <a class="{{ Route::is('supplier.index', 'supplier.create', 'supplier.edit', 'supplier.*') ? 'active' : '' }}"
                    href="{{ route('supplier.index') }}"><i class="sidebar-item-icon fa fa-truck"></i>
                    <span class="nav-label">Supplier</span>
                </a>
            </li>
            <li>
                <a class="{{ Route::is('kategori.index', 'kategori.create', 'kategori.edit', 'kategori.*') ? 'active' : '' }}"
                    href="{{ route('kategori.index') }}"><i class="sidebar-item-icon fa fa-tags"></i>
                    <span class="nav-label">Kategori</span>
                </a>
            </li>
            <li>
                <a class="{{ Route::is('barang.index', 'barang.create', 'barang.edit', 'barang.*') ? 'active' : '' }}"
                    href="{{ route('barang.index') }}"><i class="sidebar-item-icon fa fa-box"></i>
                    <span class="nav-label">Barang</span>
                </a>


            </li>
            <li class="heading">TRANSAKSI</li>
            {{-- <li>
                <a class="{{ Route::is('permintaan.create') ? 'active' : '' }}"
                    href="{{ route('permintaan.create') }}"><i class="sidebar-item-icon fa fa-clipboard-list"></i>
                    <span class="nav-label">Permintaan</span>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ url('/pembelian') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Pembelian</p>
                </a>

            </li>
            <li>
                <li class="nav-item">
                    <a href="{{ route('penjualan.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Penjualan</p>
                    </a>
                </li>
            </li>
            <li>
                <li class="nav-item">
                    <a href="{{ route('retur.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Retur</p>
                    </a>
                </li>
            </li>


            <li class="heading">LAPORAN</li>
            <li>
                <a href="laporan-persediaan"><i class="sidebar-item-icon fa fa-file-alt"></i>
                    <span class="nav-label">Laporan Persediaan</span>
                </a>
            </li>
            <li>
                <a href="llaporan.barang-masuk.index"><i class="sidebar-item-icon fa fa-file-download"></i>
                    <span class="nav-label">Laporan Barang Masuk</span>
                </a>

                <a href="#"><i class="sidebar-item-icon fa fa-file-upload"></i>
                    <span class="nav-label">Laporan Barang Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
