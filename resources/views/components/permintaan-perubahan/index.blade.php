@props(['perubahan', 'currentStatus', 'syaratExisting'])

<div class="items-center mb-5">
    <h5 class="font-semibold text-lg dark:text-white-light">Informasi Umum</h5>
    <hr class="border-t border-primary mt-3" />
</div>

<div class="flex flex-col lg:flex-row lg:space-x-6 mb-3">
    <div class="flex flex-col sm:flex-row sm:items-center w-full">
        <label class="sm:w-44 text-sm font-medium">No Antrian</label>
        <div class="flex-1">
            <x-text-input id="no_antrian" class="w-full" type="text" name="no_antrian" :value="old('no_antrian', $perubahan->no_antrian)" readonly />
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center w-full mt-3 lg:mt-0">
        <label class="sm:w-20 text-sm font-medium">Tanggal</label>
        <div class="flex-1">
            <x-text-input id="tanggal" class="w-full" type="date" name="tanggal" :value="old('tanggal', $perubahan->tanggal)" readonly />
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
        <select id="bidang" name="penyedia_layanan_id" class="select2 border-gray-300 rounded-md text-sm w-full">
            <option value="">Pilih Bidang</option>
        </select>
    </div>
</div>

<div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
    <label class="sm:w-44">Nama Layanan</label>
    <div class="flex-1">
        <select id="nama_layanan" name="layanan_id" class="select2 border-gray-300 rounded-md text-sm w-full">
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
            <input type="radio" disabled class="form-checkbox" {{ $perubahan->is_in_peta_spbe ? 'checked' : '' }}> Ya
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" disabled class="form-checkbox" {{ !$perubahan->is_in_peta_spbe ? 'checked' : '' }}>
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

                <input type="text" class="border-gray-300 rounded-md text-sm" value="{{ $perubahan->downtime }}"
                    disabled readonly>
            </label>

        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalSyarat" class="hidden fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
        <div id="modalSyaratContent"></div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary btnCloseSyaratModal">Batal</button>
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
                                                disabled
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
    });
</script>
