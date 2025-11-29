<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Jenis Layanan']]" />
        {{-- Tombol Aksi di Atas --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            @can('tambah jenis layanan')
                {{-- ==== Modal Jenis Layanan ==== --}}
                <x-modals modal-id="modalJenisLayanan" title-id="modal-title" title-default="Tambah Jenis Layanan"
                    title-edit="Edit Jenis Layanan" form-id="JenisLayananForm" error-box-id="formError" open-event="open-modal"
                    edit-event="open-edit-modal" close-event="close-jenis-layanan-modal" button-label="Tambah Jenis Layanan">

                    <form x-ref="JenisLayananForm" class="space-y-5" id="JenisLayananForm" method="POST"
                        x-data="JenisLayananFormHandler" @submit.prevent="submitForm">
                        @csrf
                        <div>
                            <label for="nama">Nama</label>
                            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                                :value="old('nama')" required autocomplete="nama" placeholder="Masukkan Nama" />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger" @click="closeModal()">Batal</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Simpan</button>
                        </div>
                    </form>
                </x-modals>
            @endcan
        </div>
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Jenis Layanan</h5>
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

    @include('components.js.jenisLayanan')



</x-app-layout>
