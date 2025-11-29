<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Penyedia Layanan']]" />
        {{-- Tombol Aksi di Atas --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            @can('Penyedia Layanan;Tambah')
                <a href="{{ route('penyedia-layanan.create') }}" class="w-full sm:w-auto btn btn-sm btn-primary">Tambah Penyedia Layanan</a>
            @endcan
        </div>
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Penyedia Layanan</h5>
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

    @include('components.js.penyediaLayanan')
</x-app-layout>
