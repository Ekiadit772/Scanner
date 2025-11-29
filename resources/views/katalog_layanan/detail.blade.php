<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Detail Daftar Layanan']]" />

    <div class="panel lg:row-span-1 mt-8">
        <div class="mb-5">
            <form id="formService" class="space-y-5 px-0 sm:px-10" enctype="multipart/form-data">
                @csrf

                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Detail Pendaftaran Layanan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                <input type="hidden" value="{{ $layanan->id }}" id="idLayanan">

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="perangkat-daerah" class="sm:w-44 text-sm font-medium">Nama Perangkat Daerah</label>
                    <div class="flex-1">
                        <select id="perangkat-daerah" name="perangkat_daerah_id"
                            class="block mt-1 border-gray-300 rounded-md shadow-sm text-sm w-1/2" disabled>
                            @if ($layanan->perangkat_daerah_id)
                                <option value="{{ $layanan->perangkat_daerah_id }}" selected>
                                    {{ $layanan->perangkatDaerah->nama ?? 'Terpilih' }}
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
                            class="block mt-1 border-gray-300 rounded-md shadow-sm text-sm w-1/2" disabled>
                            @if ($layanan->penyedia_layanan_id)
                                <option value="{{ $layanan->penyedia_layanan_id }}" selected>
                                    {{ $layanan->penyediaLayanan->nama_bidang }}
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
                                class=" block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" disabled>
                                @foreach ($jenisLayanan as $jenis)
                                    <option value="{{ $jenis->id }}"
                                        {{ $layanan->jenis_layanan_id == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
                        <label for="kelompok_layanan" class="sm:w-32 mt-1 text-sm font-medium">Kelompok Layanan</label>
                        <div class="flex-1">
                            <select id="kelompok_layanan_id" name="kelompok_layanan_id"
                                class=" block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm" disabled>
                                @foreach ($kelLayanans as $kelLayanan)
                                    <option value="{{ $kelLayanan->id }}"
                                        {{ $layanan->kelompok_layanan_id == $kelLayanan->id ? 'selected' : '' }}>
                                        {{ $kelLayanan->nama }}
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
                            <x-text-input id="kode" type="text" name="kode" value="{{ $layanan->kode }}"
                                readonly />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/4">
                        <label for="tahun" class="sm:w-32 text-sm font-medium">Tahun Layanan</label>
                        <div class="flex-1">
                            <input id="tahun" type="number" name="tahun" min="2000" max="2100"
                                placeholder="Masukan Tahun" class="form-input w-full border-gray-300 rounded-md text-sm"
                                value="{{ $layanan->tahun }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="nama" class="sm:w-44 text-sm font-medium">Nama Layanan</label>
                    <div class="flex-1">
                        <x-text-input id="nama" type="text" name="nama" value="{{ $layanan->nama }}"
                            readonly />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                    <label for="deskripsi" class="sm:w-44 text-sm font-medium">Deskripsi Singkat</label>
                    <div class="flex-1">
                        <textarea id="deskripsi" class="form-textarea border rounded p-2" name="deskripsi" readonly>{{ $layanan->deskripsi }}</textarea>
                    </div>
                </div>

                {{-- === SLA & Syarat === --}}
                <div class="mt-8 border-t pt-5">
                    <h5 class="font-semibold text-lg dark:text-white-light mb-4">SLA dan Syarat Layanan</h5>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- SLA --}}
                        <div>
                            <div class="border rounded-lg shadow-sm">
                                <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">Daftar SLA</div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm border-collapse">
                                        <thead class="bg-gray-50 border-b">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Nama</th>
                                                <th class="px-3 py-2 text-left">Nilai</th>
                                                <th class="px-3 py-2 text-left">Satuan</th>
                                                <th class="px-3 py-2 text-left">Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableSLA" class="divide-y">
                                            @foreach ($layanan->sla as $sla)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $sla->nama }}</td>
                                                    <td class="px-3 py-2">{{ $sla->nilai }}</td>
                                                    <td class="px-3 py-2">{{ $sla->satuan }}</td>
                                                    <td class="px-3 py-2">{{ $sla->deskripsi }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Syarat --}}
                        <div>
                            <div class="border rounded-lg shadow-sm">
                                <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">Daftar Syarat</div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm border-collapse">
                                        <thead class="bg-gray-50 border-b">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Nama</th>
                                                <th class="px-3 py-2 text-left">Jenis</th>
                                                <th class="px-3 py-2 text-left">Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableSyarat" class="divide-y">
                                            @foreach ($layanan->syarat as $syarat)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $syarat->jenisSyarat->nama }}</td>
                                                    <td class="px-3 py-2">{{ $syarat->jenisSyarat->kelompok }}</td>
                                                    <td class="px-3 py-2">{{ $syarat->deskripsi }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Kembali --}}
                <div class="flex justify-end mt-6">
                    <a href="{{ route('katalog-layanan.index') }}"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Preview (readonly mode, tanpa tombol simpan) --}}
    <div id="modalPreview"
        class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-60 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Preview Data Service Catalog</h2>
            <div id="previewContent" class="space-y-4 text-sm"></div>
            <div class="flex justify-end mt-6">
                <button id="cancelPreview" class="bg-gray-300 px-4 py-2 rounded">Tutup</button>
            </div>
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

        .select2-selection.error {
            border-color: #f87171 !important;
            border-width: 1px !important;
        }

        /* Nonaktifkan pointer events agar tampak readonly */
    </style>
</x-app-layout>
