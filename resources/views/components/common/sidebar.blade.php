<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-20 ml-[5px] flex-none" src="{{ asset('/assets/images/logo_diskominfo_full.png') }}"
                        alt="image" />
                    <span
                        class="text-xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle lg:inline dark:text-white-light">SPBE</span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden  p-4 py-0"
                x-data="{ activeDropdown: null }">
                <li class="menu nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="flex items-center">

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                        </div>
                    </a>
                </li>

                {{-- <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Apps</span>
                </h2> --}}

                @canany([
                    'Penyedia Layanan;Lihat',
                    'Perangkat Daerah;Lihat',
                    'Jenis Syarat;Lihat',
                    'Kelompok
                    Layanan;Lihat',
                    'Kategori Perubahan;Lihat',
                    'Jenis Insiden;Lihat',
                    'Satuan;Lihat',
                    'Jenis
                    Peran;Lihat',
                    ])
                    <li class="nav-item">
                        <ul>
                            <li class="menu nav-item">
                                <button type="button" class="nav-link group"
                                    :class="{ 'active': activeDropdown === 'invoice' }"
                                    @click="activeDropdown === 'invoice' ? activeDropdown = null : activeDropdown = 'invoice'">
                                    <div class="flex text-left items-center">
                                        <span
                                            class="ltr:pl-3  text-black dark:text-[#506690] dark:group-hover:text-white-dark text-wrap">Master
                                            & Reference Data Management</span>
                                    </div>
                                    <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'invoice' }">

                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </button>
                                <ul x-cloak x-show="activeDropdown === 'invoice'" x-collapse class="sub-menu text-gray-500">
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
                                            class="nav-link {{ request()->routeIs('jenis-layanan*') ? 'active' : '' }}">Jenis
                                            Layanan</a>
                                    </li>
                                @endcan --}}
                                    @can('Kelompok Layanan;Lihat')
                                        <li>
                                            <a href="{{ route('kelompok-layanan.index') }}"
                                                class="nav-link {{ request()->routeIs('kelompok-layanan*') ? 'active' : '' }}">Kelompok
                                                Layanan</a>
                                        </li>
                                    @endcan
                                    @canany([
                                        'Satuan;Lihat',
                                        'Jenis Peran;Lihat',
                                        'Jenis Insiden;Lihat',
                                        'Kategori Perubahan;Lihat',
                                        'Jenis
                                        Syarat;Lihat',
                                        'Status Penanganan Insiden;Lihat',
                                        ])
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
                        </ul>
                    </li>
                @endcanany

                @canany(['Katalog Layanan;Pendaftaran', 'Katalog Layanan;Verifikasi'])
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group"
                            :class="{ 'active': activeDropdown === 'katalog-layanan' }"
                            @click="activeDropdown === 'katalog-layanan' ? activeDropdown = null : activeDropdown = 'katalog-layanan'">
                            <div class="flex items-center">
                                <span
                                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Katalog
                                    Layanan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'katalog-layanan' }">

                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'katalog-layanan'" x-collapse class="sub-menu text-gray-500">
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
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group"
                            :class="{ 'active': activeDropdown === 'manajemen-layanan' }"
                            @click="activeDropdown === 'manajemen-layanan' ? activeDropdown = null : activeDropdown = 'manajemen-layanan'">
                            <div class="flex items-center text-start">
                                <span
                                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark text-wrap">Manajemen
                                    Permintaan Layanan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'manajemen-layanan' }">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'manajemen-layanan'" x-collapse
                            class="sub-menu text-gray-500">
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
                    'Manajemen
                    Perubahan;Penelusuran',
                    'Manajemen Perubahan;Penutupan',
                    ])
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group"
                            :class="{ 'active': activeDropdown === 'perubahan-layanan' }"
                            @click="activeDropdown === 'perubahan-layanan' ? activeDropdown = null : activeDropdown = 'perubahan-layanan'">
                            <div class="flex">
                                <span
                                    class="ltr:pl-3  text-black dark:text-[#506690] dark:group-hover:text-white-dark text-wrap">Manajemen
                                    Perubahan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'perubahan-layanan' }">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'perubahan-layanan'" x-collapse
                            class="sub-menu text-gray-500">
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
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group"
                            :class="{ 'active': activeDropdown === 'insiden-layanan' }"
                            @click="activeDropdown === 'insiden-layanan' ? activeDropdown = null : activeDropdown = 'insiden-layanan'">
                            <div class="flex">
                                <span
                                    class="ltr:pl-3  text-black dark:text-[#506690] dark:group-hover:text-white-dark text-wrap">Manajemen
                                    Insiden</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'insiden-layanan' }">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'insiden-layanan'" x-collapse
                            class="sub-menu text-gray-500">
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
                    'Permintaan Layanan;Lihat
                    Riwayat',
                    'Manajemen Insiden;Lihat Riwayat',
                    ])
                    <li class="menu nav-item">
                        <button type="button" class="nav-link group"
                            :class="{ 'active': activeDropdown === 'pelaporan' }"
                            @click="activeDropdown === 'pelaporan' ? activeDropdown = null : activeDropdown = 'pelaporan'">
                            <div class="flex">
                                <span
                                    class="ltr:pl-3  text-black dark:text-[#506690] dark:group-hover:text-white-dark text-wrap">Pelporan</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'pelaporan' }">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <ul x-cloak x-show="activeDropdown === 'pelaporan'" x-collapse class="sub-menu text-gray-500">

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
                                    class="nav-link {{ request()->routeIs('realisasi-belanja-tik.index', 'realisasi-belanja-tik.show') ? 'active' : '' }}">Realisasi
                                    Belanja TIK</a>
                            </li>
                            @can('Manajemen Insiden;Lihat Riwayat')
                                <li>
                                    <a href="{{ route('katalog-aplikasi.index') }}"
                                        class="nav-link {{ request()->routeIs('insiden-layanan.index') ? 'active' : '' }}">Daftar
                                        Katalog Aplikasi</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('Pengguna Aplikasi;Lihat')
                    <li class="menu nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <div class="flex items-center">

                                <span
                                    class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Manajemen
                                    Pengguna</span>
                            </div>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </nav>
</div>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location
                    .pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>
