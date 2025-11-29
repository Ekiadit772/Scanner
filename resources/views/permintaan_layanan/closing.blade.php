<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Permintaan Layanan'], ['label' => 'Pengakhiran Permintaan Layanan']]" />

        {{-- <a href="{{ route('katalog-layanan.create') }}" class="btn btn-primary btn-sm">Tambah</a> --}}
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Data Pengakhiran Permintaan Layanan</h5>
            <table id="myTable2" class="whitespace-nowrap w-full"></table>
        </div>
    </div>

    <style>
        #myTable2 {
            table-layout: fixed;
            width: 100% !important;
        }

        #myTable2 td,
        #myTable2 th {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
            vertical-align: top;
        }

        .dataTable-container {
            overflow-x: hidden !important;
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

        #myTable2 td:nth-child(6),
        #myTable2 th:nth-child(6) {
            width: 150px !important;
        }

        #myTable2 td:nth-child(6) {
            display: -webkit-box !important;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 4.5em;
            line-height: 1.5em;
        }
    </style>

    @include('components.js.permintaanLayananVerifikasi')
</x-app-layout>
