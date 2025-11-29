<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Rekapitulasi Penggunaan Layanan']]" />
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Rekapitulasi Penggunaan Layanan</h5>
            <div class="flex flex-wrap justify-between items-center gap-3">
                <!-- Bagian kiri: select filter -->
                <div class="flex flex-wrap gap-3">
                    <div class="w-64">
                        <select id="filter-kelompok" class="filter-select w-full">
                            <option value="" selected disabled>Pilih Kelompok Layanan</option>
                            @foreach ($kelLayanans as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-64">
                        <select id="filter-opd-pengusul" class="filter-select w-full"></select>
                    </div>

                    <div class="w-64">
                        <select id="filter-opd-penyedia" class="filter-select w-full"></select>
                    </div>
                </div>

                <!-- Bagian kanan: search -->
                <div class="w-64">
                    <input type="text" id="search-layanan" class="form-input w-full"
                        placeholder="Cari nama layanan">
                </div>
            </div>
            <table id="myTable2" class="whitespace-nowrap w-full"></table>
        </div>
    </div>

    <style>
        table.table-checkbox thead tr th:first-child {
            width: 1px !important;
        }

        .select2-container .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
        }
    </style>

    @include('components.js.rekapitulasiPenggunaanLayanan')
</x-app-layout>
