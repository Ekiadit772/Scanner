@props(['perubahan'])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">

    <div class="lg:col-span-2 flex items-center">
        <h5 class="font-semibold text-lg dark:text-white-light">Analisis Perubahan</h5>
        <hr class="border-t border-primary ml-3 flex-1" />
    </div>
</div>

<div class="lg:col-span-2">
    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full sm:w-1/2">
            <label for="no_permohonan" class="sm:w-44 text-sm mt-1 font-medium">No
                Permohonan</label>
            <div class="flex-1">
                <x-text-input id="no_permohonan" class="w-full" type="text" value="{{ $perubahan->no_permohonan }}"
                    readonly />
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
            <label for="dampak_perubahan" class="sm:w-44 mt-2">Dampak Perubahan</label>
            <div class="flex-1">
                <textarea id="dampak_perubahan" name="dampak_perubahan" rows="2"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm" readonly>{{ $perubahan->verifikasi->dampak_perubahan ?? '' }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">
            Tingkat Dampak Perubahan
        </label>
        <div class="flex items-center gap-6 flex-1">
            <label class="flex items-center gap-2">
                <input type="radio" disabled class="form-checkbox"
                    {{ $perubahan->verifikasi?->tingkat_dampak == 'Tinggi' ? 'checked' : '' }}> Tinggi
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" disabled class="form-checkbox"
                    {{ $perubahan->verifikasi?->tingkat_dampak == 'Sedang' ? 'checked' : '' }}> Sedang
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" disabled class="form-checkbox"
                    {{ $perubahan->verifikasi?->tingkat_dampak == 'Rendah' ? 'checked' : '' }}> Rendah
            </label>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">
            Kesiapan Personil
        </label>
        <div class="flex items-center gap-6 flex-1">
            <label class="flex items-center gap-2">
                <input type="radio" disabled class="form-checkbox"
                    {{ $perubahan->verifikasi?->kesiapan_personil == 'Tinggi' ? 'checked' : '' }}>
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="kesiapan_personil" value="Sedang" class="form-checkbox" disabled
                    class="form-checkbox" {{ $perubahan->verifikasi?->kesiapan_personil == 'Sedang' ? 'checked' : '' }}>
                Sedang
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="kesiapan_personil" value="Rendah" class="form-checkbox" disabled
                    class="form-checkbox" {{ $perubahan->verifikasi?->kesiapan_personil == 'Rendah' ? 'checked' : '' }}>
                Rendah
            </label>

            <label class="flex items-center gap-2">
                Catatan
            </label>
            <input type="text" class="form-input"
                value="{{ $perubahan->verifikasi->kesiapan_personil_catatan ?? '' }}" readonly>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">
            Kesiapan Organisasi
        </label>
        <div class="flex items-center gap-6 flex-1">
            <label class="flex items-center gap-2">
                <input type="radio" name="kesiapan_organisasi" value="Tinggi" class="form-checkbox" disabled
                    {{ $perubahan->verifikasi?->kesiapan_organisasi == 'Tinggi' ? 'checked' : '' }}>
                Tinggi
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="kesiapan_organisasi" value="Sedang" class="form-checkbox" disabled
                    {{ $perubahan->verifikasi?->kesiapan_organisasi == 'Sedang' ? 'checked' : '' }}>
                Sedang
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="kesiapan_organisasi" value="Rendah" class="form-checkbox" disabled
                    {{ $perubahan->verifikasi?->kesiapan_organisasi == 'Rendah' ? 'checked' : '' }}>
                Rendah
            </label>

            <label class="flex items-center gap-2">
                Catatan
            </label>
            <input type="text" name="kesiapan_organisasi_catatan" class="form-input"
                value="{{ $perubahan->verifikasi->kesiapan_organisasi_catatan ?? '' }}" readonly>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
            <label for="risiko_potensial" class="sm:w-44 mt-2">Risiko Potensial</label>
            <div class="flex-1">
                <textarea id="risiko_potensial" name="risiko_potensial" rows="2"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                    placeholder="Masukkan risiko potensial" disabled>{{ $perubahan->verifikasi->risiko_potensial }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
            <label for="rencana_mitigasi" class="sm:w-44 mt-2">Rencana Mitigasi
                Risiko</label>
            <div class="flex-1">
                <textarea id="rencana_mitigasi" name="rencana_mitigasi" rows="2"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                    placeholder="Masukkan rencana mitigasi risiko" disabled>{{ $perubahan->verifikasi->rencana_mitigasi }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center mb-3">
        <label for="keterangan_status" class="sm:w-44 text-sm font-medium">Komentar
            (Opsional)</label>
        <div class="flex-1">
            <textarea id="keterangan_status" class="form-textarea border rounded p-2" name="keterangan" disabled>{{ optional($perubahan->riwayatStatus->last())->keterangan }}</textarea>
        </div>
    </div>
</div>
