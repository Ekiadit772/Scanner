<x-app-layout>
    <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Detail Perubahan Layanan']]" />

    <div class="panel lg:row-span-3 mt-8">
        <div class="mb-5">

            <form class="space-y-5 px-0 sm:px-10" id="formManajemenPerubahan" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="" id="idPerubahan" value="{{ $hashedAgain }}">

                <x-permintaan-perubahan.index :perubahan="$perubahan" :currentStatus="$currentStatus" :syaratExisting="$syaratExisting" />

                <x-permintaan-perubahan.verifikasi :perubahan="$perubahan" />

                <x-permintaan-perubahan.closing :perubahan="$perubahan" />

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
                    <div class="lg:col-span-1 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Status Permohonan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>

                    <div class="lg:col-span-2 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Penutupan Permintaan Perubahan</h5>
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

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Tanggal Penyelesaian Perubahan
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <input type="date" name="tanggal_penyelesaian" class="form-input"
                                    {{ old('tanggal_penyelesaian') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Kesesuaian Hasil Evaluasi
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesesuaian_hasil" value="Sesuai" class="form-checkbox"
                                        {{ old('kesesuaian_hasil') == 'Sesuai' ? 'checked' : '' }}>
                                    Sesuai
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="kesesuaian_hasil" value="Tidak Sesuai"
                                        class="form-checkbox"
                                        {{ old('kesesuaian_hasil') == 'Tidak Sesuai' ? 'checked' : '' }}>
                                    Tidak Sesuai
                                </label>

                                <label class="flex items-center gap-2">
                                    Penjelasan
                                </label>
                                <input type="text" name="kesesuaian_penjelasan" class="form-input"
                                    {{ old('kesesuaian_penjelasan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Dampak Terhadap SPBE Kota Bandung
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="dampak_spbe" value="Positif" class="form-checkbox"
                                        {{ old('dampak_spbe') == 'Positif' ? 'checked' : '' }}>
                                    Positif
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="dampak_spbe" value="Netral" class="form-checkbox"
                                        {{ old('dampak_spbe') == 'Netral' ? 'checked' : '' }}>
                                    Netral
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="dampak_spbe" value="Negatif" class="form-checkbox"
                                        {{ old('dampak_spbe') == 'Negatif' ? 'checked' : '' }}>
                                    Negatif
                                </label>

                                <label class="flex items-center gap-2">
                                    Penjelasan
                                </label>
                                <input type="text" name="dampak_spbe_penjelasan" class="form-input"
                                    {{ old('dampak_spbe_penjelasan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Persetujuan Penyelesaian
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="persetujuan_penyelesaian" value="Telah Selesai"
                                        class="form-checkbox"
                                        {{ old('persetujuan_penyelesaian') == 'Telah Selesai' ? 'checked' : '' }}>
                                    Telah Selesai
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="persetujuan_penyelesaian" value="Selesai Sebagian"
                                        class="form-checkbox"
                                        {{ old('persetujuan_penyelesaian') == 'Selesai Sebagian' ? 'checked' : '' }}>
                                    Selesai Sebagian
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="persetujuan_penyelesaian" value="Tidak Berhasil"
                                        class="form-checkbox"
                                        {{ old('persetujuan_penyelesaian') == 'Tidak Berhasil' ? 'checked' : '' }}>
                                    Tidak Berhasil
                                </label>

                                <label class="flex items-center gap-2">
                                    Penjelasan
                                </label>
                                <input type="text" name="persetujuan_catatan" class="form-input"
                                    {{ old('persetujuan_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Kordinator SPBE
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <input type="text" name="kordinator_spbe" class="form-input"
                                    {{ old('kordinator_spbe') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Jabatan Kordinator
                            </label>
                            <div class="flex items-center gap-6 flex-1">
                                <input type="text" name="kordinator_jabatan" class="form-input"
                                    {{ old('kordinator_jabatan') }}>
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
                        <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'Selesai')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Ubah status menjadi selesai
                        </button>

                        <a href="{{ route('perubahan-layanan.closing') }}"
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
    <style>
        .form-checkbox:checked[disabled] {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
    </style>


    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

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

            let selectedId = "{{ $perubahan->perangkat_daerah_pemohon_id }}";
            let selectedText = "{{ $perubahan->perangkatPemohon->nama ?? '' }}";

            if (selectedId) {
                let option = new Option(selectedText, selectedId, true, true);
                $('#perangkat_daerah_user').append(option).trigger('change');
                $('#perangkat_daerah_user').prop("disabled", true);
            }

            const UPDATE_STATUS_URL = `{{ url('/api/perubahan-layanan/update-status-penutupan') }}`;

            async function updateStatus(id, status) {

                let formData = new FormData();

                formData.append("status", status);
                formData.append("perubahan_layanan_id", document.getElementById('idPerubahan').value);

                formData.append("tanggal_penyelesaian", document.querySelector(
                    "input[name='tanggal_penyelesaian']").value);

                formData.append("kesesuaian_hasil", document.querySelector(
                    "input[name='kesesuaian_hasil']:checked")?.value || "");

                formData.append("kesesuaian_penjelasan", document.querySelector(
                    "input[name='kesesuaian_penjelasan']").value);

                formData.append("dampak_spbe", document.querySelector("input[name='dampak_spbe']:checked")
                    ?.value ||
                    "");
                formData.append("dampak_spbe_penjelasan", document.querySelector(
                        "input[name='dampak_spbe_penjelasan']")
                    .value);

                formData.append("persetujuan_penyelesaian", document.querySelector(
                    "input[name='persetujuan_penyelesaian']:checked")?.value || "");
                formData.append("persetujuan_catatan", document.querySelector(
                    "input[name='persetujuan_catatan']").value);

                formData.append("kordinator_spbe", document.querySelector(
                    "input[name='kordinator_spbe']").value);

                formData.append("kordinator_jabatan", document.querySelector(
                    "input[name='kordinator_jabatan']").value);

                try {
                    const res = await fetch(`${UPDATE_STATUS_URL}/${id}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        },
                        body: formData
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        let errorHtml = "";

                        // Jika error berbentuk object (Laravel validation)
                        if (data.errors) {
                            errorHtml = "<ul style='text-align:center;'>";
                            for (let field in data.errors) {
                                data.errors[field].forEach(msg => {
                                    errorHtml += `<li>${msg}</li>`;
                                });
                            }
                            errorHtml += "</ul>";
                        }

                        // Jika error berupa message string
                        else {
                            errorHtml = data.message || "Terjadi kesalahan.";
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorHtml
                        });
                        return;
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: data.message,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('perubahan-layanan.closing') }}";
                    }, 1200);

                } catch (error) {
                    Swal.fire("Error!", error.message, "error");
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
                        let errorHtml = "";

                        // Jika error berbentuk object (Laravel validation)
                        if (data.errors) {
                            errorHtml = "<ul style='text-align:left;'>";
                            for (let field in data.errors) {
                                data.errors[field].forEach(msg => {
                                    errorHtml += `<li>${msg}</li>`;
                                });
                            }
                            errorHtml += "</ul>";
                        } else {
                            errorHtml = data.message || "Terjadi kesalahan.";
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorHtml
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
                        window.location.href = "{{ route('perubahan-layanan.closing') }}";
                    }, 1200);

                } catch (err) {
                    Swal.fire("Error!", err.message, "error");
                }
            }

            window.submitDitolak = submitDitolak;

        });
    </script>

</x-app-layout>
