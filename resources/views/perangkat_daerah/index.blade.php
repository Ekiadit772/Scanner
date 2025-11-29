<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Perangkat Daerah']]" />

        {{-- Tombol Aksi di Atas --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            @can('Perangkat Daerah;Tambah')
                {{-- ==== Modal Perangkat Daerah ==== --}}
                <x-modals modal-id="modalPerangkatDaerah" title-id="modal-title" title-default="Tambah Perangkat Daerah"
                    title-edit="Edit Perangkat Daerah" form-id="PerangkatDaerahForm" error-box-id="formError"
                    open-event="open-modal" edit-event="open-edit-modal" close-event="close-perangkat-daerah-modal"
                    button-label="Tambah Perangkat Daerah">
                    <form x-ref="PerangkatDaerahForm" class="space-y-5" id="PerangkatDaerahForm" method="POST"
                        x-data="PerangkatDaerahFormHandler" @submit.prevent="submitForm">
                        @csrf
                        <div>
                            <label for="kode">Kode</label>
                            <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                :value="old('kode')" required autocomplete="kode" placeholder="Masukkan Kode" />
                            <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                        </div>
                        <div>
                            <label for="nama">Nama</label>
                            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                                :value="old('nama')" required autocomplete="nama" placeholder="Masukkan Nama" />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>
                        <div>
                            <label for="kadis_nama">Nama Kepala Dinas</label>
                            <x-text-input id="kadis_nama" class="block mt-1 w-full" type="text" name="kadis_nama"
                                required autocomplete="kadis_nama" placeholder="Masukkan Nama Kepala Dinas" />
                        </div>

                        <div>
                            <label for="kadis_nip">NIP Kepala Dinas</label>
                            <x-text-input id="kadis_nip" class="block mt-1 w-full" type="number" name="kadis_nip" required
                                autocomplete="kadis_nip" placeholder="Masukkan NIP Kepala Dinas" />
                        </div>

                        <div>
                            <label for="kadis_tte">File TTE Kepala Dinas</label>
                            <input id="kadis_tte" type="file" name="kadis_tte"
                                class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary" />
                            <p class="text-xs text-gray-500 mt-1">Format: PNG, JPG, JPEG, PDF</p>
                            <img id="previewGambar" class="w-40 h-auto mt-3 rounded border" style="display:none;">
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
            <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Perangkat Daerah</h5>
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
    @include('components.js.perangkatDaerah')
</x-app-layout>
