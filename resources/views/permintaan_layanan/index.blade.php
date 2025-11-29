<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Riwayat Permintaan Layanan']]" />
        {{-- <a href="{{ route('katalog-layanan.create') }}" class="btn btn-primary btn-sm">Tambah</a> --}}
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-4 mt-5">
        @if ($activeStatus->contains(1))
            <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
                <div class="flex items-center justify-center w-14 h-14 rounded-lg text-gray-600">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C21 19.6569 21 17.7712 21 14V10C21 6.22876 21 4.34315 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 8C7.25 7.58579 7.58579 7.25 8 7.25H16C16.4142 7.25 16.75 7.58579 16.75 8C16.75 8.41421 16.4142 8.75 16 8.75H8C7.58579 8.75 7.25 8.41421 7.25 8ZM7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12ZM8 15.25C7.58579 15.25 7.25 15.5858 7.25 16C7.25 16.4142 7.58579 16.75 8 16.75H13C13.4142 16.75 13.75 16.4142 13.75 16C13.75 15.5858 13.4142 15.25 13 15.25H8Z"
                            fill="currentColor" />
                    </svg>

                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-700">Dalam Antrian</h4>
                    <p id="summary-dalam-antrian" class="text-3xl font-bold">0</p>
                    <p class="text-xs text-gray-500">Data yang akan diproses</p>
                </div>
            </div>
        @endif

        @if ($activeStatus->contains(2))
            <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
                <div class="flex items-center justify-center w-14 h-14 rounded-lg text-primary">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16.5189 16.5013C16.6939 16.3648 16.8526 16.2061 17.1701 15.8886L21.1275 11.9312C21.2231 11.8356 21.1793 11.6708 21.0515 11.6264C20.5844 11.4644 19.9767 11.1601 19.4083 10.5917C18.8399 10.0233 18.5356 9.41561 18.3736 8.94849C18.3292 8.82066 18.1644 8.77687 18.0688 8.87254L14.1114 12.8299C13.7939 13.1474 13.6352 13.3061 13.4987 13.4811C13.3377 13.6876 13.1996 13.9109 13.087 14.1473C12.9915 14.3476 12.9205 14.5606 12.7786 14.9865L12.5951 15.5368L12.3034 16.4118L12.0299 17.2323C11.9601 17.4419 12.0146 17.6729 12.1708 17.8292C12.3271 17.9854 12.5581 18.0399 12.7677 17.9701L13.5882 17.6966L14.4632 17.4049L15.0135 17.2214L15.0136 17.2214C15.4394 17.0795 15.6524 17.0085 15.8527 16.913C16.0891 16.8004 16.3124 16.6623 16.5189 16.5013Z"
                            fill="currentColor" />
                        <path
                            d="M22.3665 10.6922C23.2112 9.84754 23.2112 8.47812 22.3665 7.63348C21.5219 6.78884 20.1525 6.78884 19.3078 7.63348L19.1806 7.76071C19.0578 7.88348 19.0022 8.05496 19.0329 8.22586C19.0522 8.33336 19.0879 8.49053 19.153 8.67807C19.2831 9.05314 19.5288 9.54549 19.9917 10.0083C20.4545 10.4712 20.9469 10.7169 21.3219 10.847C21.5095 10.9121 21.6666 10.9478 21.7741 10.9671C21.945 10.9978 22.1165 10.9422 22.2393 10.8194L22.3665 10.6922Z"
                            fill="currentColor" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C20.9812 19.6756 20.9997 17.8316 21 14.1801L18.1817 16.9984C17.9119 17.2683 17.691 17.4894 17.4415 17.6841C17.1491 17.9121 16.8328 18.1076 16.4981 18.2671C16.2124 18.4032 15.9159 18.502 15.5538 18.6225L13.2421 19.3931C12.4935 19.6426 11.6682 19.4478 11.1102 18.8898C10.5523 18.3318 10.3574 17.5065 10.607 16.7579L10.8805 15.9375L11.3556 14.5121L11.3775 14.4463C11.4981 14.0842 11.5968 13.7876 11.7329 13.5019C11.8924 13.1672 12.0879 12.8509 12.316 12.5586C12.5106 12.309 12.7317 12.0881 13.0017 11.8183L17.0081 7.81188L18.12 6.70004L18.2472 6.57282C18.9626 5.85741 19.9003 5.49981 20.838 5.5C20.6867 4.46945 20.3941 3.73727 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 9C7.25 8.58579 7.58579 8.25 8 8.25H14.5C14.9142 8.25 15.25 8.58579 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75H8C7.58579 9.75 7.25 9.41421 7.25 9ZM7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H10.5C10.9142 12.25 11.25 12.5858 11.25 13C11.25 13.4142 10.9142 13.75 10.5 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13ZM7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H9.5C9.91421 16.25 10.25 16.5858 10.25 17C10.25 17.4142 9.91421 17.75 9.5 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z"
                            fill="currentColor" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-700">Verifikasi</h4>
                    <p id="summary-verifikasi" class="text-3xl font-bold">0</p>
                    <p class="text-xs text-gray-500">Data yang sudah diverifikasi</p>
                </div>
            </div>
        @endif

        @if ($activeStatus->contains(3))
            <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
                <div class="flex items-center justify-center w-14 h-14 rounded-lg text-warning">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.75 10.0004V14.0004C1.75 16.8288 1.75 18.243 2.62868 19.1217C2.84602 19.339 3.09612 19.5026 3.39228 19.6257C3.38556 19.5812 3.37922 19.5366 3.37321 19.492C3.24986 18.5745 3.24992 17.4288 3.25 16.099L3.25 8.0003L3.25 7.90155V7.90155C3.24992 6.57182 3.24986 5.4261 3.37321 4.50861C3.37921 4.46399 3.38555 4.41944 3.39226 4.375C3.09611 4.49812 2.84601 4.6617 2.62868 4.87903C1.75 5.75771 1.75 7.17192 1.75 10.0004Z"
                            fill="currentColor" />
                        <path
                            d="M21.75 10.0004V14.0004C21.75 16.8288 21.75 18.243 20.8713 19.1217C20.654 19.339 20.4039 19.5026 20.1077 19.6257C20.1144 19.5812 20.1208 19.5366 20.1268 19.492C20.2501 18.5745 20.2501 17.4288 20.25 16.099V7.90156C20.2501 6.57183 20.2501 5.4261 20.1268 4.50861C20.1208 4.46399 20.1144 4.41944 20.1077 4.375C20.4039 4.49812 20.654 4.6617 20.8713 4.87903C21.75 5.75771 21.75 7.17192 21.75 10.0004Z"
                            fill="currentColor" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.62868 2.87868C4.75 3.75736 4.75 5.17157 4.75 8V16C4.75 18.8284 4.75 20.2426 5.62868 21.1213C6.50736 22 7.92157 22 10.75 22H12.75C15.5784 22 16.9926 22 17.8713 21.1213C18.75 20.2426 18.75 18.8284 18.75 16V8C18.75 5.17157 18.75 3.75736 17.8713 2.87868C16.9926 2 15.5784 2 12.75 2H10.75C7.92157 2 6.50736 2 5.62868 2.87868ZM8 17C8 16.5858 8.33579 16.25 8.75 16.25H11.75C12.1642 16.25 12.5 16.5858 12.5 17C12.5 17.4142 12.1642 17.75 11.75 17.75H8.75C8.33579 17.75 8 17.4142 8 17ZM8.75 12.25C8.33579 12.25 8 12.5858 8 13C8 13.4142 8.33579 13.75 8.75 13.75H14.75C15.1642 13.75 15.5 13.4142 15.5 13C15.5 12.5858 15.1642 12.25 14.75 12.25H8.75ZM8 9C8 8.58579 8.33579 8.25 8.75 8.25H14.75C15.1642 8.25 15.5 8.58579 15.5 9C15.5 9.41421 15.1642 9.75 14.75 9.75H8.75C8.33579 9.75 8 9.41421 8 9Z"
                            fill="currentColor" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-700">Proses</h4>
                    <p id="summary-proses" class="text-3xl font-bold">0</p>
                    <p class="text-xs text-gray-500">Data yang sedang diproses</p>
                </div>
            </div>
        @endif

        @if ($activeStatus->contains(4))
            <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
                <div class="flex items-center justify-center w-14 h-14 rounded-lg text-success">
                    <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447ZM10.5431 7.51724C10.8288 7.2173 10.8172 6.74256 10.5172 6.4569C10.2173 6.17123 9.74256 6.18281 9.4569 6.48276L7.14286 8.9125L6.5431 8.28276C6.25744 7.98281 5.78271 7.97123 5.48276 8.2569C5.18281 8.54256 5.17123 9.01729 5.4569 9.31724L6.59976 10.5172C6.74131 10.6659 6.9376 10.75 7.14286 10.75C7.34812 10.75 7.5444 10.6659 7.68596 10.5172L10.5431 7.51724ZM13 8.25C12.5858 8.25 12.25 8.58579 12.25 9C12.25 9.41422 12.5858 9.75 13 9.75H18C18.4142 9.75 18.75 9.41422 18.75 9C18.75 8.58579 18.4142 8.25 18 8.25H13ZM10.5431 14.5172C10.8288 14.2173 10.8172 13.7426 10.5172 13.4569C10.2173 13.1712 9.74256 13.1828 9.4569 13.4828L7.14286 15.9125L6.5431 15.2828C6.25744 14.9828 5.78271 14.9712 5.48276 15.2569C5.18281 15.5426 5.17123 16.0173 5.4569 16.3172L6.59976 17.5172C6.74131 17.6659 6.9376 17.75 7.14286 17.75C7.34812 17.75 7.5444 17.6659 7.68596 17.5172L10.5431 14.5172ZM13 15.25C12.5858 15.25 12.25 15.5858 12.25 16C12.25 16.4142 12.5858 16.75 13 16.75H18C18.4142 16.75 18.75 16.4142 18.75 16C18.75 15.5858 18.4142 15.25 18 15.25H13Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-700">Selesai</h4>
                    <p id="summary-selesai" class="text-3xl font-bold">0</p>
                    <p class="text-xs text-gray-500">Data pengajuan selesai</p>
                </div>
            </div>
        @endif

        @if ($activeStatus->contains(5))
        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-14 h-14 rounded-lg text-danger">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.5 2C8.67157 2 8 2.67157 8 3.5V4.5C8 5.32843 8.67157 6 9.5 6H14.5C15.3284 6 16 5.32843 16 4.5V3.5C16 2.67157 15.3284 2 14.5 2H9.5Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.87868 4.87694C4.44798 4.30764 5.24209 4.10719 6.5 4.03662V4.5C6.5 6.15685 7.84315 7.5 9.5 7.5H14.5C16.1569 7.5 17.5 6.15685 17.5 4.5V4.03662C18.7579 4.10719 19.552 4.30764 20.1213 4.87694C21 5.75562 21 7.16983 21 9.99826V15.9983C21 18.8267 21 20.2409 20.1213 21.1196C19.2426 21.9983 17.8284 21.9983 15 21.9983H9C6.17157 21.9983 4.75736 21.9983 3.87868 21.1196C3 20.2409 3 18.8267 3 15.9983V9.99826C3 7.16983 3 5.75562 3.87868 4.87694ZM12 13.4394L10.0303 11.4697C9.73744 11.1768 9.26256 11.1768 8.96967 11.4697C8.67678 11.7626 8.67678 12.2374 8.96967 12.5303L10.9394 14.5L8.96969 16.4697C8.6768 16.7626 8.6768 17.2374 8.96969 17.5303C9.26258 17.8232 9.73746 17.8232 10.0304 17.5303L12 15.5607L13.9696 17.5303C14.2625 17.8232 14.7374 17.8232 15.0303 17.5303C15.3232 17.2374 15.3232 16.7625 15.0303 16.4697L13.0607 14.5L15.0303 12.5303C15.3232 12.2375 15.3232 11.7626 15.0303 11.4697C14.7374 11.1768 14.2626 11.1768 13.9697 11.4697L12 13.4394Z"
                        fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Ditolak / Tidak Disetujui</h4>
                <p id="summary-ditolak" class="text-3xl font-bold">0</p>
                <p class="text-xs text-gray-500">Data pengajuan ditolak / tidak disetujui</p>
            </div>
        </div>
        @endif

    </div>


    <div class="panel mt-6">
        <h5 class="font-semibold text-lg dark:text-white-light mb-4">Data Riwayat Permintaan Layanan</h5>

        <div class="flex flex-wrap gap-3 mb-4">
            <input type="hidden" value="manajemen_permintaan" id="jenis_transaksi">
            <div class="w-64">
                <select id="filter-pemohon" class="select2-filter w-full"></select>
            </div>
            <div class="w-64">
                <select id="filter-penyedia" class="select2-filter w-full"></select>
            </div>
            <div class="w-64">
                <select id="filter-layanan" class="select2-filter w-full"></select>
            </div>
            <div class="w-64">
                <select id="filter-status" class="select2-filter w-full"></select>
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

    @include('components.js.permintaanLayanan')
</x-app-layout>
