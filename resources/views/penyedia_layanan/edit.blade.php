<x-app-layout>
    <x-breadcrumb :items="[
        ['label' => 'Manajemen Data Master & Referensi'],
        ['label' => 'Penyedia Layanan', 'url' => url('penyedia-layanan')],
        ['label' => 'Edit Penyedia Layanan'],
    ]" />

    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">
            <form id="penyediaForm" method="POST" data-id="{{ Hashids::encode($penyedia->id) }}">
                @csrf
                @method('PUT')

                {{-- Bagian Identitas --}}
                <div class="items-center mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Edit Penyedia Layanan</h5>
                    <hr class="border-t border-primary mt-3" />
                </div>

                {{-- Kode dan Nama Perangkat Daerah --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-3">
                    <div class="w-full sm:col-span-8 flex flex-col sm:flex-row sm:items-center">
                        <label for="perangkat_daerah_id" class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                            Nama Perangkat Daerah
                        </label>
                        <div class="flex-1">

                            <select id="perangkat_daerah_id" name="perangkat_daerah_id"
                                class=" w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="" disabled>Pilih Perangkat Daerah</option>
                                @foreach ($perangkat_daerah_all as $perda)
                                    <option value="{{ $perda->id }}" data-kode="{{ $perda->kode }}"
                                        {{ $penyedia->perangkat_daerah_id == $perda->id ? 'selected' : '' }}>
                                        {{ $perda->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="w-full sm:col-span-4 flex flex-col sm:flex-row sm:items-center">
                        <label for="kode_perangkat_daerah"
                            class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                            Kode Perangkat Daerah
                        </label>
                        <x-text-input id="kode_perangkat_daerah" type="text" name="kode_perangkat_daerah"
                            class="flex-1 w-full" value="{{ $penyedia->perangkatDaerah->kode ?? '' }}" disabled />
                    </div>
                </div>

                {{-- Nama Bidang --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-3">
                    <div class="col-span-12 flex flex-col sm:flex-row sm:items-center">
                        <label for="nama_bidang" class="sm:w-40 text-sm whitespace-nowrap mb-1 sm:mb-0 sm:mr-2">
                            Nama Bidang
                        </label>
                        <x-text-input id="nama_bidang" type="text" name="nama_bidang" class="flex-1 w-full"
                            value="{{ old('nama_bidang', $penyedia->nama_bidang) }}" required />
                    </div>
                </div>

                {{-- Bagian Personil --}}
                <div class="mt-6">
                    <h6 class="font-semibold mb-2">Nama & Peran Personil</h6>
                    <div class="flex justify-end mb-2">
                        <button type="button" id="add-row"
                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            + Tambah Personil
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 text-sm min-w-[700px]" id="table-personil">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-2 w-10 text-center">No</th>
                                    <th class="border p-2">NIP</th>
                                    <th class="border p-2">Nama Personil</th>
                                    <th class="border p-2">Jabatan</th>
                                    <th class="border p-2">Nama Peran</th>
                                    <th class="border p-2 w-12 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penyedia->peranPenyedia as $index => $p)
                                    <tr>
                                        <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                        <td class="border p-2">
                                            <input type="text" name="personil[{{ $index }}][nip]"
                                                class="w-full border-gray-300 rounded-md" value="{{ $p->nip }}" />
                                        </td>
                                        <td class="border p-2">
                                            <input type="text" name="personil[{{ $index }}][nama]"
                                                class="w-full border-gray-300 rounded-md"
                                                value="{{ $p->nama }}" />
                                        </td>
                                        <td class="border p-2">
                                            <input type="text" name="personil[{{ $index }}][jabatan]"
                                                class="w-full border-gray-300 rounded-md"
                                                value="{{ $p->jabatan }}" />
                                        </td>
                                        <td class="border p-2">
                                            <select name="personil[{{ $index }}][id_peran]"
                                                class="w-full id-peran border-gray-300 rounded-md">
                                                <option value="" disabled>Pilih</option>
                                                @foreach ($jenis_peran_all as $peran)
                                                    <option value="{{ $peran->id }}"
                                                        {{ $p->jenis_peran_id == $peran->id ? 'selected' : '' }}>
                                                        {{ $peran->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border p-2 text-center">
                                            <button type="button" class="text-red-500 remove-row">✕</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('penyedia-layanan.index') }}"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 4px 8px;
        }

        .select2-selection__rendered {
            color: #111827 !important;
            line-height: 28px !important;
        }

        .select2-selection__arrow {
            height: 36px !important;
        }
    </style>

    {{-- JS dinamis --}}
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const $perangkatSelect = $('#perangkat_daerah_id');
            const $kodeInput = $('#kode_perangkat_daerah');
            const $addRowBtn = $('#add-row');
            const $tbody = $('#table-personil tbody');

            console.log('jQuery version:', $.fn.jquery);
            console.log('Select2 available?', typeof $.fn.select2 !== 'undefined');

            // 🔹 Inisialisasi Select2
            $perangkatSelect.select2({
                placeholder: 'Pilih Perangkat Daerah',
                allowClear: true,
                width: '100%',
                theme: 'default'
            });

            // Set kode otomatis ketika pilihan berubah
            $perangkatSelect.on('change', function() {
                const kode = $(this).find(':selected').data('kode');
                $kodeInput.val(kode || '');
            });

            // Tombol tambah baris personil
            $addRowBtn.on('click', function() {
                const rowCount = $tbody.find('tr').length;
                const $newRow = $tbody.find('tr:first').clone();

                $newRow.find('input, select').each(function() {
                    $(this).val('');
                    const name = $(this).attr('name');
                    if (name) $(this).attr('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                });

                $newRow.find('td:first').text(rowCount + 1);
                $tbody.append($newRow);
            });

            // Tombol hapus baris
            $tbody.on('click', '.remove-row', function() {
                if ($tbody.find('tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>

    <script>
        document.getElementById('penyediaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const penyediaId = this.dataset.id;
                const res = await fetch(`{{ url('api/master-penyedia-layanan/update') }}/${penyediaId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    throw data;
                }

                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    padding: '1.5em',
                });
                toast.fire({
                    icon: 'success',
                    title: data.message || 'Penyedia Layanan berhasil diupdate!',
                });

                this.reset();

                setTimeout(() => window.location.href = "{{ url('penyedia-layanan') }}", 100);

            } catch (err) {
                console.error(err); // supaya bisa dilihat di console

                let errorHtml = '';

                if (err.errors) {
                    errorHtml = '<ul style="text-align:center; margin-top:2px;">';
                    Object.entries(err.errors).forEach(([field, messages]) => {
                        // Cek apakah field punya index, contoh: "personil.0.nip"
                        const match = field.match(/^personil\.(\d+)\./);
                        if (match) {
                            const rowNumber = parseInt(match[1]) + 1; // tambah 1 biar mulai dari 1
                            errorHtml += `<li><strong>Baris ${rowNumber}:</strong> ${messages[0]}</li>`;
                        } else {
                            // Error umum (bukan dari array personil)
                            errorHtml += `<li>${messages[0]}</li>`;
                        }
                    });
                    errorHtml += '</ul>';
                }

                Swal.fire({
                    icon: 'error',
                    title: err.message || 'Validasi Gagal!',
                    html: errorHtml || 'Terjadi kesalahan, silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    @if (isset($errors) && $errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: `
                    <ul style="text-align:center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

</x-app-layout>
