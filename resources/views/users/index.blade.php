<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'User Management']]" />
        {{-- Tombol Aksi di Atas --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            @can('Role Pengguna Aplikasi;Lihat')
                <a href="{{ route('roles.index') }}" class="w-full sm:w-auto btn btn-sm btn-success">Role & Permission</a>
            @endcan
            @can('Pengguna Aplikasi;Tambah')
                {{-- ==== Modal User ==== --}}
                <x-modals modal-id="modalUser" title-id="modal-title" title-default="Tambah Pengguna" title-edit="Edit Pengguna"
                    form-id="userForm" error-box-id="formError" submit-handler="userFormHandler" open-event="open-modal"
                    edit-event="open-edit-modal" close-event="close-modal" button-label="Tambah Pengguna">

                    <div x-data="{ isView: false }" x-on:open-modal.window="isView = false"
                        x-on:open-edit-modal.window="isView = false" x-on:open-detail.window="isView = true">

                        <form x-ref="userForm" class="space-y-5" id="userForm" method="POST">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- NAME (full row) --}}
                                <div class="md:col-span-2">
                                    <label for="name">Nama</label>
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name')" autocomplete="name" placeholder="Enter name"
                                        x-bind:readonly="isView" x-bind:disabled="isView" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                {{-- NIP + PHONE --}}
                                <div>
                                    <label for="nip">NIP</label>
                                    <x-text-input id="nip" class="block mt-1 w-full" type="number" name="nip"
                                        :value="old('nip')" autocomplete="nip" placeholder="Enter nip"
                                        x-bind:readonly="isView" x-bind:disabled="isView" />
                                    <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="phone">No Telp</label>
                                    <x-text-input id="phone" class="block mt-1 w-full" type="number" name="phone"
                                        :value="old('phone')" autocomplete="phone" placeholder="Enter phone"
                                        x-bind:readonly="isView" x-bind:disabled="isView" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                {{-- EMAIL --}}
                                <div class="md:col-span-2">
                                    <label for="email">Email</label>
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" autocomplete="email" placeholder="Enter Email"
                                        x-bind:readonly="isView" x-bind:disabled="isView" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                {{-- PASSWORD (hanya saat tambah, full row) --}}
                                <div x-show="!isEdit && !isView" class="md:col-span-2">
                                    <label for="password">Password</label>
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                        :value="old('password')" autocomplete="new-password" placeholder="Enter password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    <div id="formError" class="text-red-500 mt-2 text-sm"></div>
                                </div>

                                {{-- ROLE  --}}
                                <div class="md:col-span-2">
                                    <label for="role">Role</label>
                                    <select id="role" name="role"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm"
                                        :disabled="isView">
                                        <option value="" disabled selected>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                </div>

                                {{-- PERANGKAT DAERAH --}}
                                <div class="md:col-span-2" id="wrapper_perangkat_daerah">
                                    <label for="perangkat_daerah_id">Perangkat Daerah</label>
                                    <select id="perangkat_daerah_id" name="perangkat_daerah_id"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm"
                                        :disabled="isView">
                                        <option value="" disabled selected>Pilih Perangkat Daerah</option>
                                        <option value="-1">Semua Dinas</option>
                                        @foreach ($opds as $opd)
                                            <option value="{{ $opd->id }}">{{ $opd->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- BIDANG --}}
                                <div class="md:col-span-2" id="wrapper_bidang">
                                    <label for="penyedia_layanan_id">Bidang</label>
                                    <select id="penyedia_layanan_id" name="penyedia_layanan_id"
                                        class="select2 border-gray-300 rounded-md text-sm w-full" :disabled="isView">
                                        <option value="">Pilih Bidang</option>
                                    </select>
                                </div>

                            </div>

                            <div class="flex justify-end items-center mt-8">
                                <button type="button" class="btn btn-outline-danger" @click="closeModal()">Batal</button>
                                <button type="submit" x-show="!isView"
                                    class="btn btn-primary ltr:ml-4 rtl:mr-4">Simpan</button>
                            </div>
                        </form>
                    </div>
                </x-modals>
            @endcan
        </div>
    </div>


    <div class="panel mt-6">
        <h5 class="font-semibold text-lg dark:text-white-light mb-4">Daftar Pengguna</h5>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">
            <div class="flex flex-wrap gap-3">
                <div class="w-56">
                    <select id="filter_role" class="select2 w-full">
                        <option value="">Filter Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-56">
                    <select id="filter_opd" class="select2 w-full">
                        <option value="">Filter Perangkat Daerah</option>
                        @foreach ($opds as $opd)
                            <option value="{{ $opd->nama }}">{{ $opd->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="w-64">
                <input type="text" id="search" class="form-input w-full" placeholder="Cari nama pengguna">
            </div>
        </div>

        <table id="myTable2" class="whitespace-normal break-words"></table>
    </div>


    <style>
        table.table-checkbox thead tr th:first-child {
            width: 1px !important;
        }

        .select2-container .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }

        #myTable2 td,
        #myTable2 th {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        .is-invalid {
            border-color: #dc2626 !important;
        }
    </style>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script>
        const roleConfig = @json($roles->keyBy('name'));
    </script>

    <script>
        $(document).ready(function() {
            // ===== INIT SELECT2 PERANGKAT DAERAH =====
            $('#perangkat_daerah_id').select2({
                placeholder: "Pilih Perangkat Daerah",
                allowClear: true,
                width: '100%'
            });

            // ===== INIT SELECT2 BIDANG (default: belum pilih PD) =====
            $('#penyedia_layanan_id').select2({
                width: '100%',
                placeholder: "Pilih Bidang",
                data: [{
                    id: '',
                    text: 'Pilih perangkat daerah terlebih dahulu',
                    disabled: true
                }]
            });

            // ===== PERANGKAT DAERAH CHANGE -> LOAD BIDANG =====
            $('#perangkat_daerah_id').on('change', function() {
                let perangkatDaerahId = $(this).val();

                $('#penyedia_layanan_id').empty().trigger('change');

                if (!perangkatDaerahId) {
                    $('#penyedia_layanan_id').select2({
                        width: '100%',
                        placeholder: "Pilih Bidang",
                        data: [{
                            id: '',
                            text: 'Pilih perangkat daerah terlebih dahulu',
                            disabled: true
                        }]
                    });
                    return;
                }

                $('#penyedia_layanan_id').select2({
                    width: '100%',
                    ajax: {
                        url: `{{ url('/api/users/get-bidang') }}/` + perangkatDaerahId,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: (data) => {
                            const results = data.results || data;

                            return {
                                results: [{
                                        id: -1,
                                        text: 'Semua Bidang'
                                    },
                                    ...results
                                ]
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Pilih Bidang',
                });
            });

            // ===== RESET FORM & ERROR (digunakan saat close-modal & open-modal create) =====
            function resetUserForm() {
                const $form = $('#userForm');

                if ($form.length) {
                    $form[0].reset();
                    $form.removeAttr('data-id');
                }

                // hapus error & border merah
                $('.error-text').remove();
                $('.is-invalid').removeClass('is-invalid');

                // reset role (bukan select2, jadi cukup val(""))
                $('#role').val('');

                // reset select2 PD
                if ($('#perangkat_daerah_id').data('select2')) {
                    $('#perangkat_daerah_id').val(null).trigger('change');
                }

                // reset select2 bidang
                if ($('#penyedia_layanan_id').data('select2')) {
                    $('#penyedia_layanan_id').val(null).trigger('change');
                    $('#penyedia_layanan_id').empty().select2({
                        width: '100%',
                        placeholder: "Pilih Bidang",
                        data: [{
                            id: '',
                            text: 'Pilih perangkat daerah terlebih dahulu',
                            disabled: true
                        }]
                    });
                }

                // hide wrapper PD & Bidang (default saat tambah user baru)
                $('#wrapper_perangkat_daerah').hide();
                $('#wrapper_bidang').hide();

                $('#modal-title').text('Tambah Pengguna');
            }

            // close-modal dari komponen -> reset form
            $(window).on('close-modal', function() {
                resetUserForm();
            });

            // open-modal (tambah pengguna) -> reset form
            $(window).on('open-modal', function() {
                resetUserForm();
            });

            // open-edit-modal -> JANGAN reset form, cukup hilangkan error lama
            $(window).on('open-edit-modal', function() {
                $('.error-text').remove();
                $('.is-invalid').removeClass('is-invalid');
                // wrapper PD/Bidang akan dikontrol oleh roleConfig + editUser()
            });

            // ===== ROLE CHANGE -> TAMPILKAN / SEMBUNYIKAN PD & BIDANG =====
            $('#role').on('change', function() {
                let roleName = $(this).val();
                let config = roleConfig[roleName];

                if (!config) {
                    $('#wrapper_perangkat_daerah').hide();
                    $('#wrapper_bidang').hide();
                    return;
                }

                // PERANGKAT DAERAH
                if (config.perangkat_daerah_id_required == 1) {
                    $('#wrapper_perangkat_daerah').show();
                } else {
                    $('#wrapper_perangkat_daerah').hide();
                    $('#perangkat_daerah_id').val(null).trigger('change');
                }

                // PENYEDIA LAYANAN / BIDANG
                if (config.penyedia_layanan_id_required == 1) {
                    $('#wrapper_bidang').show();
                } else {
                    $('#wrapper_bidang').hide();
                    $('#penyedia_layanan_id').val(null).trigger('change');
                }
            });
        });
    </script>

    @include('components.js.users')
</x-app-layout>
