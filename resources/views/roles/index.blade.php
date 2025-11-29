<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
    <x-breadcrumb :items="[['label' => 'User Management', 'url' => '/users'], ['label' => 'Role Management']]" />
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        {{-- === KIRI: TABEL ROLE === --}}
        <div class="w-full md:w-3/6">
            {{-- <div class="flex justify-end gap-5 mb-4">
                @can('tambah role')
                    <x-modals modal-id="modalRole" title-id="modal-title" title-default="Tambah Role" title-edit="Edit Role"
                        form-id="roleForm" error-box-id="formError" open-event="open-modal" edit-event="open-edit-modal"
                        close-event="close-role-modal" button-label="Tambah Role">
                        <form x-ref="roleForm" id="roleForm" class="space-y-5" x-data="roleFormHandler" method="POST"
                            @submit.prevent="submitForm">
                            @csrf

                            <div>
                                <label for="name">Nama Role</label>
                                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                    placeholder="Masukkan nama role" />
                                <div id="error-name">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label for="level">Level</label>
                                <select id="level" name="level"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="" disabled selected>Pilih Level</option>
                                    @for ($i = 10; $i <= 100; $i += 10)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <div id="error-level">
                                    <x-input-error :messages="$errors->get('level')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label for="jumlah_akun">Jumlah Akun</label>
                                <x-text-input id="jumlah_akun" name="jumlah_akun" type="number" min="1"
                                    class="block mt-1 w-full" placeholder="Masukkan jumlah akun" />
                                <div id="error-jumlah_akun">
                                    <x-input-error :messages="$errors->get('jumlah_akun')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label class="block mb-1">User dengan Role ini Wajib isi Perangkat Daerah</label>

                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="perangkat_daerah_id_required" value="1"
                                            class="form-radio text-primary">
                                        <span class="ml-2">Ya</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input type="radio" name="perangkat_daerah_id_required" value="0"
                                            class="form-radio text-primary">
                                        <span class="ml-2">Tidak</span>
                                    </label>
                                </div>

                                <div id="error-perangkat_daerah_id_required">
                                    <x-input-error :messages="$errors->get('perangkat_daerah_id_required')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label class="block mb-1">User dengan Role ini Wajib isi Penyedia Layanan</label>

                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="penyedia_layanan_id_required" value="1"
                                            class="form-radio text-primary">
                                        <span class="ml-2">Ya</span>
                                    </label>

                                    <label class="inline-flex items-center">
                                        <input type="radio" name="penyedia_layanan_id_required" value="0"
                                            class="form-radio text-primary">
                                        <span class="ml-2">Tidak</span>
                                    </label>
                                </div>

                                <div id="error-penyedia_layanan_id_required">
                                    <x-input-error :messages="$errors->get('penyedia_layanan_id_required')" class="mt-2" />
                                </div>
                            </div>


                            <div id="formError" class="text-red-500 text-sm"></div>

                            <div class="flex justify-end mt-6">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="$dispatch('close-role-modal')">Batal</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Simpan</button>
                            </div>
                        </form>
                    </x-modals>
                @endcan
            </div> --}}

            {{-- === Table Roles === --}}
            <div x-data="roleTable">
                <div class="panel">
                    <h5 class="font-semibold text-lg mb-4">Daftar Role</h5>
                    <table id="tableRoles" class="whitespace-wrap w-full"></table>
                </div>
            </div>
        </div>

        {{-- === KANAN: PANEL PERMISSION === --}}
        <div id="permissionPanel" class="w-full md:w-3/6 hidden">
            <div class="bg-white p-6 rounded-lg shadow min-h-[400px]">
                <h3 class="font-semibold text-lg mb-4 text-gray-800" id="roleTitle">
                    Pilih role untuk melihat permission
                </h3>
                <div id="permissionContent" class="text-gray-700"></div>
            </div>
        </div>
    </div>

    @include('components.js.roles')
</x-app-layout>
