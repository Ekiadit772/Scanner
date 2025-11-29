<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Edit Permintaan Layanan']]" />

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

    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">
            <form class="space-y-5 px-0 sm:px-10" id="formManajemenPermintaan" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="" id="idManajemen" value="{{ $hashedAgain }}">
                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Identitas Pemohon</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="perangkat_daerah_user" class="sm:w-44">Perangkat Daerah</label>
                    <div class="flex-1">
                        <select name="perangkat_daerah_pemohon_id" id="perangkat_daerah_user"
                            class="select2 border-gray-300 rounded-md text-sm w-full"></select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="nama_pemohon" class="sm:w-44">Nama Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="nama_pemohon" class="w-full" type="text" name="nama_pemohon"
                                value="{{ old('nama_pemohon', $permintaan->nama_pemohon) }}" required
                                placeholder="Masukkan Nama Pemohon" />
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="nip_pemohon" class="sm:w-20">NIP</label>
                        <div class="flex-1">
                            <x-text-input id="nip_pemohon" class="w-full" type="number" name="nip_pemohon"
                                value="{{ old('nip_pemohon', $permintaan->nip_pemohon) }}" required
                                placeholder="Masukkan NIP" />
                            <x-input-error :messages="$errors->get('nip_pemohon')" class="mt-2 sm:ml-32" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="unit_kerja_pemohon" class="sm:w-44 mt-2">Bidang/ Unit Kerja</label>
                        <div class="flex-1">
                            <x-text-input id="unit_kerja_pemohon" class="w-full" type="text"
                                name="unit_kerja_pemohon" :value="old('unit_kerja_pemohon', $permintaan->unit_kerja_pemohon)" placeholder="Masukkan Unit Kerja" />
                            <x-input-error :messages="$errors->get('unit_kerja_pemohon')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="jabatan_pemohon" class="sm:w-20">Jabatan</label>
                        <div class="flex-1">
                            <x-text-input id="jabatan_pemohon" class="w-full" type="text" name="jabatan_pemohon"
                                :value="$permintaan->jabatan_pemohon" required placeholder="Masukkan Nama Instansi" />
                            <x-input-error :messages="$errors->get('jabatan_pemohon')" class="mt-2 sm:ml-32" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="telepon_pemohon" class="sm:w-44 mt-2">Telepon Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="telepon_pemohon" class="w-full" type="number" name="telepon_pemohon"
                                :value="old('telepon_pemohon', $permintaan->telepon_pemohon)" placeholder="Masukkan Telepon Pemohon" />
                            <x-input-error :messages="$errors->get('telepon_pemohon')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="email_pemohon" class="sm:w-20 mt-1 text-sm font-medium">Email Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="email_pemohon" class="w-full" type="email" name="email_pemohon"
                                :value="old('email_pemohon', $permintaan->email_pemohon)" placeholder="Masukan Email Pemohon" />
                        </div>
                    </div>
                </div>

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Spesifikasi Layanan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/3">
                        <label for="kode" class="sm:w-44 text-sm mt-1 font-medium">No Antrian</label>
                        <div class="flex-1">
                            <x-text-input id="no_antrian" class="w-full" type="text" name="no_antrian"
                                :value="old('no_antrian', $permintaan->no_antrian)" readonly />
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/5">
                        <label for="tanggal" class="sm:w-20 mt-1 text-sm font-medium">Tanggal</label>
                        <div class="flex-1">
                            <x-text-input id="tanggal" class="w-full" type="date" name="tanggal"
                                :value="old('tanggal', $permintaan->tanggal)" />
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

                {{-- Persyaratan --}}
                <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                    <label for="persyaratan" class="sm:w-44 mt-2">Persyaratan</label>
                    <div id="persyaratan_list" class="flex-1 space-y-2" data-loaded="0"></div>
                    <x-input-error :messages="$errors->get('persyaratan')" class="mt-2" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="deskripsi_spek" class="sm:w-44">Deskripsi Spek</label>
                    <div class="flex-1">
                        <textarea id="deskripsi_spek" class="w-full form-textarea" type="text" name="deskripsi_spek">{{ $permintaan->deskripsi_spek }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi_spek')" class="mt-2 sm:ml-32" />
                    </div>
                </div>

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Detail Permintaan Layanan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>


                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-5">
                            <div>
                                @if ($errors->has('detail'))
                                    <p class="text-red-500 text-sm mb-2">{{ $errors->first('detail') }}</p>
                                @endif
                            </div>

                            <button type="button" id="btnAdddetail" class="btn btn-primary btn-sm">+ Tambah
                                detail</button>
                        </div>
                        <div class="border rounded-lg shadow-sm">
                            {{-- <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">Daftar detail</div> --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border-collapse">
                                    <thead class="bg-gray-50 border-b">
                                        <tr>
                                            <th class="px-3 py-2 text-left">Nama Item</th>
                                            <th class="px-3 py-2 text-left">Deskripsi Layanan</th>
                                            <th class="px-3 py-2 text-left">Banyaknya</th>
                                            <th class="px-3 py-2 text-left">Satuan</th>
                                            <th class="px-3 py-2 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabledetail" class="divide-y">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="flex justify-between">
                    <a href="{{ route('permintaan-layanan.index') }}"
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

    <div id="modaldetail"
        class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Tambah Layanan detail</h2>
            <form id="formdetail">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama_item" class="form-input">
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi_layanan" class="form-input"></textarea>
                </div>
                <div class="mb-3">
                    <label>Banyaknya</label>
                    <input type="number" name="banyaknya" class="form-input">
                </div>
                <div class="mb-3">
                    <label>Satuan</label>
                    <select id="satuan" class="form-select" name="satuan" value="{{ old('satuan') }}">
                        <option value="" selected disabled>Pilih satuan</option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->nama }}">{{ $satuan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" id="detailListInput" name="detailList">

                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-300 px-4 py-2 rounded" id="closedetail">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <style>
        .select2-container {
            width: 100% !important;
            display: block;
        }

        .select2-container--default .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection__rendered {
            line-height: 1.25rem !important;
            padding-left: 0.25rem;
        }

        .select2-container--default .select2-selection__arrow {
            height: 2.5rem !important;
            right: 0.5rem;
        }

        .flex-1>.select2-container {
            flex: 1;
            min-width: 0;
        }

        .select2-hidden-accessible {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            border: 0 !important;
        }
    </style>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    @php
        $detailArray = $permintaan->detailPermintaan
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'nama_item' => $d->nama_item,
                    'deskripsi_layanan' => $d->deskripsi_layanan,
                    'banyaknya' => $d->banyaknya,
                    'satuan' => $d->satuan,
                ];
            })
            ->toArray();
    @endphp

    @php
        $syaratDataArray = $syaratExisting ?? [];
    @endphp
    <script>
        $(document).ready(function() {
            let syaratData = @json($syaratExisting ?? []);

            $('.select2').select2({
                width: '100%'
            });

            function setSelect2Value(selector, id, text) {
                if (id && text) {
                    let option = new Option(text, id, true, true);
                    $(selector).append(option).trigger('change');
                }
            }

            $('#perangkat_daerah_user').select2({
                ajax: {
                    url: `{{ url('/api/permintaan-layanan/get-perangkat-daerah?by_user=true') }}`,
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

            setSelect2Value('#perangkat_daerah_user', '{{ $permintaan->perangkat_daerah_pemohon_id }}',
                '{{ $permintaan->perangkatPemohon->nama ?? '' }}');

            $('#penyedia_layanan').select2({
                ajax: {
                    url: `{{ url('/api/permintaan-layanan/get-perangkat-daerah') }}`,
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


            $('#penyedia_layanan').on('change', function() {
                let perangkatDaerahId = $(this).val();
                $('#bidang, #nama_layanan').val(null).trigger('change');
                $('#persyaratan_list').html(
                    '<p class="text-gray-400 italic">Pilih layanan untuk melihat persyaratan</p>');
                if (!perangkatDaerahId) return;

                $('#bidang').select2({
                    ajax: {
                        url: `{{ url('/api/permintaan-layanan/get-bidang') }}/` +
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

            setSelect2Value('#penyedia_layanan', '{{ $permintaan->perangkat_daerah_id }}',
                '{{ $permintaan->perangkatDaerah->nama ?? '' }}');

            $('#bidang').on('change', function() {
                let penyediaId = $(this).val();
                $('#nama_layanan').val(null).trigger('change');
                $('#persyaratan_list').html(
                    '<p class="text-gray-400 italic">Pilih layanan untuk melihat persyaratan</p>');
                if (!penyediaId) return;

                $('#nama_layanan').select2({
                    ajax: {
                        url: `{{ url('/api/permintaan-layanan/get-layanan') }}/` + penyediaId,
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
            });

            setSelect2Value('#bidang', '{{ $permintaan->penyedia_layanan_id }}',
                '{{ $permintaan->penyediaLayanan->nama_bidang ?? '' }}');


            $('#nama_layanan').select2({
                ajax: {
                    url: `{{ url('/api/permintaan-layanan/get-layanan') }}/{{ $permintaan->penyedia_layanan_id }}`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return data;
                    },
                    cache: true
                },
                placeholder: 'Pilih Layanan',
            });

            setSelect2Value('#nama_layanan', '{{ $permintaan->layanan_id }}',
                '{{ $permintaan->layanan->nama ?? '' }}');

            $('#nama_layanan').on('change', function() {
                const layananId = $(this).val();
                if (!layananId) {
                    $('#no_antrian').val('');
                    return;
                }

                $.ajax({
                    url: `{{ url('/api/permintaan-layanan/generate-no') }}`,
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


            let detailList = @json($detailArray);

            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderTableDetail() {
                $('#tabledetail').empty();
                detailList.forEach((d, i) => {
                    const idAttr = d.id ? `data-id="${d.id}"` : '';
                    $('#tabledetail').append(`
                <tr ${idAttr}>
                    <td class="px-3 py-2">${escapeHtml(d.nama_item)}</td>
                    <td class="px-3 py-2">${escapeHtml(d.deskripsi_layanan)}</td>
                    <td class="px-3 py-2">${escapeHtml(d.banyaknya)}</td>
                    <td class="px-3 py-2">${escapeHtml(d.satuan)}</td>
                    <td class="px-3 py-2 text-center">
                        <button type="button" class="text-blue-600 btnEditDetail" data-index="${i}">Edit</button> |
                        <button type="button" class="text-red-600 btnDeleteDetail" data-index="${i}">Hapus</button>
                    </td>
                </tr>
            `);
                });
                $('#detailListInput').val(JSON.stringify(detailList));
            }

            renderTableDetail();

            $('#btnAdddetail').click(function() {
                $('#modaldetail').removeClass('hidden');
                $('#formdetail')[0].reset();
                $('#formdetail').removeData('edit-index').removeData('edit-id');
            });

            $('#closedetail').click(function() {
                $('#modaldetail').addClass('hidden');
            });

            $('#formdetail').submit(function(e) {
                e.preventDefault();
                const form = this;
                const data = Object.fromEntries(new FormData(form).entries());
                data.banyaknya = data.banyaknya || 0;

                if (!data.nama_item || !data.deskripsi_layanan || !data.banyaknya || !data.satuan) {
                    alert('Semua field wajib diisi.');
                    return;
                }

                const editIndex = $(form).data('edit-index');
                const editId = $(form).data('edit-id');

                if (typeof editIndex !== 'undefined') {
                    if (editId) data.id = editId;
                    detailList[editIndex] = data;
                } else {
                    detailList.push(data);
                }

                renderTableDetail();
                $('#modaldetail').addClass('hidden');
                form.reset();
                $(form).removeData('edit-index').removeData('edit-id');
            });

            $(document).on('click', '.btnEditDetail', function() {
                const index = $(this).data('index');
                const item = detailList[index];
                $('#modaldetail').removeClass('hidden');
                $('#formdetail [name="nama_item"]').val(item.nama_item);
                $('#formdetail [name="deskripsi_layanan"]').val(item.deskripsi_layanan);
                $('#formdetail [name="banyaknya"]').val(item.banyaknya);
                $('#formdetail [name="satuan"]').val(item.satuan);
                $('#formdetail').data('edit-index', index);
                if (item.id) $('#formdetail').data('edit-id', item.id);
                else $('#formdetail').removeData('edit-id');
            });

            $(document).on('click', '.btnDeleteDetail', function() {
                const index = $(this).data('index');
                if (!confirm('Hapus detail ini?')) return;
                detailList.splice(index, 1);
                renderTableDetail();
            });

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

                $.getJSON(`{{ url('/api/permintaan-layanan/get-syarat') }}/` + layananId, function(data) {
                    if (!data.length) {
                        $list.html(
                            '<p class="text-gray-400 italic">Tidak ada persyaratan untuk layanan ini</p>'
                        );
                    } else {
                        let html = '';
                        data.forEach((item, index) => {
                            let formTypeId = jenisSyaratToFormType[item.jenis_syarat_id];
                            let isFilled = syaratData[item.jenis_syarat_id] ? true : false;
                            html += `
                        ${index===0?`<table class="min-w-full text-sm border-collapse">
                                            <thead class="bg-gray-50 border-b">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">No</th>
                                                    <th class="px-3 py-2 text-left">Nama Persyaratan</th>
                                                    <th class="px-3 py-2 text-left">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>`:''}
                            <tr>
                            <td class="px-3 py-2">${index+1}</td>
                            <td class="px-3 py-2">${item.nama}</td>
                            <td class="px-3 py-2">
                                <button type="button" class="btnOpenSyaratModal ${isFilled?'text-green-600':'text-blue-600'}"
                                    data-syarat-id="${item.jenis_syarat_id}">
                                    ${isFilled?'Ubah Syarat':'Belum Terisi'}
                                </button>
                            </td>
                        </tr>
                        ${index===data.length-1?`</tbody></table>`:''}`;
                        });
                        $list.html(html);
                    }
                });
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
                // console.log(formTypeId);

                $('#modalSyarat').removeClass('hidden');
                $('#modalSyaratContent').html('<p class="p-4">Memuat form...</p>');

                $.get(`{{ url('/api/permintaan-layanan/form') }}/` + formTypeId, function(html) {
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

                    // === Perhatikan di sini, pakai jenisSyaratId, bukan formTypeId ===
                    if (syaratData[jenisSyaratId]) {
                        let data = syaratData[jenisSyaratId];
                        // console.log(data);


                        $form.find('input, textarea, select').each(function() {
                            let name = $(this).attr('name');
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

                    if (typeof initAutoNumericUang === 'function') {
                        initAutoNumericUang($form[0]); // konteks = isi modal
                    }
                }).fail(function() {
                    $('#modalSyaratContent').html(
                        '<p class="p-4 text-red-600">Gagal memuat form.</p>');
                });
            });

            $(document).on('click', '.btnSaveSyarat', function() {
                let $form = $('#modalSyaratContent');
                if (!$form.length) return;

                let formTypeId = $form.data('formTypeId');
                let jenisSyaratId = $form.data('jenisSyaratId');
                if (!formTypeId || !jenisSyaratId) return;

                if (!syaratData[jenisSyaratId]) syaratData[jenisSyaratId] = {
                    fields: {},
                    files: {},
                    file_urls: {}
                };

                let oldFiles = syaratData[jenisSyaratId].files || {};
                let oldFileUrls = syaratData[jenisSyaratId].file_urls || {};

                let formData = {
                    fields: {},
                    files: {},
                    file_urls: oldFileUrls
                };

                $form.find('input, textarea, select').each(function() {
                    let match = $(this).attr('name').match(/^syarat\[\w+\]\[(.+)\]$/);
                    if (!match) return;
                    let key = match[1];

                    if ($(this).attr('type') === 'file') {
                        let file = $(this).prop('files')[0] || null;
                        if (file) {
                            formData.files[key] = file;
                        } else if (oldFiles[key]) {
                            formData.files[key] = oldFiles[key];
                        }
                    } else {
                        formData.fields[key] = $(this).val() || null;
                    }
                });

                syaratData[jenisSyaratId].fields = formData.fields;
                syaratData[jenisSyaratId].files = formData.files;

                console.log(syaratData);


                // update status tombol
                $(`#persyaratan_list button[data-syarat-id="${jenisSyaratId}"]`)
                    .text('Ubah Syarat')
                    .removeClass('text-blue-600')
                    .addClass('text-green-600');

                $('#modalSyarat').addClass('hidden');
            });


            $(document).on('click', '.btnCloseSyaratModal', function() {
                $('#modalSyarat').addClass('hidden');
            });

            $('#formManajemenPermintaan').submit(function(e) {
                e.preventDefault();

                // if (!detailList.length) {
                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Belum ada detail layanan',
                //         text: 'Tambahkan minimal 1 detail sebelum submit.'
                //     });
                //     return;
                // }

                let idManajemen = $('#idManajemen').val();
                let form = this;

                let formData = new FormData(form);
                formData.append('detailList', JSON.stringify(detailList));

                Object.keys(syaratData).forEach(jenisSyaratId => {

                    const data = syaratData[jenisSyaratId];
                    const formTypeId = jenisSyaratToFormType[jenisSyaratId] ?? jenisSyaratId;

                    // console.log(data);
                    // console.log(jenisSyaratId);


                    if (!formTypeId) return;

                    // --- FILES ---
                    if (data.files) {
                        Object.keys(data.files).forEach(key => {
                            const fileVal = data.files[key];

                            // jika file adalah File object → kirim file baru
                            if (fileVal instanceof File) {
                                formData.append(
                                    `syarat_form[${formTypeId}][items][${jenisSyaratId}][${key}]`,
                                    fileVal
                                );
                            }

                            // jika BUKAN File → berarti file lama → tetap kirim nama sebagai string
                            else {
                                formData.append(
                                    `syarat_form[${formTypeId}][items][${jenisSyaratId}][${key}]`,
                                    fileVal
                                );
                            }
                        });
                    }

                    // --- FIELDS LAIN (nama_aplikasi, keterangan, status, dll) ---
                    if (data.fields) {
                        Object.keys(data.fields).forEach(key => {
                            formData.append(
                                `syarat_form[${formTypeId}][items][${jenisSyaratId}][${key}]`,
                                data.fields[key] ?? ""
                            );
                        });
                    }

                    // --- kirim formTypeId juga ---
                    formData.append(
                        `syarat_form[${formTypeId}][items][${jenisSyaratId}][formTypeId]`,
                        formTypeId
                    );
                });

                formData.append('_method', 'PUT');

                $.ajax({
                    url: `{{ url('/api/permintaan-layanan/update') }}/${idManajemen}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: res.message,
                                    confirmButtonText: 'OK'
                                })
                                .then(() => window.location.href =
                                    `{{ url('/permintaan-layanan') }}`);
                        } else {
                            alert('Terjadi kesalahan saat update.');
                        }
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
        });
    </script>

</x-app-layout>
