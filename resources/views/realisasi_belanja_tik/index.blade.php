<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Realisasi Belanja TIK']]" />
        {{-- <a href="{{ route('katalog-layanan.create') }}" class="btn btn-primary btn-sm">Tambah</a> --}}
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 mt-5">
        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-14 h-14 rounded-lg text-green-600">
                <svg class="w-13 h-13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20.9414 8.18881C21.5215 8.76206 21.771 9.48386 21.8877 10.3411C22 11.1668 22 12.2166 22 13.5191V13.6236C22 14.9261 22 15.976 21.8877 16.8016C21.771 17.6589 21.5215 18.3807 20.9414 18.9539C20.3612 19.5272 19.6307 19.7738 18.7632 19.889C17.9276 20 16.8651 20 15.547 20H10.622C9.30387 20 8.24141 20 7.4058 19.889C6.53824 19.7738 5.80777 19.5272 5.22762 18.9539C4.87566 18.6062 4.64535 18.2037 4.49261 17.7495C5.36407 17.8574 6.4422 17.8573 7.68787 17.8573H12.6974C13.979 17.8573 15.0833 17.8574 15.9676 17.7399C16.9154 17.614 17.8238 17.3301 18.5607 16.602C19.2975 15.8739 19.5848 14.9762 19.7123 14.0398C19.8312 13.166 19.8311 12.0748 19.831 10.8084V10.6203C19.8311 9.38912 19.8311 8.32356 19.7219 7.46234C20.1818 7.61328 20.5893 7.84088 20.9414 8.18881Z"
                        fill="#1C274C" fill="currentColor" />
                    <path
                        d="M10.1926 9.04765C9.26108 9.04765 8.50591 9.79385 8.50591 10.7143C8.50591 11.6348 9.26108 12.381 10.1926 12.381C11.1242 12.381 11.8793 11.6348 11.8793 10.7143C11.8793 9.79385 11.1242 9.04765 10.1926 9.04765Z"
                        fill="#1C274C" fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.84691 5.83684C2 6.67369 2 8.02057 2 10.7143C2 13.4081 2 14.755 2.84691 15.5918C3.69381 16.4287 5.05688 16.4287 7.78303 16.4287H12.6022C15.3284 16.4287 16.6914 16.4287 17.5384 15.5918C18.3853 14.755 18.3853 13.4081 18.3853 10.7143C18.3853 8.02057 18.3853 6.67369 17.5384 5.83684C16.6914 5 15.3284 5 12.6022 5H7.78303C5.05688 5 3.69381 5 2.84691 5.83684ZM7.06015 10.7143C7.06015 9.00487 8.46261 7.61907 10.1926 7.61907C11.9226 7.61907 13.3251 9.00487 13.3251 10.7143C13.3251 12.4238 11.9226 13.8096 10.1926 13.8096C8.46261 13.8096 7.06015 12.4238 7.06015 10.7143ZM15.4937 13.3334C15.0945 13.3334 14.7709 13.0136 14.7709 12.6191V8.80956C14.7709 8.41506 15.0945 8.09526 15.4937 8.09526C15.893 8.09526 16.2166 8.41506 16.2166 8.80956V12.6191C16.2166 13.0136 15.893 13.3334 15.4937 13.3334ZM4.16864 12.6191C4.16864 13.0136 4.49228 13.3334 4.89152 13.3334C5.29075 13.3334 5.61439 13.0136 5.61439 12.6191L5.61439 8.80956C5.61439 8.41506 5.29075 8.09526 4.89152 8.09526C4.49228 8.09526 4.16864 8.41506 4.16864 8.80956L4.16864 12.6191Z"
                        fill="#1C274C" fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Jumlah Anggaran</h4>
                <p id="summary-total-anggaran" class="text-3xl font-bold">0</p>
                <p class="text-xs text-gray-500">Jumlah Anggaran Rekomendasi Sistem Informasi</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-14 h-14 rounded-lg text-primary">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.97883 9.68508C2.99294 8.89073 2 8.49355 2 8C2 7.50645 2.99294 7.10927 4.97883 6.31492L7.7873 5.19153C9.77318 4.39718 10.7661 4 12 4C13.2339 4 14.2268 4.39718 16.2127 5.19153L19.0212 6.31492C21.0071 7.10927 22 7.50645 22 8C22 8.49355 21.0071 8.89073 19.0212 9.68508L16.2127 10.8085C14.2268 11.6028 13.2339 12 12 12C10.7661 12 9.77318 11.6028 7.7873 10.8085L4.97883 9.68508Z"
                        fill="#1C274C" fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2 8C2 8.49355 2.99294 8.89073 4.97883 9.68508L7.7873 10.8085C9.77318 11.6028 10.7661 12 12 12C13.2339 12 14.2268 11.6028 16.2127 10.8085L19.0212 9.68508C21.0071 8.89073 22 8.49355 22 8C22 7.50645 21.0071 7.10927 19.0212 6.31492L16.2127 5.19153C14.2268 4.39718 13.2339 4 12 4C10.7661 4 9.77318 4.39718 7.7873 5.19153L4.97883 6.31492C2.99294 7.10927 2 7.50645 2 8Z"
                        fill="#1C274C" fill="currentColor" />
                    <path
                        d="M19.0212 13.6851L16.2127 14.8085C14.2268 15.6028 13.2339 16 12 16C10.7661 16 9.77318 15.6028 7.7873 14.8085L4.97883 13.6851C2.99294 12.8907 2 12.4935 2 12C2 11.5551 2.80681 11.1885 4.42043 10.5388L7.56143 11.7952C9.41007 12.535 10.572 13 12 13C13.428 13 14.5899 12.535 16.4386 11.7952L19.5796 10.5388C21.1932 11.1885 22 11.5551 22 12C22 12.4935 21.0071 12.8907 19.0212 13.6851Z"
                        fill="#1C274C" fill="currentColor" />
                    <path
                        d="M19.0212 17.6849L16.2127 18.8083C14.2268 19.6026 13.2339 19.9998 12 19.9998C10.7661 19.9998 9.77318 19.6026 7.7873 18.8083L4.97883 17.6849C2.99294 16.8905 2 16.4934 2 15.9998C2 15.5549 2.80681 15.1883 4.42043 14.5386L7.56143 15.795C9.41007 16.5348 10.572 16.9998 12 16.9998C13.428 16.9998 14.5899 16.5348 16.4386 15.795L19.5796 14.5386C21.1932 15.1883 22 15.5549 22 15.9998C22 16.4934 21.0071 16.8905 19.0212 17.6849Z"
                        fill="#1C274C" fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Total Layanan</h4>
                <p id="summary-total-layanan" class="text-3xl font-bold">0</p>
                <p class="text-xs text-gray-500">Jumlah Layanan Rekomendasi Sistem Informasi</p>
            </div>
        </div>
    </div>


    <div class="panel mt-6">
        <h5 class="font-semibold text-lg dark:text-white-light mb-4">Data Realisasi Belanja TIK</h5>

        <div class="flex flex-wrap gap-3 mb-4">
            <div class="w-64">
                <select id="filter-perangkat-daerah" class="select2-filter w-full"></select>
            </div>

            <div class="w-52">
                <select id="filter-tahun" class="select2-filter w-full">
                    <option value="">Semua Tahun</option>
                    @for ($tahun = date('Y'); $tahun >= 2024; $tahun--)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <table id="myTable2" class="whitespace-nowrap w-full"></table>
        <div id="pagination-container" class="flex justify-end mt-4"></div>
    </div>

    <style>
        table.table-checkbox thead tr th:first-child {
            width: 1px !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
        }

        #myTable2 td,
        #myTable2 th {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        .dataTable-container {
            overflow-x: hidden !important;
        }

        #myTable2 td {
            vertical-align: top;
        }

        #myTable2 th:nth-child(1) {
            width: 50px !important;
        }

        #myTable2 th:nth-child(2) {
            width: 200px !important;
        }

        #myTable2 th:nth-child(3) {
            width: 300px !important;
        }

        #myTable2 th:nth-child(4) {
            width: 250px !important;
        }
    </style>

    @include('components.js.realisasiBelanjaTik')
</x-app-layout>
