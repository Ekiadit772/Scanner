<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Status Penanganan Insiden']]" />
    </div>

    <!-- Tabs Navigasi -->
    <div class="mb-6" x-data="{ tab: 'home' }">
        <ul class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
            @can('Satuan;Lihat')
                <li>
                    <a href="{{ route('satuan.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 hover:bg-primary hover:text-white">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5"
                                d="M16 4.00195C18.175 4.01406 19.3529 4.11051 20.1213 4.87889C21 5.75757 21 7.17179 21 10.0002V16.0002C21 18.8286 21 20.2429 20.1213 21.1215C19.2426 22.0002 17.8284 22.0002 15 22.0002H9C6.17157 22.0002 4.75736 22.0002 3.87868 21.1215C3 20.2429 3 18.8286 3 16.0002V10.0002C3 7.17179 3 5.75757 3.87868 4.87889C4.64706 4.11051 5.82497 4.01406 8 4.00195"
                                stroke="currentColor" stroke-width="1.5"></path>
                            <path d="M8 14H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M7 10.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path d="M9 17.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            <path
                                d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        <span>Satuan</span>
                    </a>
                </li>
            @endcan
            @can('Jenis Peran;Lihat')
                <li>
                    <a href="{{ route('jenis-peran.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 hover:bg-primary hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                            <path opacity="0.5"
                                d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                        <span>Jenis Peran</span>
                    </a>
                </li>
            @endcan
            @can('Jenis Insiden;Lihat')
                <li>
                    <a href="{{ route('jenis-insiden.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 hover:bg-primary hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16 4C18.175 4.01211 19.3529 4.10856 20.1213 4.87694C21 5.75562 21 7.16983 21 9.99826V15.9983C21 18.8267 21 20.2409 20.1213 21.1196C19.2426 21.9983 17.8284 21.9983 15 21.9983H9C6.17157 21.9983 4.75736 21.9983 3.87868 21.1196C3 20.2409 3 18.8267 3 15.9983V9.99826C3 7.16983 3 5.75562 3.87868 4.87694C4.64706 4.10856 5.82497 4.01211 8 4"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M14.5 11L9.50004 16M9.50002 11L14.5 16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>

                        <span>Jenis Insiden</span>
                    </a>
                </li>
            @endcan
            @can('Jenis Syarat;Lihat')
                <li>
                    <a href="{{ route('jenis-syarat.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 hover:bg-primary hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16 4.00195C18.175 4.01406 19.3529 4.11051 20.1213 4.87889C21 5.75757 21 7.17179 21 10.0002V16.0002C21 18.8286 21 20.2429 20.1213 21.1215C19.2426 22.0002 17.8284 22.0002 15 22.0002H9C6.17157 22.0002 4.75736 22.0002 3.87868 21.1215C3 20.2429 3 18.8286 3 16.0002V10.0002C3 7.17179 3 5.75757 3.87868 4.87889C4.64706 4.11051 5.82497 4.01406 8 4.00195"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M10.5 14L17 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 14H7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 10.5H7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 17.5H7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M10.5 10.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M10.5 17.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>


                        <span>Jenis Syarat</span>
                    </a>
                </li>
            @endcan
            @can('Kategori Perubahan;Lihat')
                <li>
                    <a href="{{ route('kategori-perubahan.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 hover:bg-primary hover:text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16 4.00195C18.175 4.01406 19.3529 4.11051 20.1213 4.87889C21 5.75757 21 7.17179 21 10.0002V16.0002C21 18.8286 21 20.2429 20.1213 21.1215C19.2426 22.0002 17.8284 22.0002 15 22.0002H9C6.17157 22.0002 4.75736 22.0002 3.87868 21.1215C3 20.2429 3 18.8286 3 16.0002V10.0002C3 7.17179 3 5.75757 3.87868 4.87889C4.64706 4.11051 5.82497 4.01406 8 4.00195"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M7 14.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 18H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>


                        <span>Kategori Perubahan</span>
                    </a>
                </li>
            @endcan
            @can('Status Penanganan Insiden;Lihat')
                <li>
                    <a href="{{ route('status-penanganan-insiden.index') }}"
                        class="px-4 py-2 flex items-center gap-2 rounded-md transition-all duration-150 bg-primary text-white">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12.0574 1.25H11.9426C9.63424 1.24999 7.82519 1.24998 6.41371 1.43975C4.96897 1.63399 3.82895 2.03933 2.93414 2.93414C2.03933 3.82895 1.63399 4.96897 1.43975 6.41371C1.24998 7.82519 1.24999 9.63422 1.25 11.9426V12.0574C1.24999 14.3658 1.24998 16.1748 1.43975 17.5863C1.63399 19.031 2.03933 20.1711 2.93414 21.0659C3.82895 21.9607 4.96897 22.366 6.41371 22.5603C7.82519 22.75 9.63423 22.75 11.9426 22.75H12.0574C14.3658 22.75 16.1748 22.75 17.5863 22.5603C19.031 22.366 20.1711 21.9607 21.0659 21.0659C21.9607 20.1711 22.366 19.031 22.5603 17.5863C22.75 16.1748 22.75 14.3658 22.75 12.0574V11.9426C22.75 9.63423 22.75 7.82519 22.5603 6.41371C22.366 4.96897 21.9607 3.82895 21.0659 2.93414C20.1711 2.03933 19.031 1.63399 17.5863 1.43975C16.1748 1.24998 14.3658 1.24999 12.0574 1.25ZM3.9948 3.9948C4.56445 3.42514 5.33517 3.09825 6.61358 2.92637C7.91356 2.75159 9.62177 2.75 12 2.75C14.3782 2.75 16.0864 2.75159 17.3864 2.92637C18.6648 3.09825 19.4355 3.42514 20.0052 3.9948C20.5749 4.56445 20.9018 5.33517 21.0736 6.61358C21.2484 7.91356 21.25 9.62177 21.25 12C21.25 14.3782 21.2484 16.0864 21.0736 17.3864C20.9018 18.6648 20.5749 19.4355 20.0052 20.0052C19.4355 20.5749 18.6648 20.9018 17.3864 21.0736C16.0864 21.2484 14.3782 21.25 12 21.25C9.62177 21.25 7.91356 21.2484 6.61358 21.0736C5.33517 20.9018 4.56445 20.5749 3.9948 20.0052C3.42514 19.4355 3.09825 18.6648 2.92637 17.3864C2.75159 16.0864 2.75 14.3782 2.75 12C2.75 9.62177 2.75159 7.91356 2.92637 6.61358C3.09825 5.33517 3.42514 4.56445 3.9948 3.9948Z"
                                fill="currentColor" />
                        </svg>

                        <span>Status Penanganan Insiden</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>

    <div x-data="multipleTable">
        <div class="panel mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Status Penanganan Insiden</h5>
                @can('Status Penanganan Insiden;Tambah')
                    {{-- ==== Modal Status Penanganan Insiden ==== --}}
                    <x-modals modal-id="modalStatusPenangananInsiden" title-id="modal-title"
                        title-default="Tambah Status Penanganan Insiden" title-edit="Edit Status Penanganan Insiden"
                        form-id="StatusPenangananInsidenForm" error-box-id="formError" open-event="open-modal"
                        edit-event="open-edit-modal" close-event="close-status-penanganan-insiden-modal"
                        button-label="Tambah Status Penanganan Insiden">

                        <form x-ref="StatusPenangananInsidenForm" class="space-y-5" id="StatusPenangananInsidenForm" method="POST"
                            x-data="StatusPenangananInsidenFormHandler" @submit.prevent="submitForm">
                            @csrf
                            <div>
                                <label for="nama">Nama</label>
                                <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama"
                                    :value="old('nama')" required autocomplete="nama" placeholder="Masukkan Nama" />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>

                            <div>
                                <label for="keterangan">Keterangan</label>
                                <textarea id="keterangan" name="keterangan"
                                    class="form-input placeholder:text-white-dark block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="6" placeholder="Masukkan keterangan">{{ old('keterangan') }}</textarea>
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>

                            <div class="flex justify-end items-center mt-8">
                                <button type="button" class="btn btn-outline-danger" @click="closeModal()">Batal</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Simpan</button>
                            </div>
                        </form>
                    </x-modals>
                @endcan
            </div>
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

    @include('components.js.statusPenangananInsiden')
</x-app-layout>
