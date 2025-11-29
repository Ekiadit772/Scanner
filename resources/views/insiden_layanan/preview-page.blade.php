<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Detail insiden Layanan']]" />

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
            <form class="space-y-5 px-0 sm:px-10" id="formManajemeninsiden" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="" id="idManajemen" value="{{ $hashedAgain }}">
                <div class="items-center mb-5">
                    <div class="flex justify-between">
                        <h5 class="font-semibold text-lg dark:text-white-light">Identitas Pemohon</h5>
                        <div class="badge badge-outline-success">{{ $insiden->no_antrian }}</div>
                    </div>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="perangkat_daerah_user" class="sm:w-44">Perangkat Daerah</label>
                    <div class="flex-1">
                        <select name="perangkat_daerah_pemohon_id" id="perangkat_daerah_user"
                            class="select2 border-gray-300 rounded-md text-sm w-full" disabled></select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="nama_pemohon" class="sm:w-44">Nama Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="nama_pemohon" class="w-full" type="text" name="nama_pemohon"
                                value="{{ old('nama_pemohon', $insiden->nama_pemohon) }}" required
                                placeholder="Masukkan Nama Pemohon" readonly />
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="nip" class="sm:w-20">NIP</label>
                        <div class="flex-1">
                            <x-text-input id="nip" class="w-full" type="text" name="nip"
                                value="{{ old('nip', $insiden->nip_pemohon) }}" required placeholder="Masukkan NIP"
                                readonly />
                            <x-input-error :messages="$errors->get('nip')" class="mt-2 sm:ml-32" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="unit_kerja" class="sm:w-44 mt-2">Bidang/ Unit Kerja</label>
                        <div class="flex-1">
                            <x-text-input id="unit_kerja" class="w-full" type="text" name="unit_kerja"
                                :value="old('unit_kerja', $insiden->unit_kerja_pemohon)" placeholder="Masukkan Unit Kerja" readonly />
                            <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="jabatan" class="sm:w-20">Jabatan</label>
                        <div class="flex-1">
                            <x-text-input id="jabatan" class="w-full" type="text" name="jabatan" :value="$insiden->jabatan_pemohon"
                                required placeholder="Masukkan Nama Instansi" readonly />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2 sm:ml-32" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="telepon_pemohon" class="sm:w-44 mt-2">Telepon Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="telepon_pemohon" class="w-full" type="number" name="telepon_pemohon"
                                :value="old('telepon_pemohon', $insiden->telepon_pemohon)" placeholder="Masukkan Telepon Pemohon" />
                            <x-input-error :messages="$errors->get('telepon_pemohon')" class="mt-2" readonly />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="email_pemohon" class="sm:w-20 mt-1 text-sm font-medium">Email Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="email_pemohon" class="w-full" type="email" name="email_pemohon"
                                :value="old('email_pemohon', $insiden->email_pemohon)" placeholder="Masukan Email Pemohon" readonly />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
                    <div class="lg:col-span-1 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Status Permohonan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>

                    <div class="lg:col-span-2 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Spesifikasi Layanan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="sticky top-36 border rounded-lg p-4 bg-white shadow-sm">
                            <x-timeline-status :logs="$insiden->riwayatStatus" />
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="flex flex-col lg:flex-row lg:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="kode" class="sm:w-44 text-sm mt-1 font-medium">No Permohonan</label>
                                <div class="flex-1">
                                    <x-text-input id="no_permohonan" class="w-full" type="text"
                                        name="no_permohonan" placeholder="Isi No Permohonan" :value="old('no_permohonan', $insiden->no_permohonan)" />
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center w-full mt-3 lg:mt-0">
                                <label for="tanggal" class="sm:w-20 text-sm mt-1 font-medium">
                                    Tanggal
                                </label>
                                <div class="flex-1">
                                    <x-text-input id="tanggal" class="w-full" type="date" name="tanggal"
                                        :value="old('tanggal', now()->toDateString())" />
                                </div>
                            </div>
                        </div>

                        <!-- PENYEDIA & BIDANG -->
                        <!-- Penyedia Layanan -->
                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label for="penyedia_layanan" class="sm:w-44">Penyedia Layanan</label>
                            <div class="flex-1">
                                <select id="penyedia_layanan" name="perangkat_daerah_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full" disabled>
                                    <option value="">Pilih Perangkat Daerah</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bidang -->
                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label for="bidang" class="sm:w-44">Bidang</label>
                            <div class="flex-1">
                                <select id="bidang" name="penyedia_layanan_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full" disabled>
                                    <option value="">Pilih Bidang</option>
                                </select>
                            </div>
                        </div>

                        <!-- NAMA LAYANAN (tetap full) -->
                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label for="nama_layanan" class="sm:w-44">Nama Layanan</label>
                            <div class="flex-1">
                                <select id="nama_layanan" name="layanan_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full" disabled>
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

                        <div class="flex flex-col sm:flex-row sm:items-center w-full mb-3">
                            <label for="jenis_insiden"
                                class="sm:w-44 text-sm mt-1 font-medium truncate overflow-hidden">
                                Jenis insiden
                            </label>

                            <div class="flex-1 min-w-0">
                                <select id="jenis_insiden" name="jenis_insiden_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full" disabled>
                                    <option value="">Pilih Jenis insiden</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                            <label for="keluhan" class="sm:w-44">Keluhan</label>
                            <div class="flex-1">
                                <textarea id="keluhan" class="w-full form-textarea" name="keluhan" readonly>{{ $insiden->keluhan }}</textarea>
                                <x-input-error :messages="$errors->get('keluhan')" class="mt-2 sm:ml-32" />
                            </div>
                        </div>

                        @if (in_array($currentStatus, ['dalam_antrian', 'penugasan', 'penanganan', 'penelusuran', 'selesai', 'ditolak']))
                            <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                                <label for="keterangan_status" class="sm:w-44 mt-2">Keterangan (Opsional)</label>
                                <div class="flex-1">
                                    <textarea id="keterangan_status" name="keterangan_status" class="w-full form-textarea">{{ old('keterangan', optional($insiden->riwayatStatus->last())->keterangan) }}</textarea>
                                </div>
                            </div>
                        @endif

                        {{-- PENANGANAN INSIDEN --}}
                        @if (in_array($currentStatus, ['penugasan', 'penanganan', 'penelusuran', 'selesai']))
                            <div class="items-center mb-5 mt-6">
                                <h5 class="font-semibold text-lg dark:text-white-light">Penanganan Insiden</h5>
                                <hr class="border-t border-primary mt-3" />
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                                <label for="penanganan_insiden" class="sm:w-52 mt-2">Teknik Penanganan Insiden</label>
                                <div class="flex-1">
                                    <textarea id="penanganan_insiden" name="penanganan_insiden" class="w-full form-textarea"
                                        @if ($currentStatus !== 'penugasan') readonly @endif>{{ old('penanganan_insiden', $insiden->penanganan_insiden) }}</textarea>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                                <label for="perangkat_yang_diperlukan" class="sm:w-52 mt-2">Perangkat yang
                                    Diperlukan</label>
                                <div class="flex-1">
                                    <textarea id="perangkat_yang_diperlukan" name="perangkat_yang_diperlukan" class="w-full form-textarea"
                                        @if ($currentStatus !== 'penugasan') readonly @endif>{{ old('perangkat_yang_diperlukan', $insiden->perangkat_yang_diperlukan) }}</textarea>
                                </div>
                            </div>
                        @endif

                        {{-- PENELUSURAN / STATUS PENANGANAN --}}
                        @if (in_array($currentStatus, ['penanganan', 'penelusuran', 'selesai']))
                            <div class="items-center mb-5 mt-6">
                                <h5 class="font-semibold text-lg dark:text-white-light">Status Penanganan Insiden</h5>
                                <hr class="border-t border-primary mt-3" />
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:w-1/2">
                                <label for="status_penanganan_insiden_id" class="sm:w-52">Status Penanganan
                                    Insiden</label>
                                <div class="flex-1">
                                    <select id="status_penanganan_insiden_id" name="status_penanganan_insiden_id"
                                        class="select2 border-gray-300 rounded-md text-sm w-full"
                                        @if ($currentStatus !== 'penanganan') disabled @endif>
                                        <option value="">Pilih Status</option>
                                        @foreach ($statusPenanganan as $sp)
                                            <option value="{{ $sp->id }}" @selected($insiden->status_penanganan_insiden_id == $sp->id)>
                                                {{ $sp->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start mb-3">
                                <label for="keterangan_pelaksanaan" class="sm:w-52 mt-2">Keterangan
                                    Pelaksanaan</label>
                                <div class="flex-1">
                                    <textarea id="keterangan_pelaksanaan" name="keterangan_pelaksanaan"
                                        class="w-full form-textarea"@if ($currentStatus !== 'penanganan') readonly @endif>{{ old('keterangan_pelaksanaan', $insiden->keterangan_pelaksanaan) }}</textarea>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    @if ($currentStatus === 'dalam_antrian')
                        <button type="button"
                            onclick="updateStatus('{{ $hashedAgain }}', 'penugasan', 'keterangan')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan
                        </button>
                        <button type="button" onclick="updateStatus('{{ $hashedAgain }}', 'ditolak')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Tolak
                        </button>
                        <a href="{{ route('insiden-layanan.penugasan') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    @elseif ($currentStatus === 'penugasan')
                        <button type="button"
                            onclick="updateStatus('{{ $hashedAgain }}', 'penanganan', 'keterangan')"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Simpan
                        </button>
                        <button type="button" onclick="updateStatus('{{ $hashedAgain }}', 'ditolak')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Tolak
                        </button>
                        <a href="{{ route('insiden-layanan.penanganan') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    @elseif ($currentStatus === 'penanganan')
                        <button type="button"
                            onclick="updateStatus('{{ $hashedAgain }}', 'penelusuran', 'keterangan')"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Simpan
                        </button>
                        <button type="button" onclick="updateStatus('{{ $hashedAgain }}', 'ditolak')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Tolak
                        </button>
                        <a href="{{ route('insiden-layanan.penelusuran') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    @elseif ($currentStatus === 'penelusuran')
                        <button type="button" onclick="updateStatus('{{ $hashedAgain }}', 'selesai', '')"
                            class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                            Simpan
                        </button>
                        <button type="button" onclick="updateStatus('{{ $hashedAgain }}', 'ditolak')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Tolak
                        </button>
                        <a href="{{ route('insiden-layanan.closing') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    @endif
                    @if ($currentStatus === 'selesai' || $currentStatus === 'ditolak')
                        <a href="{{ route('insiden-layanan.penugasan') }}"
                            class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                    @endif

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
        $detailArray = $insiden->detailInsiden
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

        $syaratDataArray = $syaratExisting ?? [];
    @endphp

    <script>
        const UPDATE_STATUS_URL = `{{ url('/api/insiden-layanan/update-status') }}`;

        async function updateStatus(id, status) {
            try {
                const approvals = [];
                document.querySelectorAll('.checkbox-approve').forEach(cb => {
                    approvals.push({
                        jenis_syarat_id: cb.dataset.jenisSyaratId,
                        layanan_syarat_id: cb.dataset.layananSyaratId,
                        is_approve: cb.checked ? 1 : 0,
                    });
                });

                const keteranganInput = document.getElementById('keterangan_status');
                const keterangan = keteranganInput ? keteranganInput.value.trim() : '';

                if (status === 'ditolak' && keterangan === "") {
                    const ok1 = validateRequiredField('keterangan_status');
                    if (!ok1) {
                        Swal.fire("Gagal!", "Komentar wajib diisi jika permintaan ditolak!", "warning");
                        return;
                    }

                }

                const noPermohonanInput = document.getElementById('no_permohonan');
                const noPermohonan = noPermohonanInput ? noPermohonanInput.value.trim() : '';

                if (status === 'penugasan' && !noPermohonan) {
                    const ok1 = validateRequiredField('no_permohonan');

                    if (!ok1) {
                        Swal.fire(
                            "Tidak bisa verifikasi!",
                            "No Permohonan wajib diisi sebelum melanjutkan.",
                            "warning"
                        );
                        return;
                    }
                }

                if (status === 'penanganan') {
                    const ok2 = validateRequiredField('penanganan_insiden');
                    const ok3 = validateRequiredField('perangkat_yang_diperlukan');

                    if (!ok2 || !ok3) {
                        Swal.fire("Validasi gagal!", "Lengkapi semua field penanganan terlebih dahulu!", "warning");
                        return;
                    }
                }

                if (status === 'penelusuran') {
                    const ok4 = validateRequiredField('status_penanganan_insiden_id');
                    const ok5 = validateRequiredField('keterangan_pelaksanaan');

                    if (!ok4 || !ok5) {
                        Swal.fire("Validasi gagal!", "Status Penanganan Insiden wajib dipilih!", "warning");
                        return;
                    }
                }

                const payload = {
                    status,
                    approvals,
                    no_permohonan: noPermohonan,
                    keterangan: keterangan
                };

                if (status === 'penanganan' || status === 'penelusuran' || status === 'selesai') {
                    const penangananInsidenEl = document.getElementById('penanganan_insiden');
                    const perangkatDiperlukanEl = document.getElementById('perangkat_yang_diperlukan');

                    if (penangananInsidenEl) {
                        payload.penanganan_insiden = penangananInsidenEl.value;
                    }
                    if (perangkatDiperlukanEl) {
                        payload.perangkat_yang_diperlukan = perangkatDiperlukanEl.value;
                    }
                }

                if (status === 'penelusuran' || status === 'selesai') {
                    const statusPenangananEl = document.getElementById('status_penanganan_insiden_id');
                    const ketPelaksanaanEl = document.getElementById('keterangan_pelaksanaan');

                    if (statusPenangananEl) {
                        payload.status_penanganan_insiden_id = statusPenangananEl.value;
                    }
                    if (ketPelaksanaanEl) {
                        payload.keterangan_pelaksanaan = ketPelaksanaanEl.value;
                    }
                }

                payload.keterangan_status = keterangan;

                const res = await fetch(`${UPDATE_STATUS_URL}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (!res.ok) {
                    if (res.status === 422 && data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        const firstMsg = data.errors[firstKey][0];

                        Swal.fire(
                            "Validasi gagal!",
                            firstMsg,
                            "warning"
                        );
                        return;
                    }

                    throw new Error(data.message || 'Gagal mengubah status.');
                }

                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: data.message || 'Status berhasil diubah!',
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } catch (err) {
                Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.", "error");
            }
        }

        function validateRequiredField(id) {
            const el = document.getElementById(id);
            if (!el) return true; // skip kalau elemen tidak ada

            if (el.value.trim() === '') {
                el.classList.add('border', 'border-red-500');
                return false;
            } else {
                el.classList.remove('border', 'border-red-500');
                return true;
            }
        }


        // ===========================================
        //  INIT JQUERY, DETAIL, PERSYARATAN, MODAL
        // ===========================================
        $(document).ready(function() {
            let syaratData = @json($syaratExisting ?? []);
            let detailList = @json($detailArray);

            $('.select2').each(function() {
                $(this).select2({
                    width: '100%',
                    // kalau di HTML ada atribut disabled, baru kita disable
                    disabled: $(this).is('[disabled]')
                });
            });


            function setSelect2Value(selector, id, text) {
                if (id && text) {
                    let option = new Option(text, id, true, true);
                    $(selector).append(option).trigger('change');
                }
            }

            setSelect2Value('#perangkat_daerah_user', '{{ $insiden->perangkat_daerah_pemohon_id }}',
                '{{ $insiden->perangkatPemohon->nama ?? '' }}');

            setSelect2Value('#penyedia_layanan', '{{ $insiden->perangkat_daerah_id }}',
                '{{ $insiden->perangkatDaerah->nama ?? '' }}');

            setSelect2Value('#bidang', '{{ $insiden->penyedia_layanan_id }}',
                '{{ $insiden->penyediaLayanan->nama_bidang ?? '' }}');

            setSelect2Value('#nama_layanan', '{{ $insiden->layanan_id }}',
                '{{ $insiden->layanan->nama ?? '' }}');

            setSelect2Value('#jenis_insiden', '{{ $insiden->layanan_id }}',
                '{{ $insiden->jenisInsiden->nama ?? '' }}');

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

                $.getJSON(
                    `{{ url('/api/insiden-layanan/get-syarat') }}/${layananId}?insiden_id={{ $insiden->id }}`,
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
                                                data-insiden-id="{{ $insiden->id }}"
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


            // ==========================
            //  MODAL SYARAT (VIEW ONLY)
            // ==========================
            $(document).on('click', '.btnOpenSyaratModal', function() {
                let jenisSyaratId = $(this).data('syarat-id');
                let formTypeId = jenisSyaratToFormType[jenisSyaratId];

                if (!formTypeId) {
                    formTypeId = jenisSyaratId;
                }

                $('#modalSyarat').removeClass('hidden');
                $('#modalSyaratContent').html('<p class="p-4">Memuat form...</p>');

                $.get(`{{ url('/api/insiden-layanan/form') }}/` + formTypeId, function(html) {
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


</x-app-layout>
