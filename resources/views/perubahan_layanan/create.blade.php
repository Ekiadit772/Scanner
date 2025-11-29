<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Manajemen Perubahan'], ['label' => 'Permintaan Perubahan']]" />
    @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">Terjadi kesalahan pada input Anda:</div>
            <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- form controls -->
    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">
            <form class="space-y-5 px-0 sm:px-10" id="formManajemenPerubahan" enctype="multipart/form-data">
                @csrf
                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Informasi Umum</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">

                    <div class="flex flex-col sm:flex-row sm:items-center w-full lg:w-1/4">
                        <label for="no_antrian" class="sm:w-44 text-sm mt-1 font-medium">
                            No Antrian
                        </label>
                        <div class="flex-1">
                            <x-text-input id="no_antrian" class="w-full" type="text" name="no_antrian"
                                :value="old('no_antrian')" readonly />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center w-full lg:w-1/4">
                        <label for="tanggal" class="sm:w-14 text-sm mt-1 font-medium">
                            Tanggal
                        </label>
                        <div class="flex-1">
                            <x-text-input id="tanggal" class="w-full" type="date" name="tanggal"
                                :value="old('tanggal', now()->toDateString())" />
                        </div>
                    </div>
                </div>

                <!-- PENYEDIA & BIDANG -->
                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <!-- Penyedia Layanan -->
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="penyedia_layanan" class="sm:w-44">Penyedia Layanan</label>
                        <div class="flex-1">
                            <select id="penyedia_layanan" name="perangkat_daerah_id"
                                class="select2 border-gray-300 rounded-md text-sm w-full">
                                <option value="">Pilih Perangkat Daerah</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bidang -->
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="bidang" class="sm:w-20">Bidang</label>
                        <div class="flex-1">
                            <select id="bidang" name="penyedia_layanan_id"
                                class="select2 border-gray-300 rounded-md text-sm w-full">
                                <option value="">Pilih Bidang</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- NAMA LAYANAN (tetap full) -->
                <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                    <label for="nama_layanan" class="sm:w-44">Nama Layanan</label>
                    <div class="flex-1">
                        <select id="nama_layanan" name="layanan_id"
                            class="select2 border-gray-300 rounded-md text-sm w-full">
                            <option value="">Pilih Layanan</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                    <label for="persyaratan" class="sm:w-44 mt-2">Persyaratan</label>
                    <div id="persyaratan_list" class="flex-1 space-y-2">
                        <p class="text-gray-400 italic">Pilih layanan untuk melihat persyaratan</p>
                    </div>
                    <x-input-error :messages="$errors->get('persyaratan')" class="mt-2" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center w-full lg:w-1/2">
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

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="perangkat_daerah_pemohon_id" class="sm:w-44">Nama Perangkat Daerah Pengusul</label>
                    <div class="flex-1">
                        <select name="perangkat_daerah_pemohon_id" id="perangkat_daerah_user"></select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="unit_kerja" class="sm:w-44 mt-2">Bidang/ Unit Kerja Pengusul</label>
                        <div class="flex-1">
                            <x-text-input id="unit_kerja" class="w-full" type="text" name="unit_kerja_pemohon"
                                :value="old('unit_kerja')" placeholder="Masukkan Unit Kerja" />
                            <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="jabatan" class="sm:w-20 mt-1 text-sm font-medium">Jabatan</label>
                        <div class="flex-1">
                            <x-text-input id="jabatan" class="w-full" type="text" name="jabatan_pemohon"
                                :value="old('jabatan')" placeholder="Masukan Jabatan" />
                        </div>
                    </div>
                </div>

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Rincian Perubahan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                        <label for="judul_perubahan" class="sm:w-44 mt-2">Judul Perubahan</label>
                        <div class="flex-1">
                            <x-text-input id="judul_perubahan" class="w-full" type="text" name="judul_perubahan"
                                :value="old('judul_perubahan')" placeholder="Masukkan judul perubahan" />
                            <x-input-error :messages="$errors->get('judul_perubahan')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                    <label for="deskripsi" class="sm:w-44 mt-2">Deskripsi Singkat</label>
                    <div class="flex-1">
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                            placeholder="Masukkan deskripsi singkat">{{ old('deskripsi') }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44 mt-2">
                        Perubahan terdapat pada peta rencana SPBE Kota Bandung
                    </label>
                    <div class="flex items-center gap-6 flex-1">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_in_peta_spbe" value="1" class="form-checkbox"
                                {{ old('is_in_peta_spbe') == '1' ? 'checked' : '' }}>
                            Ya
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_in_peta_spbe" value="0" class="form-checkbox"
                                {{ old('is_in_peta_spbe') == '0' ? 'checked' : '' }}>
                            Tidak
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 w-full">
                    <label class="sm:w-44 mt-2">
                        Jenis Perubahan
                    </label>
                    <div class="flex items-center gap-6 flex-1">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="jenis_perubahan" value="Minor" class="form-checkbox"
                                {{ old('jenis_perubahan') == 'Minor' ? 'checked' : '' }}>
                            Minor
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="jenis_perubahan" value="Mayor" class="form-checkbox"
                                {{ old('jenis_perubahan') == 'Mayor' ? 'checked' : '' }}>
                            Mayor
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="jenis_perubahan" value="Darurat" class="form-checkbox"
                                {{ old('jenis_perubahan') == 'Darurat' ? 'checked' : '' }}>
                            Darurat
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                    <label for="latar_belakang" class="sm:w-44 mt-2">Latar Belakang / Alasan Perubahan</label>
                    <div class="flex-1">
                        <textarea id="latar_belakang" name="latar_belakang" rows="3"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                            placeholder="Masukkan latar belakang / alasan perubahan">{{ old('latar_belakang') }}</textarea>
                        <x-input-error :messages="$errors->get('latar_belakang')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                    <label for="tujuan_perubahan" class="sm:w-44 mt-2">Tujuan Perubahan</label>
                    <div class="flex-1">
                        <x-text-input id="tujuan_perubahan" class="w-full" type="text" name="tujuan_perubahan"
                            :value="old('tujuan_perubahan')" placeholder="Masukkan tujuan perubahan" />
                        <x-input-error :messages="$errors->get('tujuan_perubahan')" class="mt-2" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-start mb-3 w-full">
                    <label class="sm:w-44 mt-2">Area Perubahan</label>

                    <div id="area-perubahan-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                        {{-- Checkbox area perubahan akan dimasukkan lewat AJAX --}}
                    </div>
                </div>

                <!-- Jadwal Perubahan -->
                <div class="flex flex-col sm:flex-row sm:items-start mb-3 w-full mt-5">
                    <label class="sm:w-44 mt-2 font-medium">Jadwal Perubahan</label>

                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm mb-1">Mulai :</label>
                            <input type="date" name="jadwal_mulai"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Selesai :</label>
                            <input type="date" name="jadwal_selesai"
                                class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
                        </div>
                    </div>
                </div>

                <!-- Waktu Downtime -->
                <div x-data="{ ada: false, tidakAda: true }" class="flex flex-col sm:flex-row sm:items-start mb-3 w-full">

                    <label class="sm:w-44 mt-2 font-medium">Waktu Downtime (Jika Ada)</label>

                    <div class="flex-1 flex flex-col gap-3">
                        <div class="flex items-center gap-6">

                            <!-- Hidden agar value dikirim ke backend -->
                            <input type="hidden" name="is_downtime" x-model="ada">

                            <!-- Checkbox: Tidak Ada -->
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="form-checkbox" x-model="tidakAda"
                                    @change="if(tidakAda){ ada=false }">
                                Tidak Ada
                            </label>

                            <!-- Checkbox: Ada -->
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="form-checkbox" x-model="ada"
                                    @change="if(ada){ tidakAda=false }">

                                Ada Durasi :

                                <input type="text" name="downtime"
                                    class="border-gray-300 rounded-md shadow-sm w-24 text-center" placeholder="___"
                                    :disabled="!ada">
                            </label>

                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('perubahan-layanan.index') }}"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
        .select2-container {
            width: 100% !important;
            display: block;
        }

        .select2-container .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
        }

        /* Select2 error border */
        .select2-selection.error {
            border-color: #f87171 !important;
            border-width: 1px !important;
        }
    </style>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            // let detailData = [];
            let syaratData = {}; // menyimpan semua data syarat per formTypeId

            // renderTabledetail();

            // ====================== SELECT2 ======================
            $('.select2').select2({
                width: '100%'
            });

            $('#perangkat_daerah_user').select2({
                ajax: {
                    url: `{{ url('/api/perubahan-layanan/get-perangkat-daerah?by_user=true') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => data,
                    cache: true
                },
                placeholder: 'Pilih Perangkat Daerah',
            });

            $('#penyedia_layanan').select2({
                ajax: {
                    url: `{{ url('/api/perubahan-layanan/get-perangkat-daerah') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => data,
                    cache: true
                },
                placeholder: 'Pilih Perangkat Daerah',
            });

            $('#kategori_perubahan_id').select2({
                ajax: {
                    url: `{{ url('/api/perubahan-layanan/get-kategori-perubahan') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => data,
                    cache: true
                },
                placeholder: 'Pilih Kategori Perubahan',
            });

            $('#penyedia_layanan').on('change', function() {
                let perangkatDaerahId = $(this).val();
                $('#bidang, #nama_layanan').val(null).trigger('change');
                $('#persyaratan_list').html(
                    '<p class="text-gray-400 italic">Pilih layanan untuk melihat persyaratan</p>');
                if (!perangkatDaerahId) return;

                $('#bidang').select2({
                    ajax: {
                        url: `{{ url('/api/perubahan-layanan/get-bidang') }}/` +
                            perangkatDaerahId,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: data => data,
                        cache: true
                    },
                    placeholder: 'Pilih Bidang',
                });
            });

            $('#bidang').on('change', function() {
                let penyediaId = $(this).val();
                $('#nama_layanan').val(null).trigger('change');
                $('#persyaratan_list').html(
                    '<p class="text-gray-400 italic">Pilih layanan untuk melihat persyaratan</p>');
                if (!penyediaId) return;

                $('#nama_layanan').select2({
                    ajax: {
                        url: `{{ url('/api/perubahan-layanan/get-layanan') }}/` + penyediaId,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            q: params.term
                        }),
                        processResults: data => data,
                        cache: true
                    },
                    placeholder: 'Pilih Layanan',
                });
                // console.log(penyediaId);

            });

            $('#nama_layanan').on('change', function() {
                const layananId = $(this).val();
                if (!layananId) {
                    $('#no_antrian').val('');
                    return;
                }

                $.ajax({
                    url: `{{ url('/api/perubahan-layanan/generate-no') }}`,
                    type: 'GET',
                    data: {
                        layanan_id: layananId
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#no_antrian').val(res.no_antrian);
                        } else {
                            $('#no_antrian').val('Error generate');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#no_antrian').val('Gagal memuat kode');
                    }
                });
            });

            $('#nama_layanan').on('change', function() {
                let layananId = $(this).val();
                let $list = $('#persyaratan_list');
                $list.html('<p class="text-gray-400 italic">Memuat persyaratan...</p>');
                if (!layananId) return;

                $.getJSON(`{{ url('/api/perubahan-layanan/get-syarat') }}/` + layananId, function(data) {
                    if (!data.length) {
                        $list.html(
                            '<p class="text-gray-400 italic">Tidak ada persyaratan untuk layanan ini</p>'
                        );
                    } else {
                        let html = '';
                        data.forEach((item, index) => {
                            let formTypeId = jenisSyaratToFormType[item.jenis_syarat_id];
                            let isFilled = syaratData[formTypeId] ? true : false;
                            html += `
                ${index === 0 ? `<table class="min-w-full text-sm border-collapse">
                                                                                                                    <thead class="bg-gray-50 border-b">
                                                                                                                        <tr>
                                                                                                                            <th class="px-3 py-2 text-left">No</th>
                                                                                                                            <th class="px-3 py-2 text-left">Nama Persyaratan</th>
                                                                                                                            <th class="px-3 py-2 text-left">Aksi</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>` : '' }
                <tr>
                    <td class="px-3 py-2">${index + 1}</td>
                    <td class="px-3 py-2">${item.nama}</td>
                    <td class="px-3 py-2">
                        <button type="button"
                                class="btnOpenSyaratModal ${isFilled ? 'text-green-600' : 'text-blue-600'}"
                                data-syarat-id="${item.jenis_syarat_id}">
                            ${isFilled ? 'Ubah Syarat' : 'Belum Terisi'}
                        </button>
                    </td>
                </tr>
                ${index === data.length - 1 ? `</tbody></table>` : ''}`;
                        });
                        $list.html(html);
                    }
                });
            });

            // ====================== MODAL SYARAT ======================
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

            $(document).on('click', '.btnOpenSyaratModal', function() {
                let jenisSyaratId = $(this).data('syarat-id');
                let formTypeId = jenisSyaratToFormType[jenisSyaratId];

                console.log('OPEN SYARAT MODAL');
                console.log('jenisSyaratId:', jenisSyaratId);
                console.log('formTypeId:', formTypeId);

                // if (!formTypeId) return alert('Form belum tersedia');
                //modif by yJs
                if (!formTypeId) {
                    // return alert('Form belum tersedia');
                    formTypeId = jenisSyaratId;
                }

                $('#modalSyarat').removeClass('hidden');
                $('#modalSyaratContent')
                    .html('<p class="p-4">Memuat form...</p>')
                    .data('formTypeId', formTypeId)
                    .data('jenisSyaratId', jenisSyaratId);

                $.get(`{{ url('/api/insiden-layanan/form') }}/` + formTypeId, function(html) {
                    let $form = $('#modalSyaratContent').html(html);

                    if (typeof initAutoNumericUang === 'function') {
                        initAutoNumericUang();
                    }

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

                    // tampilkan teks-nya
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
                            let n = $(this).attr('name');
                            let match = n.match(/^syarat\[\w+\]\[(.+)\]$/);
                            if (!match) return;
                            let key = match[1];
                            if ($(this).attr('type') !== 'file' && data[key] !==
                                undefined) {
                                $(this).val(data[key]);
                            }
                        });

                        console.log(data);

                        if (data.files) {
                            let filesText = Object.values(data.files).map(f => f.name).join(', ');
                            $form.find('#fileNameDisplay').text(filesText);
                        } else $form.find('#fileNameDisplay').text('');
                    }
                }).fail(function() {
                    $('#modalSyaratContent').html(
                        '<p class="p-4 text-red-600">Gagal memuat form.</p>'
                    );
                });
            });

            $(document).on('click', '.btnSaveSyarat', function() {
                let $form = $('#modalSyaratContent');
                if (!$form.length) return;

                let formTypeId = $form.data('formTypeId');
                let jenisSyaratId = $form.data('jenisSyaratId');

                console.log('SAVE SYARAT');
                console.log('jenisSyaratId:', jenisSyaratId);
                console.log('formTypeId:', formTypeId);

                if (!formTypeId || !jenisSyaratId) return;

                let formData = {
                    files: {}
                };

                $form.find('input, textarea, select').each(function() {
                    let n = $(this).attr('name');
                    let match = n.match(/^syarat\[\w+\]\[(.+)\]$/);
                    if (!match) return;
                    let key = match[1];

                    if ($(this).attr('type') === 'file') {
                        let file = $(this).prop('files')[0] || null;
                        if (file) formData.files[key] = file;
                    } else {
                        formData[key] = $(this).val() || null;
                    }
                });

                syaratData[jenisSyaratId] = {
                    formTypeId,
                    ...formData
                };

                console.log('Data tersimpan:', syaratData);

                $(`#persyaratan_list button[data-syarat-id="${jenisSyaratId}"]`)
                    .text('Ubah Syarat')
                    .removeClass('text-blue-600')
                    .addClass('text-green-600');

                $('#modalSyarat').addClass('hidden');
            });

            $(document).on('click', '.btnCloseSyaratModal', function() {
                $('#modalSyarat').addClass('hidden');
            });

            fetch(`{{ url('/api/area-perubahan/get-area-perubahan-data') }}`)
                .then(response => response.json())
                .then(res => {
                    let data = res.data;
                    let container = document.getElementById('area-perubahan-container');
                    container.innerHTML = '';

                    data.forEach(item => {
                        let nama = item[0];
                        let isAktif = item[1];
                        let hashedId = item[2];

                        if (isAktif == 1) {
                            container.innerHTML += `
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="area_perubahan_ids[]" value="${hashedId}" class="form-checkbox">
                            ${nama}
                        </label>
                    `;
                        }
                    });
                })
                .catch(err => console.error('Error fetching area perubahan:', err));

            // ====================== FORM MAIN ======================
            $('#formManajemenPerubahan').on('submit', function(e) {
                e.preventDefault();

                // if (!detailData.length) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Belum ada detail layanan',
                //         text: 'Tambahkan minimal 1 detail sebelum submit.'
                //     });
                //     return;
                // }

                let formData = new FormData(this);

                // tambahkan syarat
                Object.keys(syaratData).forEach(jenisSyaratId => {
                    const data = syaratData[jenisSyaratId];
                    const formTypeId = data.formTypeId;

                    // FILES
                    if (data.files) {
                        Object.keys(data.files).forEach(key => {
                            formData.append(
                                `syarat_form[${formTypeId}][items][${jenisSyaratId}][${key}]`,
                                data.files[key]
                            );
                        });
                    }

                    // FIELDS LAIN (nama_aplikasi, keterangan, status, dll)
                    Object.keys(data).forEach(k => {
                        if (k !== "files" && k !== "formTypeId") {
                            formData.append(
                                `syarat_form[${formTypeId}][items][${jenisSyaratId}][${k}]`,
                                data[k] ?? ""
                            );
                        }
                    });

                    // tetap kirim formTypeId
                    formData.append(
                        `syarat_form[${formTypeId}][items][${jenisSyaratId}][formTypeId]`,
                        formTypeId
                    );
                });


                $.ajax({
                    url: `{{ url('/api/perubahan-layanan/store') }}`,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message
                        }).then(() => {
                            window.location.href = `{{ url('/perubahan-layanan') }}`;
                        });
                    },
                    error: function(xhr) {
                        // bersihkan error lama
                        $('p.text-red-500').remove();
                        $('input, select, textarea').removeClass('border-red-500');
                        $('.select2-selection').removeClass('error');

                        let message = 'Terjadi kesalahan pada server.';

                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let syaratMessages = []; // khusus error syarat
                            let otherMessages = []; // error lain yang tidak ketemu input-nya

                            $.each(xhr.responseJSON.errors, function(key, msgs) {
                                // 1. error syarat (dibuat di controller: "syarat.4", "syarat.6", dst)
                                if (key.startsWith('syarat.')) {
                                    // $('#persyaratan_list').prepend(`
                                //         <p class="text-red-500 text-sm mb-1">${msgs.join('<br>')}</p>
                                //     `);

                                    // ambil ID syarat dari "syarat.4"
                                    let parts = key.split('.');
                                    let jenisId = parts[1];

                                    // tandai tombol syarat → merah
                                    let btn = $(
                                        '#persyaratan_list button[data-syarat-id="' +
                                        jenisId + '"]');
                                    btn.removeClass('text-green-600 text-blue-600')
                                        .addClass('text-red-600')
                                        .addClass('flex items-center gap-1')
                                        .html(`
                                                <x-icons.warning class="w-4 h-4 text-red-600" />
                                                <span>Wajib diisi</span>
                                        `);

                                } else {
                                    // 2. coba cari input di form (untuk field biasa)
                                    let input = $('[name="' + key + '"]');

                                    if (input.length) {
                                        showError(input, msgs);
                                    } else {
                                        // 3. error dari service syarat (nama_aplikasi, pemilik_aplikasi, dll)
                                        //    input-nya ada di modal, tapi name-nya "syarat[ID][nama_aplikasi]"
                                        //    jadi kita tampilkan sebagai alert global saja
                                        otherMessages = otherMessages.concat(msgs);
                                    }
                                }
                            });

                            // Tampilkan error syarat di area persyaratan
                            if (syaratMessages.length) {
                                $('#persyaratan_list')
                                    .prepend('<p class="text-red-500 text-sm mb-2">' +
                                        syaratMessages.join('<br>') +
                                        '</p>');
                            }

                            // Kalau ada error syarat dari service (nama_aplikasi, dll) tampilkan di Swal
                            if (otherMessages.length) {
                                message = otherMessages.join('<br>');
                            } else if (syaratMessages.length) {
                                message = syaratMessages.join('<br>');
                            } else {
                                message = 'Validasi gagal. Cek input Anda.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi gagal',
                                html: '<div style="text-align:left;">' + message +
                                    '</div>'
                            });

                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: '<div style="text-align:left;">' + message +
                                    '</div>'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: '<div style="text-align:left;">' + message +
                                    '</div>'
                            });
                        }

                        console.log(xhr.responseText);
                    }
                });

            });

            // ====================== FORM MAIN ======================
            // $('#formManajemenPerubahan').on('submit', function(e) {
            //     e.preventDefault();

            //     let formData = new FormData(this);

            //     $.ajax({
            //         url: `{{ url('/api/perubahan-layanan/store') }}`,
            //         type: "POST",
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(res) {
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Berhasil',
            //                 text: res.message
            //             }).then(() => {
            //                 window.location.href = `{{ url('/perubahan-layanan') }}`;
            //             });
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);

            //             if (xhr.status === 422) {
            //                 let errors = xhr.responseJSON.errors;
            //                 for (let field in errors) {
            //                     let input = $('[name="' + field + '"]');
            //                     showError(input, errors[field]);
            //                 }
            //             }
            //         }
            //     });
            // });

            function showError(input, msgs) {
                input.removeClass('border-red-500');
                input.next('p.text-red-500').remove();
                if (input.hasClass('select2-hidden-accessible')) {
                    input.next('.select2').find('.select2-selection').addClass('error');
                    input.next('.select2').after('<p class="text-red-500 text-sm mt-1">' + msgs[0] + '</p>');
                } else {
                    input.addClass('border-red-500');
                    input.after('<p class="text-red-500 text-sm mt-1">' + msgs[0] + '</p>');
                }
            }

            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('border-red-500');
                $(this).next('p.text-red-500').remove();
                if ($(this).hasClass('select2-hidden-accessible')) $(this).next('.select2').find(
                    '.select2-selection').removeClass('error');
            });

        });
    </script>



</x-app-layout>
