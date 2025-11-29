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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">
                    <div class="lg:col-span-1 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Status Permohonan</h5>
                        <hr class="border-t border-primary ml-3 flex-1" />
                    </div>

                    <div class="lg:col-span-2 flex items-center">
                        <h5 class="font-semibold text-lg dark:text-white-light">Pelaporan Perubahan</h5>
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
                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="tim_pelaksana" class="sm:w-44 text-sm mt-1 font-medium">Tim Pelaksana /
                                    Penanggung Jawab</label>
                                <div class="flex-1">
                                    <x-text-input id="tim_pelaksana" class="w-full" type="text" name="tim_pelaksana"
                                        :value="old('tim_pelaksana')" placeholder="Isi tim pelaksana / penanggung jawab" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Tanggal Pelaksanaan
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tanggal_rencana" value="Sesuai Rencana"
                                        class="form-checkbox"
                                        {{ old('tanggal_rencana') == 'Sesuai Rencana' ? 'checked' : '' }}>
                                    Sesuai Rencana
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tanggal_rencana" value="Tidak Sesuai Rencana"
                                        class="form-checkbox"
                                        {{ old('tanggal_rencana') == 'Tidak Sesuai Rencana' ? 'checked' : '' }}>
                                    Tidak Sesuai Rencana
                                </label>

                                <label class="flex items-center gap-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="tanggal_mulai" class="form-input"
                                    {{ old('tanggal_mulai') }}>

                                <label class="flex items-center gap-2">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="tanggal_selesai" class="form-input"
                                    {{ old('tanggal_selesai') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Anggaran
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="anggaran" value="Memadai" class="form-checkbox"
                                        {{ old('anggaran') == 'Memadai' ? 'checked' : '' }}>
                                    Memadai
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="anggaran" value="Tidak Memadai" class="form-checkbox"
                                        {{ old('anggaran') == 'Tidak Memadai' ? 'checked' : '' }}>
                                    Tidak Memadai
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="anggaran_catatan" class="form-input"
                                    {{ old('anggaran_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Sumber Daya Lain (SDM, Infrastruktur, dll)
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="sumber_daya_lain" value="Tersedia" class="form-checkbox"
                                        {{ old('sumber_daya_lain') == 'Tersedia' ? 'checked' : '' }}>
                                    Tersedia
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="sumber_daya_lain" value="Tidak Tersedia"
                                        class="form-checkbox"
                                        {{ old('sumber_daya_lain') == 'Tidak Tersedia' ? 'checked' : '' }}>
                                    Tidak Tersedia
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="sumber_daya_lain_catatan" class="form-input"
                                    {{ old('sumber_daya_lain_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Komunikasi Perubahan Terhadap Stakeholder
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="komunikasi_perubahan" value="Dilaksanakan"
                                        class="form-checkbox"
                                        {{ old('komunikasi_perubahan') == 'Dilaksanakan' ? 'checked' : '' }}>
                                    Dilaksanakan
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="komunikasi_perubahan" value="Tidak Dilaksanakan"
                                        class="form-checkbox"
                                        {{ old('komunikasi_perubahan') == 'Tidak Dilaksanakan' ? 'checked' : '' }}>
                                    Tidak Dilaksanakan
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="komunikasi_perubahan_catatan" class="form-input"
                                    {{ old('komunikasi_perubahan_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Lainnya
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="lainnya" value="Dilaksanakan" class="form-checkbox"
                                        {{ old('lainnya') == 'Dilaksanakan' ? 'checked' : '' }}>
                                    Dilaksanakan
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="lainnya" value="Tidak Dilaksanakan"
                                        class="form-checkbox"
                                        {{ old('lainnya') == 'Tidak Dilaksanakan' ? 'checked' : '' }}>
                                    Tidak Dilaksanakan
                                </label>

                                <label class="flex items-center gap-2">
                                    Catatan
                                </label>
                                <input type="text" name="lainnya_catatan" class="form-input"
                                    {{ old('lainnya_catatan') }}>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="langkah_implementasi" class="sm:w-44 text-sm mt-1 font-medium">Langkah -
                                    Langkah Implementasi Perubahan</label>
                                <div class="flex-1">
                                    <x-text-input id="langkah_implementasi" class="w-full" type="text"
                                        name="langkah_implementasi" :value="old('langkah_implementasi')"
                                        placeholder="Isi langkah - langkah implementasi perubahan" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
                            <label class="sm:w-44 mt-2">
                                Status Pelaksanaan
                            </label>
                            <div class="flex items-center gap-6 flex-1">

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="status_pelaksanaan" value="Selesai"
                                        class="form-checkbox"
                                        {{ old('status_pelaksanaan') == 'Selesai' ? 'checked' : '' }}>
                                    Selesai
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="status_pelaksanaan" value="Parsial"
                                        class="form-checkbox"
                                        {{ old('status_pelaksanaan') == 'Parsial' ? 'checked' : '' }}>
                                    Parsial
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="status_pelaksanaan" value="Tertunda"
                                        class="form-checkbox"
                                        {{ old('status_pelaksanaan') == 'Tertunda' ? 'checked' : '' }}>
                                    Tertunda
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="status_pelaksanaan" value="Gagal"
                                        class="form-checkbox"
                                        {{ old('status_pelaksanaan') == 'Gagal' ? 'checked' : '' }}>
                                    Gagal
                                </label>
                            </div>


                        </div>
                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="catatan_khusus" class="sm:w-44 text-sm mt-1 font-medium">Catatan Khusus
                                    Saat Pelaksanaan</label>
                                <div class="flex-1">
                                    <x-text-input id="catatan_khusus" class="w-full" type="text"
                                        name="catatan_khusus" :value="old('catatan_khusus')"
                                        placeholder="Isi langkah - langkah implementasi perubahan" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
                                <label for="bukti_implementasi" class="sm:w-44 text-sm mt-1 font-medium">Bukti
                                    Implementasi</label>
                                <div class="flex-1">
                                    <x-text-input id="bukti_implementasi" class="w-full" type="file"
                                        name="bukti_implementasi" :value="old('bukti_implementasi')"
                                        placeholder="Isi langkah - langkah implementasi perubahan" />
                                </div>
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
                        <button type="button" @click="updateStatus('{{ $hashedAgain }}', 'Pelaporan')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Ubah status menjadi penutupan
                        </button>

                        <a href="{{ route('perubahan-layanan.pelaporan') }}"
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

            const UPDATE_STATUS_URL = `{{ url('/api/perubahan-layanan/update-status-pelaporan') }}`;

            async function updateStatus(id, status) {

                let formData = new FormData();

                formData.append("status", status);
                formData.append("perubahan_layanan_id", document.getElementById('idPerubahan').value);

                formData.append("tim_pelaksana", document.querySelector("input[name='tim_pelaksana']").value);

                formData.append("tanggal_rencana", document.querySelector(
                    "input[name='tanggal_rencana']:checked")?.value || "");
                formData.append("tanggal_mulai", document.querySelector("input[name='tanggal_mulai']").value);
                formData.append("tanggal_selesai", document.querySelector("input[name='tanggal_selesai']")
                    .value);

                formData.append("anggaran", document.querySelector("input[name='anggaran']:checked")?.value ||
                    "");
                formData.append("anggaran_catatan", document.querySelector("input[name='anggaran_catatan']")
                    .value);

                formData.append("sumber_daya_lain", document.querySelector(
                    "input[name='sumber_daya_lain']:checked")?.value || "");
                formData.append("sumber_daya_lain_catatan", document.querySelector(
                    "input[name='sumber_daya_lain_catatan']").value);

                formData.append("komunikasi_perubahan", document.querySelector(
                    "input[name='komunikasi_perubahan']:checked")?.value || "");
                formData.append("komunikasi_perubahan_catatan", document.querySelector(
                    "input[name='komunikasi_perubahan_catatan']").value);

                formData.append("lainnya", document.querySelector("input[name='lainnya']:checked")?.value ||
                    "");
                formData.append("lainnya_catatan", document.querySelector("input[name='lainnya_catatan']")
                    .value);

                formData.append("langkah_implementasi", document.querySelector(
                    "input[name='langkah_implementasi']").value);

                formData.append("status_pelaksanaan", document.querySelector(
                    "input[name='status_pelaksanaan']:checked")?.value || "");

                formData.append("catatan_khusus", document.querySelector("input[name='catatan_khusus']").value);

                // Handle file upload
                let bukti = document.querySelector("input[name='bukti_implementasi']").files[0];
                if (bukti) {
                    formData.append("bukti_implementasi", bukti);
                }

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
                        window.location.href = "{{ route('perubahan-layanan.pelaporan') }}";
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
                        window.location.href = "{{ route('perubahan-layanan.pelaporan') }}";
                    }, 1200);

                } catch (err) {
                    Swal.fire("Error!", err.message, "error");
                }
            }

            window.submitDitolak = submitDitolak;

        });
    </script>

</x-app-layout>
