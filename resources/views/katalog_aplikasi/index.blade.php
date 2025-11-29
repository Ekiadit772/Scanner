<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Katalog Aplikasi']]" />
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Katalog Aplikasi</h5>
            <table id="myTable2" class="whitespace-nowrap w-full"></table>
        </div>
    </div>

    <style>
        #myTable2 {
            table-layout: fixed !important;
        }

        #myTable2 thead th:first-child,
        #myTable2 tbody td:first-child {
            width: 5% !important;
        }
    </style>
    @include('components.js.katalogAplikasi')
</x-app-layout>
