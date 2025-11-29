<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Permintaan Layanan'], ['label' => 'Data Pendaftaran Layanan yang Yang Belum Diproses']]" />

        {{-- <a href="{{ route('katalog-layanan.create') }}" class="btn btn-primary btn-sm">Tambah</a> --}}
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Data Proses Pendaftaran Layanan</h5>
            <table id="myTable2" class="whitespace-nowrap w-full"></table>
        </div>
    </div>

    <style>
        table.table-checkbox thead tr th:first-child {
            width: 1px !important;
        }
    </style>

    @include('components.js.katalogLayananVerifikasi')
</x-app-layout>
