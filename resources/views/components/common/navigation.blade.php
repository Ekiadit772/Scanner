<ul
    class="horizontal-menu items-center hidden py-1.5 font-semibold px-6 lg:space-x-1.5 xl:space-x-8 rtl:space-x-reverse bg-white border-t border-[#ebedf2] dark:border-[#191e3a] dark:bg-[#0e1726] text-black dark:text-white-dark">
    <li class="menu nav-item relative">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="flex items-center">
                <span class="px-1">Dashboard</span>
            </div>
        </a>
    </li>
    @canany(['Penyedia Layanan;Lihat', 'Perangkat Daerah;Lihat', 'Jenis Syarat;Lihat', 'Kelompok Layanan;Lihat',
        'Kategori Perubahan;Lihat', 'Jenis Insiden;Lihat', 'Satuan;Lihat', 'Jenis Peran;Lihat'])
        <li class="menu nav-item relative">
            <a href="javascript:;" class="nav-link">
                <div class="flex items-center text-center">
                    <span class="px-1">Manajemen Data Master & Referensi</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Penyedia Layanan;Lihat')
                    <li>
                        <a href="{{ route('penyedia-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('penyedia-layanan*') ? 'active' : '' }}">
                            Penyedia Layanan
                        </a>
                    </li>
                @endcan
                @can('Perangkat Daerah;Lihat')
                    <li>
                        <a href="{{ route('perangkat-daerah.index') }}"
                            class="nav-link {{ request()->routeIs('perangkat-daerah*') ? 'active' : '' }}">
                            Perangkat Daerah
                        </a>
                    </li>
                @endcan
                <li>
                    <a href="{{ route('peta-jalan-spbe.index') }}">Peta Jalan SPBE</a>
                </li>
                {{-- @can('lihat jenis layanan')
                    <li>
                    <a href="{{ route('jenis-layanan.index') }}"
                        class="nav-link {{ request()->routeIs('jenis-layanan*') ? 'active' : '' }}">Jenis Layanan</a>
                </li>
                @endcan --}}
                @can('Kelompok Layanan;Lihat')
                    <li>
                        <a href="{{ route('kelompok-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('kelompok-layanan*') ? 'active' : '' }}">Kelompok Layanan</a>
                    </li>
                @endcan
                @canany(['Satuan;Lihat', 'Jenis Peran;Lihat', 'Jenis Insiden;Lihat', 'Kategori Perubahan;Lihat', 'Jenis
                    Syarat;Lihat', 'Status Penanganan Insiden;Lihat'])
                    <li>
                        @php
                            if (auth()->user()->can('Satuan;Lihat')) {
                                $route = route('satuan.index');
                            } elseif (auth()->user()->can('Jenis Peran;Lihat')) {
                                $route = route('jenis-peran.index');
                            } elseif (auth()->user()->can('Jenis Insiden;Lihat')) {
                                $route = route('jenis-insiden.index');
                            } elseif (auth()->user()->can('Kategori Perubahan;Lihat')) {
                                $route = route('kategori-perubahan.index');
                            } elseif (auth()->user()->can('Jenis Syarat;Lihat')) {
                                $route = route('jenis-syarat.index');
                            } elseif (auth()->user()->can('Status Penanganan Insiden;Lihat')) {
                                $route = route('status-penanganan-insiden.index');
                            }
                        @endphp
                        <a href="{{ $route }}"
                            class="nav-link {{ request()->routeIs('satuan*', 'jenis-peran*', 'jenis-insiden*', 'kategori-perubahan*', 'jenis-syarat*', 'status-penanganan-insiden*') ? 'active' : '' }}">
                            Referensi
                        </a>
                    </li>
                @endcanany

            </ul>
        </li>
    @endcanany

    @canany(['Katalog Layanan;Pendaftaran', 'Katalog Layanan;Verifikasi'])
        <li class="menu nav-item relative">
            <a href="javascript:;" class="nav-link {{ request()->routeIs('katalog-layanan*') ? 'active' : '' }}">
                <div class="flex items-center">
                    <span class="px-1">Katalog Layanan</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Katalog Layanan;Pendaftaran')
                    <li>
                        <a href="{{ route('katalog-layanan.create') }}"
                            class="nav-link {{ request()->routeIs('katalog-layanan.create') ? 'active' : '' }}">Pendaftaran
                            Layanan</a>
                    </li>
                @endcan
                @can('Katalog Layanan;Verifikasi')
                    <li>
                        <a href="{{ route('katalog-layanan.verifikasi') }}"
                            class="nav-link {{ request()->routeIs('katalog-layanan.verifikasi') ? 'active' : '' }}">
                            Data Pendaftaran Layanan Yang Belum Diverifikasi
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @canany(['Permintaan Layanan;Buat Permintaan', 'Permintaan Layanan;Verifikasi'])
        <li class="menu nav-item relative">
            <a href="javascript:;"
                class="nav-link {{ request()->routeIs('permintaan-layanan.create', 'permintaan-layanan.verifikasi', 'permintaan-layanan.proses', 'permintaan-layanan.closing') ? 'active' : '' }}">
                <div class="flex items-center">
                    <span class="px-1">Manajemen Permintaan Layanan</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Permintaan Layanan;Buat Permintaan')
                    <li>
                        <a href="{{ route('permintaan-layanan.create') }}"
                            class="nav-link {{ request()->routeIs('permintaan-layanan.create') ? 'active' : '' }}">Permintaan
                            Layanan</a>
                    </li>
                @endcan
                @can('Permintaan Layanan;Verifikasi')
                    <li>
                        <a href="{{ route('permintaan-layanan.verifikasi') }}"
                            class="nav-link {{ request()->routeIs('permintaan-layanan.verifikasi') ? 'active' : '' }}">
                            Verifikasi Permintaan Layanan
                        </a>
                    </li>
                @endcan
                @can('Permintaan Layanan;Verifikasi')
                    <li>
                        <a href="{{ route('permintaan-layanan.proses') }}"
                            class="nav-link {{ request()->routeIs('permintaan-layanan.proses') ? 'active' : '' }}">
                            Pelaporan Pelaksanaan Permintaan Layanan
                        </a>
                    </li>
                @endcan
                @can('Permintaan Layanan;Verifikasi')
                    <li>
                        <a href="{{ route('permintaan-layanan.closing') }}"
                            class="nav-link {{ request()->routeIs('permintaan-layanan.closing') ? 'active' : '' }}">
                            Pengakhiran Permintaan Layanan
                        </a>
                    </li>
                @endcan

            </ul>
        </li>
    @endcanany

    @canany([
        'Manajemen Perubahan;Permintaan Perubahan',
        'Manajemen Perubahan;Persetujuan Perubahan',
        'Manajemen Perubahan;Pelaporan',
        'Manajemen Perubahan;Pelaksanaan Perubahan',
        'Manajemen Perubahan;Penelusuran',
        'Manajemen Perubahan;Penutupan',
        ])
        <li class="menu nav-item relative">
            <a href="javascript:;"
                class="nav-link
        {{ request()->routeIs(
            'perubahan-layanan.create',
            'perubahan-layanan.persetujuan',
            'perubahan-layanan.pelaporan',
            'perubahan-layanan.pelaksanaan',
            'perubahan-layanan.penelusuran',
            'perubahan-layanan.closing',
        )
            ? 'active'
            : '' }}">
                <div class="flex items-center">
                    <span class="px-1">Manajemen Perubahan</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Manajemen Perubahan;Permintaan Perubahan')
                    <li>
                        <a href="{{ route('perubahan-layanan.create') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.create') ? 'active' : '' }}">
                            Permintaan Perubahan
                        </a>
                    </li>
                @endcan
                @can('Manajemen Perubahan;Persetujuan Perubahan')
                    <li>
                        <a href="{{ route('perubahan-layanan.persetujuan') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.persetujuan') ? 'active' : '' }}">
                            Persetujuan Perubahan
                        </a>
                    </li>
                @endcan
                @can('Manajemen Perubahan;Pelaporan')
                    <li>
                        <a href="{{ route('perubahan-layanan.pelaporan') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.pelaporan') ? 'active' : '' }}">
                            Pelaporan Perubahan
                        </a>
                    </li>
                @endcan
                {{-- @can('Manajemen Perubahan;Pelaksanaan Perubahan')
                    <li>
                        <a href="{{ route('perubahan-layanan.pelaksanaan') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.pelaksanaan') ? 'active' : '' }}">
                            Pelaksanaan Perubahan
                        </a>
                    </li>
                @endcan
                @can('Manajemen Perubahan;Penelusuran')
                    <li>
                        <a href="{{ route('perubahan-layanan.penelusuran') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.penelusuran') ? 'active' : '' }}">
                            Penelusuran dan Status Implementasi Perubahan
                        </a>
                    </li>
                @endcan --}}
                @can('Manajemen Perubahan;Penutupan')
                    <li>
                        <a href="{{ route('perubahan-layanan.closing') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.closing') ? 'active' : '' }}">
                            Penutupan Permintaan Perubahan
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @canany([
        'Manajemen Insiden;Pelaporan Insiden',
        'Manajemen Insiden;Penugasan Penanganan Insiden',
        'Manajemen
        Insiden;Penanganan Insiden',
        'Manajemen Insiden;Penulusuran Insiden',
        'Manajemen Insiden;Penutupan Penanganan',
        ])
        <li class="menu nav-item relative">
            <a href="javascript:;"
                class="nav-link {{ request()->routeIs('insiden-layanan.create', 'insiden-layanan.penugasan', 'insiden-layanan.penanganan', 'insiden-layanan.closing') ? 'active' : '' }}">
                <div class="flex items-center">
                    <span class="px-1">Manajemen Insiden</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Manajemen Insiden;Pelaporan Insiden')
                    <li>
                        <a href="{{ route('insiden-layanan.create') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.create') ? 'active' : '' }}">Pelaporan
                            Insiden</a>
                    </li>
                @endcan
                @can('Manajemen Insiden;Penugasan Penanganan Insiden')
                    <li>
                        <a href="{{ route('insiden-layanan.penugasan') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.penugasan') ? 'active' : '' }}">Penugasan
                            Penanganan Insiden</a>
                    </li>
                @endcan
                @can('Manajemen Insiden;Penanganan Insiden')
                    <li>
                        <a href="{{ route('insiden-layanan.penanganan') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.penanganan') ? 'active' : '' }}">Penanganan
                            Insiden</a>
                    </li>
                @endcan
                @can('Manajemen Insiden;Penulusuran Insiden')
                    <li>
                        <a href="{{ route('insiden-layanan.penelusuran') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.penelusuran') ? 'active' : '' }}">Penelusuran
                            dan
                            Status Penanganan Insiden</a>
                    </li>
                @endcan
                @can('Manajemen Insiden;Penutupan Penanganan')
                    <li>
                        <a href="{{ route('insiden-layanan.closing') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.closing') ? 'active' : '' }}">Penutupan
                            Penanganan Insiden</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @canany([
        'Rekapitulasi Penggunaan Layanan;Lihat',
        'Katalog Layanan;Lihat Riwayat',
        'Permintaan Layanan;Lihat Riwayat',
        'Manajemen Insiden;Lihat Riwayat',
        ])
        <li class="menu nav-item relative">
            <a href="javascript:;"
                class="nav-link {{ request()->routeIs('rekapitulasi-penggunaan-layanan.index', 'katalog-layanan.index', 'insiden-layanan.index', 'insiden-layanan.show', 'perubahan-layanan.index') ? 'active' : '' }}">
                <div class="flex items-center">
                    <span class="px-1">Pelaporan</span>
                </div>
                <div class="right_arrow">
                    <svg class="w-4 h-4 rotate-90" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <ul class="sub-menu">
                @can('Rekapitulasi Penggunaan Layanan;Lihat')
                    {{-- <li>
                        <a href="{{ route('rekapitulasi-penggunaan-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('rekapitulasi-penggunaan-layanan*') ? 'active' : '' }}">
                            Rekapitulasi Penggunaan Layanan
                        </a>
                    </li> --}}
                @endcan

                @can('Katalog Layanan;Lihat Riwayat')
                    <li>
                        <a href="{{ route('katalog-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('katalog-layanan.index') ? 'active' : '' }}">Daftar
                            Katalog Layanan</a>
                    </li>
                @endcan
                @can('Permintaan Layanan;Lihat Riwayat')
                    <li>
                        <a href="{{ route('permintaan-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('permintaan-layanan.index') ? 'active' : '' }}">Riwayat
                            Permintaan Layanan</a>

                    </li>
                @endcan
                @can('Manajemen Perubahan;Lihat Riwayat')
                    <li>
                        <a href="{{ route('perubahan-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('perubahan-layanan.index') ? 'active' : '' }}">Riwayat
                            Perubahan Layanan</a>
                    </li>
                @endcan
                @can('Manajemen Insiden;Lihat Riwayat')
                    <li>
                        <a href="{{ route('insiden-layanan.index') }}"
                            class="nav-link {{ request()->routeIs('insiden-layanan.index', 'insiden-layanan.show') ? 'active' : '' }}">Riwayat
                            Insiden Layanan</a>
                    </li>
                @endcan
                <li>
                    <a href="{{ route('realisasi-belanja-tik.index') }}"
                        class="nav-link {{ request()->routeIs('realisasi-belanja-tik.index', 'realisasi-belanja-tik.show') ? 'active' : '' }}">Realisasi Belanja TIK</a>
                </li>
                @can('Manajemen Insiden;Lihat Riwayat')
                    <li>
                        <a href="{{ route('katalog-aplikasi.index') }}"
                            class="nav-link {{ request()->routeIs('katalog-aplikasi.index') ? 'active' : '' }}">Daftar Katalog Aplikasi</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @can('Pengguna Aplikasi;Lihat')
        <li class="menu nav-item relative">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                <div class="flex items-center text-center">
                    <span class="px-1">Manajemen Pengguna</span>
                </div>
            </a>
        </li>
    @endcan
</ul>
