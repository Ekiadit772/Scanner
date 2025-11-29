<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Detail Perubahan Layanan']]" />

    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">

            <form class="space-y-5 px-0 sm:px-10" id="formManajemenPerubahan">
                @csrf
                @method('PUT')

                <input type="hidden" name="" id="idPerubahan" value="{{ $hashedAgain }}">

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Informasi Umum</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col lg:flex-row lg:space-x-6 mb-3">
                    <div class="flex flex-col sm:flex-row sm:items-center w-full">
                        <label class="sm:w-44 text-sm font-medium">No Antrian</label>
                        <div class="flex-1">
                            <x-text-input id="no_antrian" class="w-full" type="text" name="no_antrian"
                                :value="old('no_antrian', $perubahan->no_antrian)" readonly />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center w-full mt-3 lg:mt-0">
                        <label class="sm:w-20 text-sm font-medium">Tanggal</label>
                        <div class="flex-1">
                            <x-text-input id="tanggal" class="w-full" type="date" name="tanggal" :value="old('tanggal', $perubahan->tanggal)"
                                readonly />
                        </div>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                    <label class="sm:w-44 text-sm font-medium">Penyedia Layanan</label>
                    <div class="flex-1">
                        <select id="penyedia_layanan" name="perangkat_daerah_id"
                            class="select2 border-gray-300 rounded-md text-sm w-full">
                            <option value="">Pilih Perangkat Daerah</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                    <label class="sm:w-44 text-sm font-medium">Bidang</label>
                    <div class="flex-1">
                        <select id="bidang" name="penyedia_layanan_id"
                            class="select2 border-gray-300 rounded-md text-sm w-full">
                            <option value="">Pilih Bidang</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                    <label class="sm:w-44">Nama Layanan</label>
                    <div class="flex-1">
                        <select id="nama_layanan" name="layanan_id"
                            class="select2 border-gray-300 rounded-md text-sm w-full">
                            <option value="">Pilih Layanan</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                    <label for="persyaratan" class="sm:w-44 mt-2">Persyaratan</label>
                    <div id="persyaratan_list" class="flex-1 space-y-2" data-loaded="0"></div>
                    <x-input-error :messages="$errors->get('persyaratan')" class="mt-2" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center w-full mb-3">
                    <label for="kategori_perubahan_id" class="sm:w-44 text-sm mt-1 font-medium">
                        Kategori Perubahan
                    </label>
                    <div class="flex-1">
                        <select id="kategori_perubahan_id" name="kategori_perubahan_id"
                            class="select2 border-gray-300 rounded-md text-sm w-full">
                            <option value="">Pilih Kategori Perubahan</option>
                        </select>
                    </div>
                </div>

                {{-- Perangkat Daerah --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label class="sm:w-44">Nama Perangkat Daerah Pengusul</label>
                    <div class="flex-1">
                        <select id="perangkat_daerah_user" class="select2" disabled></select>
                    </div>
                </div>

                {{-- Unit Kerja & Jabatan --}}
                <div class="flex flex-col sm:flex-row sm:space-x-6">

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                        <label class="sm:w-44">Bidang / Unit Kerja Pengusul</label>
                        <div class="flex-1">
                            <input type="text" class="w-full border-gray-300 rounded-md text-sm"
                                value="{{ $perubahan->unit_kerja_pemohon }}" disabled readonly>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                        <label class="sm:w-20">Jabatan</label>
                        <div class="flex-1">
                            <input type="text" class="w-full border-gray-300 rounded-md text-sm"
                                value="{{ $perubahan->jabatan_pemohon }}" disabled readonly>
                        </div>
                    </div>
                </div>

                {{-- Section Rincian Perubahan --}}
                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Rincian Perubahan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                {{-- Judul Perubahan --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Judul Perubahan</label>
                    <div class="flex-1">
                        <input type="text" class="w-full border-gray-300 rounded-md text-sm"
                            value="{{ $perubahan->judul_perubahan }}" disabled readonly>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Deskripsi Singkat</label>
                    <div class="flex-1">
                        <textarea rows="3" class="w-full border-gray-300 rounded-md text-sm" disabled readonly>{{ $perubahan->deskripsi }}</textarea>
                    </div>
                </div>

                {{-- Peta SPBE --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Perubahan terdapat pada peta rencana SPBE</label>
                    <div class="flex items-center gap-6 flex-1">
                        <label class="flex items-center gap-2">
                            <input type="radio" disabled class="form-checkbox"
                                {{ $perubahan->is_in_peta_spbe ? 'checked' : '' }}> Ya
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" disabled class="form-checkbox"
                                {{ !$perubahan->is_in_peta_spbe ? 'checked' : '' }}>
                            Tidak
                        </label>
                    </div>
                </div>

                {{-- Jenis Perubahan --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Jenis Perubahan</label>
                    <div class="flex items-center gap-6 flex-1">
                        <label class="flex items-center gap-2">
                            <input type="radio" class="form-checkbox" disabled
                                {{ $perubahan->jenis_perubahan == 'Minor' ? 'checked' : '' }}> Minor
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" class="form-checkbox" disabled
                                {{ $perubahan->jenis_perubahan == 'Mayor' ? 'checked' : '' }}> Mayor
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" class="form-checkbox" disabled
                                {{ $perubahan->jenis_perubahan == 'Darurat' ? 'checked' : '' }}> Darurat
                        </label>
                    </div>
                </div>

                {{-- Latar Belakang --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Latar Belakang</label>
                    <div class="flex-1">
                        <textarea rows="3" class="w-full border-gray-300 rounded-md text-sm" disabled readonly>{{ $perubahan->latar_belakang }}</textarea>
                    </div>
                </div>

                {{-- Tujuan --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44">Tujuan Perubahan</label>
                    <div class="flex-1">
                        <input type="text" class="w-full border-gray-300 rounded-md text-sm"
                            value="{{ $perubahan->tujuan_perubahan }}" disabled readonly>
                    </div>
                </div>

                {{-- Area Perubahan --}}
                <div class="flex flex-col sm:flex-row sm:items-start mb-3 w-full">
                    <label class="sm:w-44">Area Perubahan</label>
                    <div id="area-perubahan-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1"></div>
                </div>

                {{-- Jadwal Perubahan --}}
                <div class="flex flex-col sm:flex-row sm:items-start mb-3 w-full mt-5">
                    <label class="sm:w-44">Jadwal Perubahan</label>

                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm mb-1">Mulai :</label>
                            <input type="date" class="w-full border-gray-300 rounded-md text-sm"
                                value="{{ $perubahan->jadwal_mulai }}" disabled readonly>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Selesai :</label>
                            <input type="date" class="w-full border-gray-300 rounded-md text-sm"
                                value="{{ $perubahan->jadwal_selesai }}" disabled readonly>
                        </div>
                    </div>
                </div>

                {{-- Waktu Downtime --}}
                <div class="flex flex-col sm:flex-row sm:items-start mb-5 w-full">
                    <label class="sm:w-44">Waktu Downtime (Jika Ada)</label>

                    <div class="flex-1 flex flex-col gap-3">
                        <div class="flex items-center gap-6">

                            {{-- Checkbox Tidak Ada --}}
                            <label class="flex items-center gap-2">
                                <input type="checkbox" disabled {{ !$perubahan->is_downtime ? 'checked' : '' }}>
                                Tidak Ada
                            </label>

                            {{-- Checkbox Ada --}}
                            <label class="flex items-center gap-2">
                                <input type="checkbox" disabled {{ $perubahan->is_downtime ? 'checked' : '' }}>
                                Ada Durasi :

                                <input type="text" class="border-gray-300 rounded-md text-sm"
                                    value="{{ $perubahan->downtime }}" disabled readonly>
                            </label>

                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
                    <div class="lg:col-span-1 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Status Permohonan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>

                    <div class="lg:col-span-2 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Analisis Perubahan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="sticky top-36 border rounded-lg p-4 bg-white shadow-sm">
                            <x-timeline-status :logs="$perubahan->riwayatStatus" />
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                                <label for="no_permohonan" class="sm:w-44 text-sm mt-1 font-medium">No
                                    Permohonan</label>
                                <div class="flex-1">
                                    <x-text-input id="no_permohonan" class="w-full" type="text"
                                        name="no_permohonan" :value="old('no_permohonan')" placeholder="Isi Nomor Permohonan" />
                                    <x-input-error :messages="$errors->get('no_permohonan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="dampak_perubahan" class="sm:w-44 mt-2">Dampak Perubahan</label>
                                <div class="flex-1">
                                    <textarea id="dampak_perubahan" name="dampak_perubahan" rows="2"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                        placeholder="Masukkan dampak perubahan">{{ old('dampak_perubahan') }}</textarea>
                                    <x-input-error :messages="$errors->get('dampak_perubahan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Tingkat Dampak Perubahan
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tingkat_dampak" value="Tinggi" class="form-checkbox"
                                        {{ old('tingkat_dampak') == 'Tinggi' ? 'checked' : '' }}>
                                    Tinggi
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tingkat_dampak" value="Sedang" class="form-checkbox"
                                        {{ old('tingkat_dampak') == 'Sedang' ? 'checked' : '' }}>
                                    Sedang
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tingkat_dampak" value="Rendah" class="form-checkbox"
                                        {{ old('tingkat_dampak') == 'Rendah' ? 'checked' : '' }}>
                                    Rendah
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Kesiapan Personil
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_personil" value="Tinggi"
                                        class="form-checkbox"
                                        {{ old('kesiapan_personil') == 'Tinggi' ? 'checked' : '' }}>
                                    Tinggi
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_personil" value="Sedang"
                                        class="form-checkbox"
                                        {{ old('kesiapan_personil') == 'Sedang' ? 'checked' : '' }}>
                                    Sedang
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_personil" value="Rendah"
                                        class="form-checkbox"
                                        {{ old('kesiapan_personil') == 'Rendah' ? 'checked' : '' }}>
                                    Rendah
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="kesiapan_personil_catatan" class="form-input"
                                    {{ old('kesiapan_personil_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Kesiapan Organisasi
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_organisasi" value="Tinggi"
                                        class="form-checkbox"
                                        {{ old('kesiapan_organisasi') == 'Tinggi' ? 'checked' : '' }}>
                                    Tinggi
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_organisasi" value="Sedang"
                                        class="form-checkbox"
                                        {{ old('kesiapan_organisasi') == 'Sedang' ? 'checked' : '' }}>
                                    Sedang
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesiapan_organisasi" value="Rendah"
                                        class="form-checkbox"
                                        {{ old('kesiapan_organisasi') == 'Rendah' ? 'checked' : '' }}>
                                    Rendah
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="kesiapan_organisasi_catatan" class="form-input"
                                    {{ old('kesiapan_organisasi_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="risiko_potensial" class="sm:w-44 mt-2">Risiko Potensial</label>
                                <div class="flex-1">
                                    <textarea id="risiko_potensial" name="risiko_potensial" rows="2"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                        placeholder="Masukkan risiko potensial">{{ old('risiko_potensial') }}</textarea>
                                    <x-input-error :messages="$errors->get('risiko_potensial')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="rencana_mitigasi" class="sm:w-44 mt-2">Rencana Mitigasi
                                    Risiko</label>
                                <div class="flex-1">
                                    <textarea id="rencana_mitigasi" name="rencana_mitigasi" rows="2"
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                        placeholder="Masukkan rencana mitigasi risiko">{{ old('rencana_mitigasi') }}</textarea>
                                    <x-input-error :messages="$errors->get('rencana_mitigasi')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                            <label for="keterangan_status" class="sm:w-44 text-sm font-medium">Komentar
                                (Opsional)</label>
                            <div class="flex-1">
                                <textarea id="keterangan_status" class="form-textarea border rounded p-2" name="keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ showModal: false, keteranganDitolak: '' }">

                    <div x-show="showModal" x-cloak
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                            <h2 class="text-lg font-semibold mb-4">Tolak Permintaan</h2>
                            <textarea x-model="keteranganDitolak" id="keteranganDitolak" placeholder="Masukkan keterangan penolakan"
                                class="w-full border rounded-md p-2 mb-4"></textarea>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showModal = false"
                                    class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Batal</button>

                                <button type="button" @click="submitDitolak('{{ $hashedAgain }}')"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Kirim</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'Disetujui')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Setujui
                        </button>

                        <a href="{{ route('perubahan-layanan.persetujuan') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>

                        <button type="button" @click="showModal = true"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Tolak
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>
    <div id="modalSyarat" class="hidden fixed inset-0 z-50 bg-black/60 overflow-y-auto">
        <div class="min-h-screen flex justify-center items-start py-20 px-4">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">

                <div id="modalSyaratContent"></div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary btnCloseSyaratModal">Batal</button>
                    <button type="button" class="btn btn-primary btnSaveSyarat">Simpan Syarat</button>
                </div>

            </div>
        </div>
    </div>

    <style>
        .form-checkbox:checked[disabled] {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
    </style>


    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @php
        $syaratDataArray = $syaratExisting ?? [];
    @endphp
    <script>
        $(document).ready(function() {
            let syaratData = @json($syaratExisting ?? []);

            let selectedArea = @json($hashedAreaPerubahan);

            fetch(`{{ url('/api/area-perubahan/get-area-perubahan-data') }}`)
                .then(response => response.json())
                .then(res => {
                    let data = res.data;
                    let container = document.getElementById('area-perubahan-container');
                    container.innerHTML = '';

                    data.forEach(item => {
                        let nama = item[0];
                        let aktif = item[1];
                        let hashedId = item[2];

                        if (aktif == 1) {
                            let checked = selectedArea.includes(hashedId) ? 'checked' : '';

                            container.innerHTML += `
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" disabled ${checked}>
                                    ${nama}
                                </label>
                            `;
                        }
                    });
                });

            // Perangkat Daerah (Select2 Readonly)
            $('#perangkat_daerah_user').select2({
                width: "100%",
                disabled: true
            });

            $('.select2').select2({
                width: '100%',
                disabled: true
            });

            function setSelect2Value(selector, id, text) {
                if (id && text) {
                    let option = new Option(text, id, true, true);
                    $(selector).append(option).trigger('change');
                }
            }

            setSelect2Value('#perangkat_daerah_user', '{{ $perubahan->perangkat_daerah_pemohon_id }}',
                '{{ $perubahan->perangkatPemohon->nama ?? '' }}');

            setSelect2Value('#penyedia_layanan', '{{ $perubahan->perangkat_daerah_id }}',
                '{{ $perubahan->perangkatDaerah->nama ?? '' }}');


            setSelect2Value('#bidang', '{{ $perubahan->penyedia_layanan_id }}',
                '{{ $perubahan->penyediaLayanan->nama_bidang ?? '' }}');


            setSelect2Value('#nama_layanan', '{{ $perubahan->layanan_id }}',
                '{{ $perubahan->layanan->nama ?? '' }}');

            setSelect2Value('#kategori_perubahan_id', '{{ $perubahan->layanan_id }}',
                '{{ $perubahan->kategoriPerubahan->nama ?? '' }}');

            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            const jenisSyaratToFormType = {
                1: 1,
                2: 2,
                3: 3,
                4: 4,
                5: 4,
                6: 5,
                7: 5,
                8: 5,
                9: 5,
                11: 5,
                10: 6
            };

            function loadPersyaratan(layananId) {
                let $list = $('#persyaratan_list');
                $list.html('<p class="text-gray-400 italic">Memuat persyaratan...</p>');
                if (!layananId) return;

                $.getJSON(
                    `{{ url('/api/perubahan-layanan/get-syarat') }}/${layananId}?perubahan_id={{ $perubahan->id }}`,
                    function(data) {
                        if (!data.length) {
                            $list.html(
                                '<p class="text-gray-400 italic">Tidak ada persyaratan untuk layanan ini</p>'
                            );
                        } else {
                            let html = '';
                            data.forEach((item, index) => {
                                let formTypeId = jenisSyaratToFormType[item.jenis_syarat_id];
                                let isChecked = item.is_approve ? 'checked' : '';

                                html += `
                                ${index===0?`<table class="min-w-full text-sm border-collapse">
                                                                                                                                                                                                <thead class="bg-gray-50 border-b">
                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                        <th class="px-3 py-2 text-left">No</th>
                                                                                                                                                                                                        <th class="px-3 py-2 text-left">Nama Persyaratan</th>
                                                                                                                                                                                                        <th class="px-3 py-2 text-left">Aksi</th>
                                                                                                                                                                                                        <th class="px-3 py-2 text-left">Disetujui</th>
                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                </thead>
                                                                                                                                                                                                <tbody>`:''}
                                <tr>
                                    <td class="px-3 py-2">${index+1}</td>
                                    <td class="px-3 py-2">${item.nama}</td>
                                    <td class="px-3 py-2">
                                        <button type="button" class="btnOpenSyaratModal text-blue-600"
                                            data-syarat-id="${item.jenis_syarat_id}"
                                            data-layanan-syarat-id="${item.id}">
                                            Lihat Syarat
                                        </button>
                                    </td>
                                    <td class="px-3 py-2">
                                        <label class="w-12 h-6 relative">
                                            <input
                                                type="checkbox"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer checkbox-approve"
                                                data-perubahan-id="{{ $perubahan->id }}"
                                                data-current-status="{{ $currentStatus }}"
                                                data-jenis-syarat-id="${item.jenis_syarat_id}"
                                                data-layanan-syarat-id="${item.id}"
                                                ${isChecked}
                                            />
                                            <span class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                        </label>
                                    </td>
                                </tr>
                                ${index===data.length-1?`</tbody></table>`:''}`;
                            });
                            $list.html(html);
                        }
                    }
                );
            }

            $('#nama_layanan').change(function() {
                loadPersyaratan($(this).val());
            });

            if ($('#nama_layanan').val()) loadPersyaratan($('#nama_layanan').val());

            $(document).on('click', '.btnOpenSyaratModal', function() {
                let jenisSyaratId = $(this).data('syarat-id');
                let formTypeId = jenisSyaratToFormType[jenisSyaratId];
                // if (!formTypeId) return alert('Form belum tersedia');
                //modif by yJs
                if (!formTypeId) {
                    // return alert('Form belum tersedia');
                    formTypeId = jenisSyaratId;
                }

                $('#modalSyarat').removeClass('hidden');
                $('#modalSyaratContent').html('<p class="p-4">Memuat form...</p>');

                $.get(`{{ url('/api/perubahan-layanan/form') }}/` + formTypeId, function(html) {
                    let $form = $('#modalSyaratContent').html(html)
                        .data('formTypeId', formTypeId)
                        .data('jenisSyaratId', jenisSyaratId);

                    // penentuan dokumen_type
                    if (jenisSyaratId == 4) $form.find('#dokumen_type_field').val(
                        'Business Requirements Specification (BRS)');
                    else if (jenisSyaratId == 5) $form.find('#dokumen_type_field').val(
                        'Software Requirement Specification (SRS)');
                    else if (jenisSyaratId == 6) $form.find('#dokumen_type_field').val(
                        'Site Acceptance Test (SAT)');
                    else if (jenisSyaratId == 7) $form.find('#dokumen_type_field').val(
                        'User Acceptance Test (UAT)');
                    else if (jenisSyaratId == 8) $form.find('#dokumen_type_field').val(
                        'Stressing Test');
                    else if (jenisSyaratId == 9) $form.find('#dokumen_type_field').val('Katalog');
                    else if (jenisSyaratId == 11) $form.find('#dokumen_type_field').val(
                        'Penetration Test');

                    let typeText = $form.find('#dokumen_type_field').val();
                    if (typeText) {
                        $form.prepend(`
                            <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded">
                                <strong>Jenis Dokumen:</strong> ${typeText}
                            </div>
                        `);
                    }

                    if (syaratData[jenisSyaratId]) {
                        let data = syaratData[jenisSyaratId];

                        $form.find('input, textarea, select').each(function() {
                            let name = $(this).attr('name') || '';
                            let match = name.match(/^syarat\[\w+\]\[(.+)\]$/);
                            if (!match) return;
                            let key = match[1];

                            if ($(this).attr('type') !== 'file' && data.fields?.[key] !==
                                undefined) {
                                $(this).val(data.fields[key]);
                            }
                        });

                        if (data.files) {
                            let filesText = Object.entries(data.files).map(([key, fileName]) => {
                                let url = data.file_urls?.[key] || '#';
                                let cleanName = fileName.split('/').pop() || url.split('/')
                                    .pop();
                                return `<a href="{{ asset('storage/${url}') }}" target="_blank">${cleanName}</a>`;
                            }).join(', ');
                            $form.find('#fileNameDisplay').html(filesText);
                        } else {
                            $form.find('#fileNameDisplay').text('');
                        }
                    }

                    $form.find('input, textarea, select').each(function() {
                        let $el = $(this);
                        let type = ($el.attr('type') || '').toLowerCase();

                        if (type === 'hidden') return;

                        if ($el.is('select')) {
                            $el.prop('disabled', true);
                        } else if ($el.is('textarea')) {
                            $el.prop('readonly', true);
                        } else if (type === 'file' || type === 'checkbox' || type ===
                            'radio') {
                            $el.prop('disabled', true);
                        } else {
                            $el.prop('readonly', true);
                        }
                    });

                    if (typeof initAutoNumericUang === 'function') {
                        initAutoNumericUang();
                    }
                }).fail(function() {
                    $('#modalSyaratContent').html(
                        '<p class="p-4 text-red-600">Gagal memuat form.</p>');
                });
            });

            $(document).on('click', '.btnCloseSyaratModal', function() {
                $('#modalSyarat').addClass('hidden');
            });

            const UPDATE_STATUS_URL = `{{ url('/api/perubahan-layanan/update-status') }}`;

            async function updateStatus(id, status) {
                const approvals = [];
                document.querySelectorAll('.checkbox-approve').forEach(cb => {
                    approvals.push({
                        jenis_syarat_id: cb.dataset.jenisSyaratId,
                        layanan_syarat_id: cb.dataset.layananSyaratId,
                        is_approve: cb.checked ? 1 : 0,
                    });
                });
                const payload = {
                    status,
                    approvals,
                    keterangan: document.getElementById('keterangan_status').value,
                    no_permohonan: document.getElementById('no_permohonan').value,
                    perubahan_layanan_id: document.getElementById('idPerubahan').value,
                    dampak_perubahan: document.getElementById('dampak_perubahan').value,
                    tingkat_dampak: document.querySelector("input[name='tingkat_dampak']:checked")?.value,
                    kesiapan_personil: document.querySelector("input[name='kesiapan_personil']:checked")
                        ?.value,
                    kesiapan_personil_catatan: document.querySelector(
                        "input[name='kesiapan_personil_catatan']").value,
                    kesiapan_organisasi: document.querySelector("input[name='kesiapan_organisasi']:checked")
                        ?.value,
                    kesiapan_organisasi_catatan: document.querySelector(
                        "input[name='kesiapan_organisasi_catatan']").value,
                    risiko_potensial: document.getElementById('risiko_potensial').value,
                    rencana_mitigasi: document.getElementById('rencana_mitigasi').value,
                };

                try {

                    const res = await fetch(`${UPDATE_STATUS_URL}/${id}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    // === HANDLE VALIDASI SWEETALERT ===
                    if (res.status === 422) {
                        const err = await res.json();

                        let message = Object.values(err.errors)
                            .map(msgArr => `• ${msgArr[0]}`)
                            .join('<br>');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: message,
                            confirmButtonColor: '#3085d6'
                        });

                        return;
                    }

                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal mengubah status.');

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: data.message || 'Status berhasil diubah!',
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('perubahan-layanan.persetujuan') }}";
                    }, 1200);

                } catch (err) {
                    Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.", "error");
                }
            }

            window.updateStatus = updateStatus;

            async function submitDitolak(id) {

                const payload = {
                    status: "Ditolak",
                    perubahan_layanan_id: id,
                    keterangan: document.querySelector('#keteranganDitolak').value
                };

                try {
                    const res = await fetch("{{ url('/api/perubahan-layanan/update-status-ditolak') }}/" +
                        id, {
                            method: "POST", // pastikan route POST
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify(payload)
                        });

                    // Jika response bukan JSON (misal 500), cegah crash
                    let data;
                    try {
                        data = await res.json();
                    } catch (e) {
                        throw new Error("Server error. Tidak bisa memproses permintaan.");
                    }

                    if (!res.ok) {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            html: data.message || JSON.stringify(data.errors)
                        });
                        return;
                    }

                    Swal.fire({
                        icon: "success",
                        title: "Permohonan ditolak",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('perubahan-layanan.persetujuan') }}";
                    }, 1200);

                } catch (err) {
                    Swal.fire("Error!", err.message, "error");
                }
            }

            window.submitDitolak = submitDitolak;

        });
    </script>

</x-app-layout>
