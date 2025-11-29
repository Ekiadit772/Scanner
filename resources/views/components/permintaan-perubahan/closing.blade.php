@props(['perubahan'])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-5">

    <div class="lg:col-span-2 flex items-center">
        <h5 class="font-semibold text-lg dark:text-white-light">Pelaporan Perubahan</h5>
        <hr class="border-t border-primary ml-3 flex-1" />
    </div>
</div>

<div class="lg:col-span-2">

    {{-- TIM PELAKSANA --}}
    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center mb-3 sm:mb-0 w-full">
            <label for="tim_pelaksana" class="sm:w-44 text-sm mt-1 font-medium">
                Tim Pelaksana / Penanggung Jawab
            </label>
            <div class="flex-1">
                <x-text-input id="tim_pelaksana" class="w-full" type="text" name="tim_pelaksana"
                    value="{{ old('tim_pelaksana', $perubahan->pelaporan->tim_pelaksana) }}" readonly />
            </div>
        </div>
    </div>

    {{-- TANGGAL PELAKSANAAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Tanggal Pelaksanaan</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $tanggal_rencana = old('tanggal_rencana', $perubahan->pelaporan->tanggal_rencana);
            @endphp

            <label class="flex items-center gap-2 w-full">
                <input type="radio" name="tanggal_rencana" value="Sesuai Rencana" disabled
                    {{ $tanggal_rencana == 'Sesuai Rencana' ? 'checked' : '' }}>
                Sesuai Rencana
            </label>

            <label class="flex items-center gap-2 w-full">
                <input type="radio" name="tanggal_rencana" value="Tidak Sesuai Rencana" disabled
                    {{ $tanggal_rencana == 'Tidak Sesuai Rencana' ? 'checked' : '' }}>
                Tidak Sesuai Rencana
            </label>

            <label class="flex items-center gap-2">Tanggal Mulai</label>
            <input type="date" readonly name="tanggal_mulai" class="form-input"
                value="{{ old('tanggal_mulai', $perubahan->pelaporan->tanggal_mulai) }}">

            <label class="flex items-center gap-2">Tanggal Selesai</label>
            <input type="date" readonly name="tanggal_selesai" class="form-input"
                value="{{ old('tanggal_selesai', $perubahan->pelaporan->tanggal_selesai) }}">
        </div>
    </div>

    {{-- ANGGARAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Anggaran</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $anggaran = old('anggaran', $perubahan->pelaporan->anggaran);
            @endphp

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="anggaran" value="Memadai"
                    {{ $anggaran == 'Memadai' ? 'checked' : '' }}>
                Memadai
            </label>

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="anggaran" value="Tidak Memadai"
                    {{ $anggaran == 'Tidak Memadai' ? 'checked' : '' }}>
                Tidak Memadai
            </label>

            <label class="flex items-center gap-2">Catatan</label>
            <input type="text" readonly name="anggaran_catatan" class="form-input"
                value="{{ old('anggaran_catatan', $perubahan->pelaporan->anggaran_catatan) }}">
        </div>
    </div>

    {{-- SUMBER DAYA LAIN --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Sumber Daya Lain</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $sd = old('sumber_daya_lain', $perubahan->pelaporan->sumber_daya_lain);
            @endphp

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="sumber_daya_lain" value="Tersedia"
                    {{ $sd == 'Tersedia' ? 'checked' : '' }}>
                Tersedia
            </label>

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="sumber_daya_lain" value="Tidak Tersedia"
                    {{ $sd == 'Tidak Tersedia' ? 'checked' : '' }}>
                Tidak Tersedia
            </label>

            <label class="flex items-center gap-2">Catatan</label>
            <input type="text" readonly name="sumber_daya_lain_catatan" class="form-input"
                value="{{ old('sumber_daya_lain_catatan', $perubahan->pelaporan->sumber_daya_lain_catatan) }}">
        </div>
    </div>

    {{-- KOMUNIKASI PERUBAHAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Komunikasi Perubahan</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $kom = old('komunikasi_perubahan', $perubahan->pelaporan->komunikasi_perubahan);
            @endphp

            <label class="flex items-center gap-2 w-full">
                <input type="radio" name="komunikasi_perubahan" value="Dilaksanakan" disabled class="form-checkbox"
                    {{ $kom == 'Dilaksanakan' ? 'checked' : '' }}>
                Dilaksanakan
            </label>

            <label class="flex items-center gap-2 w-full">
                <input type="radio" name="komunikasi_perubahan" value="Tidak Dilaksanakan" disabled
                    class="form-checkbox" {{ $kom == 'Tidak Dilaksanakan' ? 'checked' : '' }}>
                Tidak Dilaksanakan
            </label>

            <label class="flex items-center gap-2">Catatan</label>
            <input type="text" disabled name="komunikasi_perubahan_catatan" class="form-input"
                value="{{ old('komunikasi_perubahan_catatan', $perubahan->pelaporan->komunikasi_perubahan_catatan) }}">
        </div>
    </div>

    {{-- LAINNYA --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Lainnya</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $lain = old('lainnya', $perubahan->pelaporan->lainnya);
            @endphp

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="lainnya" value="Dilaksanakan"
                    {{ $lain == 'Dilaksanakan' ? 'checked' : '' }}>
                Dilaksanakan
            </label>

            <label class="flex items-center gap-2 w-full">
                <input type="radio" disabled class="form-checkbox" name="lainnya" value="Tidak Dilaksanakan"
                    {{ $lain == 'Tidak Dilaksanakan' ? 'checked' : '' }}>
                Tidak Dilaksanakan
            </label>

            <label class="flex items-center gap-2">Catatan</label>
            <input type="text" readonly name="lainnya_catatan" class="form-input"
                value="{{ old('lainnya_catatan', $perubahan->pelaporan->lainnya_catatan) }}">
        </div>
    </div>

    {{-- LANGKAH IMPLEMENTASI --}}
    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center w-full">
            <label for="langkah_implementasi" class="sm:w-44 text-sm mt-1 font-medium">
                Langkah - Langkah Implementasi Perubahan
            </label>
            <div class="flex-1">
                <x-text-input id="langkah_implementasi" class="w-full" type="text" name="langkah_implementasi"
                    readonly value="{{ old('langkah_implementasi', $perubahan->pelaporan->langkah_implementasi) }}"
                    placeholder="Isi langkah - langkah implementasi perubahan" />
            </div>
        </div>
    </div>

    {{-- STATUS PELAKSANAAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center mb-3 mt-3">
        <label class="sm:w-44 mt-2">Status Pelaksanaan</label>
        <div class="flex items-center gap-6 flex-1">

            @php
                $status_p = old('status_pelaksanaan', $perubahan->pelaporan->status_pelaksanaan);
            @endphp

            @foreach (['Selesai', 'Parsial', 'Tertunda', 'Gagal'] as $item)
                <label class="flex items-center gap-2">
                    <input type="radio" disabled class="form-checkbox" name="status_pelaksanaan"
                        value="{{ $item }}" {{ $status_p == $item ? 'checked' : '' }}>
                    {{ $item }}
                </label>
            @endforeach

        </div>
    </div>

    {{-- CATATAN KHUSUS --}}
    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center w-full">
            <label for="catatan_khusus" class="sm:w-44 text-sm mt-1 font-medium">
                Catatan Khusus Saat Pelaksanaan
            </label>
            <div class="flex-1">
                <x-text-input id="catatan_khusus" class="w-full" type="text" name="catatan_khusus" readonly
                    value="{{ old('catatan_khusus', $perubahan->pelaporan->catatan_khusus) }}"
                    placeholder="Isi catatan pelaksanaan" />
            </div>
        </div>
    </div>

    {{-- BUKTI IMPLEMENTASI --}}
    <div class="flex flex-col sm:flex-row sm:space-x-6 mb-3">
        <div class="flex flex-col sm:flex-row sm:items-center w-full">
            <label class="sm:w-44 text-sm mt-1 font-medium">
                Bukti Implementasi
            </label>
            <div class="flex-1">

                @if ($perubahan->pelaporan->bukti_implementasi)
                    <a href="{{ asset('storage/' . $perubahan->pelaporan->bukti_implementasi) }}" target="_blank"
                        class="text-blue-600 hover:underline">
                        {{ basename($perubahan->pelaporan->bukti_implementasi) }}
                    </a>
                @else
                    <p class="text-gray-500">Tidak ada file</p>
                @endif

            </div>
        </div>
    </div>

</div>
