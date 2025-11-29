<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Detail Permintaan Layanan']]" />

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
                    <div class="flex justify-between">
                        <h5 class="font-semibold text-lg dark:text-white-light">Identitas Pemohon</h5>
                        <div class="badge badge-outline-success">{{ $permintaan->no_antrian }}</div>
                    </div>
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
                                placeholder="Masukkan Nama Pemohon" readonly />
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="nip" class="sm:w-20">NIP</label>
                        <div class="flex-1">
                            <x-text-input id="nip" class="w-full" type="text" name="nip"
                                value="{{ old('nip', $permintaan->nip_pemohon) }}" required placeholder="Masukkan NIP"
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
                                :value="old('unit_kerja', $permintaan->unit_kerja_pemohon)" placeholder="Masukkan Unit Kerja" readonly />
                            <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="jabatan" class="sm:w-20">Jabatan</label>
                        <div class="flex-1">
                            <x-text-input id="jabatan" class="w-full" type="text" name="jabatan" :value="$permintaan->jabatan_pemohon"
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
                                :value="old('telepon_pemohon', $permintaan->telepon_pemohon)" placeholder="Masukkan Telepon Pemohon" />
                            <x-input-error :messages="$errors->get('telepon_pemohon')" class="mt-2" readonly />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="email_pemohon" class="sm:w-20 mt-1 text-sm font-medium">Email Pemohon</label>
                        <div class="flex-1">
                            <x-text-input id="email_pemohon" class="w-full" type="email" name="email_pemohon"
                                :value="old('email_pemohon', $permintaan->email_pemohon)" placeholder="Masukan Email Pemohon" readonly />
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
                            <x-timeline-status :logs="$permintaan->riwayatStatus" />
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                                <label for="kode" class="sm:w-44 text-sm mt-1 font-medium">No Permohonan</label>
                                <div class="flex-1">
                                    <x-text-input id="no_permohonan" class="w-full" type="text"
                                        name="no_permohonan" :value="old('no_permohonan', $permintaan->no_permohonan)" placeholder="Isi Nomor Permohonan" />
                                </div>
                            </div>


                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                                <label for="tanggal" class="sm:w-20 mt-1 text-sm font-medium">Tanggal</label>
                                <div class="flex-1">
                                    <x-text-input id="tanggal" class="w-full" type="date" name="tanggal"
                                        :value="old('tanggal', $permintaan->tanggal)" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label for="penyedia_layanan" class="sm:w-44">Penyedia Layanan</label>
                            <div class="flex-1">
                                <select id="penyedia_layanan" name="perangkat_daerah_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full">
                                    <option value="">Pilih Perangkat Daerah</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label for="bidang" class="sm:w-44">Bidang</label>
                            <div class="flex-1">
                                <select id="bidang" name="penyedia_layanan_id"
                                    class="select2 border-gray-300 rounded-md text-sm w-full">
                                    <option value="">Pilih Bidang</option>
                                </select>
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
                                <textarea id="deskripsi_spek" class="w-full form-textarea" type="text" name="deskripsi_spek" readonly>{{ $permintaan->deskripsi_spek }}</textarea>
                                <x-input-error :messages="$errors->get('deskripsi_spek')" class="mt-2 sm:ml-32" />
                            </div>
                        </div>

                        <div class="items-center mb-5">
                            <h5 class="font-semibold text-lg dark:text-white-light">Detail Permintaan Layanan</h5>
                            <hr class="border-t border-primary mt-3" />
                        </div>

                        <div class="space-y-6 mb-3">
                            <div>
                                <div class="border rounded-lg shadow-sm">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm border-collapse">
                                            <thead class="bg-gray-50 border-b">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Nama Item</th>
                                                    <th class="px-3 py-2 text-left">Deskripsi Layanan</th>
                                                    <th class="px-3 py-2 text-left">Banyaknya</th>
                                                    <th class="px-3 py-2 text-left">Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabledetail" class="divide-y">

                                            </tbody>
                                        </table>
                                    </div>
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

                        @if ($currentStatus === 'closing' && $permintaan->layanan_id == config('constants.LAYANAN_REKOMENDASI_ID'))
                            @if ($permintaan->suratRekomendasi())
                                <a href="{{ asset('storage/' . $permintaan->suratRekomendasi()) }}"
                                    class="btn btn-primary" target="_blank">
                                    Download Surat Rekomendasi
                                </a>
                            @endif
                        @endif


                        <div x-data="{ showModal: false, keterangan: '' }" class="flex justify-end gap-2 mt-6">
                            <div x-data="{ showKatalogAplikasiModal: false, keterangan: '' }" class="flex justify-end gap-2 mt-6">
                                @if ($currentStatus === 'verifikasi')
                                    <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'proses')"
                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Verifikasi
                                    </button>
                                    <a href="{{ route('permintaan-layanan.verifikasi') }}"
                                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                                    <button type="button" @click="showModal = true"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Tolak
                                    </button>
                                @elseif ($currentStatus === 'proses')
                                    <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'closing')"
                                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Proses
                                    </button>
                                    <a href="{{ route('permintaan-layanan.proses') }}"
                                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                                    <button type="button" @click="showModal = true"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Tolak
                                    </button>
                                @elseif ($currentStatus === 'closing')
                                    @if ($permintaan->layanan_id == config('constants.LAYANAN_HOSTING_ID'))
                                        <button type="button" @click="showKatalogAplikasiModal = true"
                                            class="bg-secondary text-white px-4 py-2 rounded hover:bg-indigo-700">
                                            Daftarkan ke Katalog Aplikasi
                                        </button>
                                    @endif
                                    <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'selesai')"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        Closing
                                    </button>
                                    <a href="{{ route('permintaan-layanan.closing') }}"
                                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                                    <button type="button" @click="showModal = true"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Tolak
                                    </button>
                                @endif

                                @if ($currentStatus === 'selesai')
                                    <a href="{{ route('permintaan-layanan.closing') }}"
                                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                                @endif

                                <div x-show="showModal" x-cloak
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                        <h2 class="text-lg font-semibold mb-4">Tolak Permintaan</h2>
                                        <textarea x-model="keterangan" placeholder="Masukkan keterangan penolakan" class="w-full border rounded-md p-2 mb-4"></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showModal = false"
                                                class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                                            <button @click="submitDitolak('{{ $hashedAgain }}')"
                                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Kirim</button>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="showKatalogAplikasiModal" x-cloak
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">

                                    <div
                                        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6
                                    max-h-[90vh] overflow-y-auto">
                                        <h2 class="text-lg font-semibold mb-4">Daftarkan Ke Katalog Aplikasi</h2>
                                        <div>
                                            <label for="kode">Kode</label>
                                            <x-text-input id="kode" class="block mt-1 w-full" type="text"
                                                name="kode" :value="old('kode')" autocomplete="kode"
                                                placeholder="Masukkan kode" />
                                            <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                                        </div>
                                        <div class="mt-2">
                                            <label for="nama_aplikasi">Nama Aplikasi</label>
                                            <x-text-input id="nama_aplikasi" class="block mt-1 w-full" type="text"
                                                name="nama_aplikasi" :value="old('nama_aplikasi')" autocomplete="nama aplikasi"
                                                placeholder="Masukkan nama aplikasi" />
                                            <x-input-error :messages="$errors->get('nama_aplikasi')" class="mt-2" />
                                        </div>
                                        <div class="mt-2">
                                            <label for="rekomendasi_id">Nama PPK</label>
                                            <x-text-input id="nama_ppk" class="block mt-1 w-full" type="text"
                                                name="nama_ppk" :value="old('nama_ppk')" autocomplete="nama_ppk"
                                                placeholder="Masukkan nama PPK" />
                                            <x-input-error :messages="$errors->get('nama_ppk')" class="mt-2" />
                                        </div>
                                        <input type="hidden" name="perangkat_daerah_id"
                                            value="{{ $permintaan->perangkat_daerah_pemohon_id }}">

                                        <div class="mt-2">
                                            <label for="rekomendasi_id">Permintaan Layanan Rekomendasi</label>
                                            <select id="rekomendasi_id" class="form-select" name="rekomendasi_id">
                                                <option value="" selected disabled>Pilih
                                                    permintaan layanan rekomendasi
                                                </option>
                                                @foreach ($rekomendasis as $rekomendasi)
                                                    <option value="{{ $rekomendasi->id }}">
                                                        {{ $rekomendasi->no_permohonan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <label for="pelaporan_id">Permintaan Layanan Pelaporan</label>
                                            <select id="pelaporan_id" class="form-select" name="pelaporan_id">
                                                <option value="" selected disabled>Pilih
                                                    permintaan layanan pelaporan
                                                </option>
                                                @foreach ($pelaporans as $pelaporan)
                                                    <option value="{{ $pelaporan->id }}">
                                                        {{ $pelaporan->no_permohonan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <label for="pengujian_id">Permintaan Layanan Pengujian</label>
                                            <select id="pengujian_id" class="form-select" name="pengujian_id">
                                                <option value="" selected disabled>Pilih
                                                    permintaan layanan pengujian
                                                </option>
                                                @foreach ($pengujians as $pengujian)
                                                    <option value="{{ $pengujian->id }}">
                                                        {{ $pengujian->no_permohonan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-4 grid grid-cols-2 gap-4">
                                            <div class="flex items-center">
                                                <input type="checkbox" id="is_pentest" name="is_pentest"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="is_pentest" class="ml-2 text-sm">Pentest</label>
                                            </div>

                                            <div class="flex items-center">
                                                <input type="checkbox" id="is_integrasi" name="is_integrasi"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="is_integrasi" class="ml-2 text-sm">Integrasi</label>
                                            </div>

                                            <div class="flex items-center">
                                                <input type="checkbox" id="is_hosting" name="is_hosting"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="is_hosting" class="ml-2 text-sm">Hosting</label>
                                            </div>

                                            <div class="flex items-center">
                                                <input type="checkbox" id="is_domain" name="is_domain"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                <label for="is_domain" class="ml-2 text-sm">Domain</label>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <label for="keterangan_katalog">Keterangan</label>
                                            <textarea id="keterangan_katalog" name="keterangan_katalog"
                                                class="form-input placeholder:text-white-dark block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                rows="6" placeholder="Masukkan keterangan">{{ old('keterangan_katalog') }}</textarea>
                                            <x-input-error :messages="$errors->get('keterangan_katalog')" class="mt-2" />
                                        </div>

                                        <div class="flex justify-end gap-2 mt-2">
                                            <button type="button" @click="showKatalogAplikasiModal = false"
                                                class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                                            <button type="button" @click="submitKatalog('{{ $hashedAgain }}')"
                                                class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90">Kirim</button>
                                        </div>
                                    </div>
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

        .placeholder-option {
            color: #999 !important;
            /* abu-abu */
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

        $syaratDataArray = $syaratExisting ?? [];
    @endphp

    <script>
        const UPDATE_STATUS_URL = `{{ url('/api/permintaan-layanan/update-status') }}`;

        async function updateStatus(id, status) {

            console.log(status);


            const keteranganInput = document.getElementById('keterangan_status');
            const keterangan = keteranganInput.value.trim();

            if (status === 'ditolak' && keterangan === "") {
                const ok1 = validateRequiredField('keterangan_status');
                if (!ok1) {
                    Swal.fire("Gagal!", "Komentar wajib diisi jika permintaan ditolak!", "warning");
                    return;
                }

            }

            const noPermohonanInput = document.getElementById('no_permohonan');
            const noPermohonan = noPermohonanInput ? noPermohonanInput.value.trim() : '';

            if (status === 'proses' && !noPermohonan) {
                const ok2 = validateRequiredField('no_permohonan');

                if (!ok2) {
                    Swal.fire("Tidak bisa verifikasi!", "No Permohonan wajib diisi.", "warning");
                    return;
                }
            }

            try {
                const approvals = [];
                document.querySelectorAll('.checkbox-approve').forEach(cb => {
                    approvals.push({
                        jenis_syarat_id: cb.dataset.jenisSyaratId,
                        layanan_syarat_id: cb.dataset.layananSyaratId,
                        is_approve: cb.checked ? 1 : 0,
                    });
                });

                const res = await fetch(`${UPDATE_STATUS_URL}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status,
                        approvals,
                        no_permohonan: noPermohonan,
                        keterangan: keterangan
                    })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Gagal mengubah status.');
                console.log(data);

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
                }, 1200);

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

        async function submitKatalog(id) {
            let formData = new FormData();

            formData.append("kode", document.getElementById('kode').value);
            formData.append("nama_aplikasi", document.getElementById('nama_aplikasi').value);
            formData.append("nama_ppk", document.getElementById('nama_ppk').value);
            formData.append("rekomendasi_id", document.getElementById('rekomendasi_id').value);
            formData.append("pelaporan_id", document.getElementById('pelaporan_id').value);
            formData.append("pengujian_id", document.getElementById('pengujian_id').value);
            formData.append("perangkat_daerah_id", "{{ $permintaan->perangkat_daerah_id }}");
            formData.append("keterangan_katalog", document.getElementById('keterangan_katalog').value);

            // checkbox
            if (document.querySelector('#is_pentest').checked) formData.append("is_pentest", 1);
            if (document.querySelector('#is_integrasi').checked) formData.append("is_integrasi", 1);
            if (document.querySelector('#is_hosting').checked) formData.append("is_hosting", 1);
            if (document.querySelector('#is_domain').checked) formData.append("is_domain", 1);

            try {
                const res = await fetch(`{{ url('/api/permintaan-layanan/katalog-aplikasi') }}/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });

                const data = await res.json();

                if (!res.ok) throw new Error(data.message);

                Swal.fire("Berhasil!", data.message, "success");
                // window.location.reload();

            } catch (err) {
                Swal.fire("Gagal!", err.message, "error");
            }
        }



        // ===========================================
        //  INIT JQUERY, DETAIL, PERSYARATAN, MODAL
        // ===========================================
        $(document).ready(function() {
            let syaratData = @json($syaratExisting ?? []);
            let detailList = @json($detailArray);

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

            setSelect2Value('#perangkat_daerah_user', '{{ $permintaan->perangkat_daerah_pemohon_id }}',
                '{{ $permintaan->perangkatPemohon->nama ?? '' }}');

            setSelect2Value('#penyedia_layanan', '{{ $permintaan->perangkat_daerah_id }}',
                '{{ $permintaan->perangkatDaerah->nama ?? '' }}');

            setSelect2Value('#bidang', '{{ $permintaan->penyedia_layanan_id }}',
                '{{ $permintaan->penyediaLayanan->nama_bidang ?? '' }}');

            setSelect2Value('#nama_layanan', '{{ $permintaan->layanan_id }}',
                '{{ $permintaan->layanan->nama ?? '' }}');

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
                    `{{ url('/api/permintaan-layanan/get-syarat') }}/${layananId}?permintaan_id={{ $permintaan->id }}`,
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
                                                                                        <th class="px-3 py-2 text-left w-10">No</th>
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
                                                data-permintaan-id="{{ $permintaan->id }}"
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

            $(document).on('click', '.btnCloseSyaratModal', function() {
                $('#modalSyarat').addClass('hidden');
            });
        });
    </script>


</x-app-layout>
