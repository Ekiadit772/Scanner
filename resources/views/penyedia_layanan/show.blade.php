<x-app-layout>
    <x-breadcrumb :items="[
        ['label' => 'Manajemen Data Master & Referensi'],
        ['label' => 'Penyedia Layanan', 'url' => '/penyedia-layanan'],
        ['label' => 'Detail Penyedia Layanan'],
    ]" />

    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">
            <div class="items-center mb-5">
                <h5 class="font-semibold text-lg dark:text-white-light">Detail Penyedia Layanan</h5>
                <hr class="border-t border-primary mt-3" />
            </div>

            {{-- Kode dan Nama Perangkat Daerah --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-3">
                <div class="w-full sm:col-span-8 flex flex-col sm:flex-row sm:items-center">
                    <label class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                        Nama Perangkat Daerah
                    </label>
                    <x-text-input type="text" class="flex-1 w-full" 
                        value="{{ $penyedia->perangkatDaerah->nama ?? '-' }}" disabled />
                </div>

                <div class="w-full sm:col-span-4 flex flex-col sm:flex-row sm:items-center">
                    <label class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                        Kode Perangkat Daerah
                    </label>
                    <x-text-input type="text" class="flex-1 w-full"
                        value="{{ $penyedia->perangkatDaerah->kode ?? '-' }}" disabled />
                </div>
            </div>

            {{-- Nama Bidang --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-3">
                <div class="col-span-12 flex flex-col sm:flex-row sm:items-center">
                    <label class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                        Nama Bidang
                    </label>
                    <x-text-input type="text" class="flex-1 w-full"
                        value="{{ $penyedia->nama_bidang }}" disabled />
                </div>
            </div>

            {{-- Bagian Personil --}}
            <div class="mt-6">
                <h6 class="font-semibold mb-2">Nama & Peran Personil</h6>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 text-sm min-w-[700px]">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2 w-10 text-center">No</th>
                                <th class="border p-2">NIP</th>
                                <th class="border p-2">Nama Personil</th>
                                <th class="border p-2">Jabatan</th>
                                <th class="border p-2">Nama Peran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penyedia->peranPenyedia as $index => $p)
                                <tr>
                                    <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border p-2">{{ $p->nip }}</td>
                                    <td class="border p-2">{{ $p->nama }}</td>
                                    <td class="border p-2">{{ $p->jabatan }}</td>
                                    <td class="border p-2">{{ $p->jenisPeran->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center border p-3 text-gray-500">Tidak ada data personil.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('penyedia-layanan.index') }}" class="btn btn-primary px-4 py-2">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
