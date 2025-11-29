<x-app-layout>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <x-breadcrumb :items="[['label' => 'Katalog Layanan'], ['label' => 'Daftar Layanan']]" />

    <div class="panel lg:row-span-1 mt-8">
        <div class="mb-5">
            <form id="formService" class="space-y-5 px-0 sm:px-10" enctype="multipart/form-data">
                @csrf

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Pendaftaran Layanan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                {{-- === Input Form === --}}
                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="perangkat-daerah" class="sm:w-44 text-sm font-medium">Nama Perangkat Daerah</label>
                    <div class="flex-1">
                        <select id="perangkat-daerah" name="perangkat_daerah_id"
                            class="select2 block mt-1 border-gray-300 rounded-md shadow-sm text-sm w-full sm:w-1/2">
                            @if (old('perangkat_daerah_id') && session('perangkat_daerah_nama'))
                                <option value="{{ old('perangkat_daerah_id') }}" selected>
                                    {{ session('perangkat_daerah_nama') }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="penyedia-layanan" class="sm:w-44 text-sm font-medium">Nama Bidang (Penyedia
                        Layanan)</label>
                    <div class="flex-1">
                        <select id="penyedia-layanan" name="penyedia_layanan_id"
                            class="block mt-1 border-gray-300 rounded-md shadow-sm text-sm w-full sm:w-1/2">
                            @if (old('penyedia_layanan_id') && session('penyedia_layanan_nama'))
                                <option value="{{ old('penyedia_layanan_id') }}" selected>
                                    {{ session('penyedia_layanan_nama') }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="jenis_layanan_id" class="sm:w-44 mt-1 text-sm font-medium">Jenis Layanan</label>
                        <div class="flex-1">
                            <select id="jenis_layanan_id" name="jenis_layanan_id"
                                class=" block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Pilih Jenis layanan</option>
                                @foreach ($jenisLayanan as $jenis)
                                    <option value="{{ $jenis->id }}"
                                        {{ old('jenis_layanan_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="kelompok_layanan_id" class="sm:w-32 text-sm font-medium">Kelompok Layanan</label>
                        <div class="flex-1">
                            <select id="kelompok_layanan_id" name="kelompok_layanan_id"
                                class=" block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Pilih Kelompok layanan</option>
                                @foreach ($kelLayanans as $kel)
                                    <option value="{{ $kel->id }}"
                                        {{ old('kelompok_layanan_id') == $kel->id ? 'selected' : '' }}>
                                        {{ $kel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-[400px]">
                        <label for="kode" class="sm:w-44 text-sm mt-1 font-medium">Kode Layanan</label>
                        <div class="flex-1">
                            <x-text-input id="kode" type="text" name="kode" placeholder="Masukkan kode"
                                value="{{ old('kode') }}" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/4">
                        <label for="tahun" class="sm:w-32 text-sm font-medium">Tahun Layanan</label>
                        <div class="flex-1">
                            <input id="tahun" type="number" name="tahun" min="2000" max="2100"
                                placeholder="Masukan Tahun"
                                class="form-input w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="nama" class="sm:w-44 text-sm font-medium">Nama Layanan</label>
                    <div class="flex-1">
                        <x-text-input id="nama" type="text" name="nama" placeholder="Masukkan nama"
                            value="{{ old('nama') }}" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="deskripsi" class="sm:w-44 text-sm font-medium">Deskripsi Singkat</label>
                    <textarea id="deskripsi" class="flex-1 form-textarea border rounded p-2" name="deskripsi">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- === SLA & Syarat === --}}
                <div class="mt-8 border-t pt-5">
                    <h5 class="font-semibold text-lg dark:text-white-light mb-4">SLA dan Syarat Layanan</h5>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-5">
                                    <div id="errorSLA"></div>

                                    <button type="button" id="btnAddSLA" class="btn btn-primary btn-sm">+ Tambah
                                        SLA</button>
                                </div>
                                <div class="border rounded-lg shadow-sm">
                                    <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">Daftar SLA</div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm border-collapse">
                                            <thead class="bg-gray-50 border-b">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Nama</th>
                                                    <th class="px-3 py-2 text-left">Deskripsi</th>
                                                    <th class="px-3 py-2 text-left">Nilai</th>
                                                    <th class="px-3 py-2 text-left">Satuan</th>
                                                    <th class="px-3 py-2 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableSLA" class="divide-y"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-5">
                                    <div id="errorSyarat"></div>
                                    <button type="button" id="btnAddSyarat" class="btn btn-secondary btn-sm">+
                                        Tambah
                                        Syarat</button>
                                </div>
                                <div class="border rounded-lg shadow-sm">
                                    <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">Daftar Syarat</div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm border-collapse">
                                            <thead class="bg-gray-50 border-b">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Nama</th>
                                                    <th class="px-3 py-2 text-left">Deskripsi</th>
                                                    <th class="px-3 py-2 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableSyarat" class="divide-y"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="sla" id="slaListInput">
                    <input type="hidden" name="syarat" id="syaratListInput">
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('katalog-layanan.index') }}"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    <button id="btnPreview" class="btn btn-primary">Pratinjau</button>
                </div>
            </form>
        </div>
    </div>

    {{-- === Modal Preview === --}}
    <div id="modalPreview"
        class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Preview Data Pendaftaran Layanan</h2>
            <div id="previewContent" class="space-y-4 text-sm"></div>

            <div class="flex justify-end gap-3 mt-6">
                <button id="cancelPreview" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                <button type="button" id="confirmSave"
                    class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>

            </div>
        </div>
    </div>
    {{-- === Modal SLA === --}}
    <div id="modalSLA" class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Tambah SLA Layanan</h2>
            <form id="formSLA">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-input">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-input"></textarea>
                </div>
                <div class="mb-3">
                    <label>Nilai</label>
                    <input type="number" name="nilai" class="form-input">
                </div>
                <div class="mb-3">
                    <label>Satuan</label>
                    {{-- <input type="text" name="satuan" class="border w-full rounded p-2"> --}}
                    <select id="satuan" class="flex-1 form-select" name="satuan" value="{{ old('satuan') }}">
                        <option value="" selected disabled>Pilih satuan</option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->nama }}">{{ $satuan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded" id="closeSLA">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- === Modal Syarat === --}}
    <div id="modalSyarat"
        class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Tambah Syarat Layanan</h2>

            <form id="formSyarat">
                {{-- Nama (dropdown dari tabel JenisSyarat) --}}
                <div class="mb-3">
                    <label>Nama</label>
                    <select name="jenis_syarat_id" id="namaSelectSyarat" class="form-input select2">
                        <option value="">Pilih Nama Syarat</option>
                        @php
                            $grouped = $jenisSyaratAll->groupBy('kelompok');
                        @endphp
                        @foreach ($grouped as $kelompok => $items)
                            <optgroup label="{{ $kelompok ?: '' }}">
                                @foreach ($items as $js)
                                    <option value="{{ $js->id }}" data-nama="{{ $js->nama }}"
                                        data-kelompok="{{ $js->kelompok }}" data-keterangan="{{ $js->keterangan }}">
                                        {{ $js->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                </div>

                <input type="hidden" name="nama" id="namaSyarat">

                {{-- Jenis (hidden, otomatis dari kelompok) --}}
                <input type="hidden" name="jenis" id="jenisSyarat">

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsiSyarat" class="form-input"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded" id="closeSyarat">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
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

        /* Select2 error border */
        .select2-selection.error {
            border-color: #f87171 !important;
            border-width: 1px !important;
        }
    </style>
    <script>
        const token = document.querySelector('meta[name="api-token"]').content;
        const oldSLA = @json(json_decode(old('sla', '[]')));
        const oldSyarat = @json(json_decode(old('syarat', '[]')));

        $(function() {
            let slaData = [];
            let syaratData = [];

            renderTableSLA();
            renderTableSyarat();

            // === Select2 untuk modal syarat ===
            $('#namaSelectSyarat').select2({
                dropdownParent: $('#modalSyarat'),
                placeholder: 'Pilih Nama Syarat',
                width: '100%'
            });

            // Saat pilihan berubah, isi otomatis field Jenis & Deskripsi
            $('#namaSelectSyarat').on('change', function() {
                const selected = $(this).find('option:selected');
                const nama = selected.data('nama') || '';
                const kelompok = selected.data('kelompok') || '';
                const keterangan = selected.data('keterangan') || '';

                $('#jenisSyarat').val(kelompok);
                $('#deskripsiSyarat').val(keterangan);
                $('#namaSyarat').val(nama);
            });


            $('#slaListInput').val(JSON.stringify(slaData));
            $('#syaratListInput').val(JSON.stringify(syaratData));

            // === Select2 ===
            $('#perangkat-daerah').select2({
                placeholder: 'Pilih Perangkat Daerah',
                ajax: {
                    url: `{{ url('/api/katalog-layanan/perangkat-daerah') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data
                    })
                }
            });

            $('#perangkat-daerah').on('change', function() {
                let opdId = $(this).val();
                $('#penyedia-layanan').val(null).trigger('change').select2({
                    placeholder: 'Pilih Nama Bidang',
                    ajax: {
                        url: `{{ url('/api/katalog-layanan/penyedia-layanan') }}`,
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            perangkat_daerah_id: opdId,
                            search: params.term
                        }),
                        processResults: data => ({
                            results: data
                        })
                    }
                });
            });

            // ***** ======================= *****
            // ***** Fungsi Pelayanan SLA *****
            // ***** ======================= *****

            $('#btnAddSLA').click(() => $('#modalSLA').removeClass('hidden'));
            $('#closeSLA').click(() => $('#modalSLA').addClass('hidden'));

            $('#formSLA').submit(function(e) {
                e.preventDefault();
                const data = Object.fromEntries(new FormData(this).entries());
                const editIndex = $(this).data('edit-index');

                if (editIndex !== undefined) {
                    slaData[editIndex] = data;
                    renderTableSLA();
                    $(this).removeData('edit-index');
                } else {
                    slaData.push(data);
                    renderTableSLA();
                }

                $('#slaListInput').val(JSON.stringify(slaData));
                this.reset();
                $('#modalSLA').addClass('hidden');
            });

            function renderTableSLA() {
                $('#tableSLA').empty();
                slaData.forEach((s, i) => {
                    $('#tableSLA').append(`
                        <tr>
                            <td class="px-3 py-2">${s.nama}</td>
                            <td class="px-3 py-2">${s.nilai}</td>
                            <td class="px-3 py-2">${s.satuan}</td>
                            <td class="px-3 py-2">${s.deskripsi}</td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" class="text-blue-600 btnEditSLA" data-index="${i}">Edit</button> |
                                <button type="button" class="text-red-600 btnDeleteSLA" data-index="${i}">Hapus</button>
                            </td>
                        </tr>
                    `);
                });
            }

            $(document).on('click', '.btnDeleteSLA', function() {
                const index = $(this).data('index');
                slaData.splice(index, 1);
                $(this).closest('tr').remove();
                $('#slaListInput').val(JSON.stringify(slaData));
            });

            // === Edit SLA ===
            $(document).on('click', '.btnEditSLA', function() {
                const index = $(this).data('index');
                const item = slaData[index];

                $('#modalSLA').removeClass('hidden');
                $('#formSLA [name="nama"]').val(item.nama);
                $('#formSLA [name="deskripsi"]').val(item.deskripsi);
                $('#formSLA [name="nilai"]').val(item.nilai);
                $('#formSLA [name="satuan"]').val(item.satuan);

                $('#formSLA').data('edit-index', index);
            });

            // ***** ======================= *****
            // ***** Fungsi Pelayanan Syarat *****
            // ***** ======================= *****

            $('#btnAddSyarat').click(() => $('#modalSyarat').removeClass('hidden'));
            $('#closeSyarat').click(() => $('#modalSyarat').addClass('hidden'));

            $('#formSyarat').submit(function(e) {
                e.preventDefault();
                const data = Object.fromEntries(new FormData(this).entries());
                const editIndex = $(this).data('edit-index');

                if (editIndex !== undefined) {
                    syaratData[editIndex] = data;
                    renderTableSyarat();
                    $(this).removeData('edit-index');
                } else {
                    syaratData.push(data);
                    renderTableSyarat();
                }

                $('#syaratListInput').val(JSON.stringify(syaratData));
                this.reset();
                $('#modalSyarat').addClass('hidden');
            });

            function renderTableSyarat() {
                $('#tableSyarat').empty();
                syaratData.forEach((s, i) => {
                    $('#tableSyarat').append(`
                        <tr>
                            <td class="px-3 py-2">${s.nama}</td>
                            <td class="px-3 py-2">${s.deskripsi || '-'}</td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" class="text-blue-600 btnEditSyarat" data-index="${i}">Edit</button> |
                                <button type="button" class="text-red-600 btnDeleteSyarat" data-index="${i}">Hapus</button>
                            </td>
                        </tr>
                    `);
                });
            }

            // === Edit Syarat ===
            $(document).on('click', '.btnEditSyarat', function() {
                const index = $(this).data('index');
                const item = syaratData[index];

                $('#modalSyarat').removeClass('hidden');
                $('#formSyarat [name="nama"]').val(item.nama);
                $('#formSyarat [name="jenis"]').val(item.jenis);
                $('#formSyarat [name="deskripsi"]').val(item.deskripsi);

                $('#formSyarat').data('edit-index', index);
            });

            $(document).on('click', '.btnDeleteSyarat', function() {
                const index = $(this).data('index');
                syaratData.splice(index, 1);
                $(this).closest('tr').remove();
                $('#syaratListInput').val(JSON.stringify(syaratData));
            });


            // ***** ============== *****
            // ***** Fungsi Preview *****
            // ***** ============== *****
            function showError(input, msgs) {
                input.removeClass('border-red-500');
                input.next('p.text-red-500').remove();
                if (input.hasClass('select2-hidden-accessible')) {
                    input.next('.select2').find('.select2-selection').removeClass('error');
                }

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
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).next('.select2').find('.select2-selection').removeClass('error');
                }
            });

            $('#btnPreview').click(function(e) {
                e.preventDefault();

                $('.text-red-500').remove();
                $('.border-red-500').removeClass('border-red-500');

                let form = $('#formService')[0];
                let formData = new FormData(form);
                formData.set('sla', $('#slaListInput').val());
                formData.set('syarat', $('#syaratListInput').val());

                const formValues = Object.fromEntries(formData.entries());
                const sla = JSON.parse(formValues.sla || '[]');
                const syarat = JSON.parse(formValues.syarat || '[]');

                let missing = [];
                if (!formValues.kode) missing.push('Kode Layanan');
                if (!formValues.nama) missing.push('Nama Layanan');
                if (!formValues.perangkat_daerah_id) missing.push('Perangkat Daerah');
                if (!formValues.penyedia_layanan_id) missing.push('Nama Bidang');

                let warningHtml = missing.length ?
                    `<div class="text-yellow-600 mb-2">
                        <b>Peringatan:</b> Field berikut kosong: ${missing.join(', ')}
                    </div>` :
                    '';

                let previewHtml = `
                    ${warningHtml}
                    <div><b>Perangkat Daerah:</b> ${$('#perangkat-daerah option:selected').text() || '-'}</div>
                    <div><b>Nama Bidang:</b> ${$('#penyedia-layanan option:selected').text() || '-'}</div>
                    <div><b>Kode Layanan:</b> ${formValues.kode || '-'}</div>
                    <div><b>Jenis Layanan:</b> ${$('#jenis_layanan_id option:selected').text() || '-'}</div>
                    <div><b>Kelompok Layanan:</b> ${$('#kelompok_layanan_id option:selected').text() || '-'}</div>
                    <div><b>Nama Layanan:</b> ${formValues.nama || '-'}</div>
                    <div><b>Tahun Layanan:</b> ${formValues.tahun || '-'}</div>
                    <div><b>Deskripsi:</b> ${formValues.deskripsi || '-'}</div>
                    <hr class="my-3">
                    <div><b>Daftar SLA:</b></div>
                    ${
                        sla.length
                            ? `<ul>${sla.map(s => `<li>${s.nama} - ${s.nilai} ${s.satuan}</li>`).join('')}</ul>`
                            : '<p class="text-gray-500 italic">Belum ada SLA.</p>'
                    }
                    <div><b>Daftar Syarat:</b></div>
                    ${
                        syarat.length
                            ? `<ul>${syarat.map(s => `<li>${s.nama} (${s.jenis}) - ${s.deskripsi || '-'}</li>`).join('')}</ul>`
                            : '<p class="text-gray-500 italic">Belum ada syarat.</p>'
                    }
                `;

                $('#previewContent').html(previewHtml);
                $('#modalPreview').removeClass('hidden');
            });

            $('#cancelPreview').click(function() {
                $('#modalPreview').addClass('hidden');
            });

            $('#confirmSave').off('click').on('click', function(e) {
                e.preventDefault();

                $('#slaListInput').val(JSON.stringify(slaData || []));
                $('#syaratListInput').val(JSON.stringify(syaratData || []));

                let formData = new FormData($('#formService')[0]);

                $.ajax({
                    url: "{{ url('/api/katalog-layanan/store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(res) {
                        $('#modalPreview').addClass('hidden');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = `{{ url('/katalog-layanan') }}`;
                        });
                        return;
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('#errorSLA, #errorSyarat').html('');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, msgs) {
                                if (key === 'sla') {
                                    $('#errorSLA').html(
                                        '<p class="text-red-500 text-sm mt-1">' +
                                        msgs[0] + '</p>');
                                } else if (key === 'syarat') {
                                    $('#errorSyarat').html(
                                        '<p class="text-red-500 text-sm mt-1">' +
                                        msgs[0] + '</p>');
                                } else {
                                    let input = $('[name="' + key + '"]');
                                    if (input.length) showError(input, msgs);
                                }
                            });
                        } else {
                            alert('Terjadi kesalahan. Silahkan coba lagi.');
                        }
                        console.log('AJAX gagal', xhr);
                        console.log('AJAX gagal');
                        console.log('Status:', xhr.status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            });


        });
    </script>
</x-app-layout>
