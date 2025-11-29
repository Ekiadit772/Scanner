<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Kelompok Layanan']]" />
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            @can('Kelompok Layanan;Tambah')
                {{-- ==== Modal Kelompok Layanan ==== --}}
                <x-modals modal-id="modalKelompokLayanan" title-id="modal-title" title-default="Tambah Kelompok Layanan"
                    title-edit="Edit Kelompok Layanan" form-id="KelompokLayananForm" error-box-id="formError"
                    submit-handler="KelompokLayananFormHandler" open-event="open-modal" edit-event="open-edit-modal"
                    close-event="close-modal" button-label="Tambah Kelompok Layanan">

                    <form x-ref="KelompokLayananForm" class="space-y-5" id="KelompokLayananForm" method="POST"
                        x-data="KelompokLayananFormHandler" @submit.prevent="submitForm">
                        @csrf

                        <div>
                            <label for="nama">Nama</label>
                            <x-text-input id="nama" class="block mt-1 w-full" type="nama" name="nama"
                                :value="old('nama')" autocomplete="nama" placeholder="Masukkan nama" />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                        <div>
                            <label for="deskripsi">Deskripsi</label>
                            <x-text-input id="deskripsi" class="block mt-1 w-full" type="deskripsi" name="deskripsi"
                                :value="old('deskripsi')" autocomplete="deskripsi" placeholder="Masukkan deskripsi" />
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
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
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Kelompok Layanan</h5>
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
    </script>

    @include('components.js.kelompokLayanan')
</x-app-layout>
