<!-- resources/views/syarat/forms/dokumen_pengujian.blade.php -->
<div class="space-y-3 p-4">
    <input type="hidden" name="syarat[{{ $syarat_id }}][jenis_syarat_id]" value="{{ $syarat_id }}">
    <input type="hidden" id="dokumen_type_field" name="syarat[{{ $syarat_id }}][dokumen_type]" value="1">

    <div class="mb-2">
        <label class="block text-sm font-medium">Nomor Dokumen</label>
        <input type="text" name="syarat[{{ $syarat_id }}][nomor_dokumen]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Dokumen</label>
        <input type="text" name="syarat[{{ $syarat_id }}][nama_dokumen]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Tanggal</label>
        <input type="date" name="syarat[{{ $syarat_id }}][tanggal]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Penyusun</label>
        <input type="text" name="syarat[{{ $syarat_id }}][penyusun]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[{{ $syarat_id }}][deskripsi]" class="form-textarea w-full"
            placeholder="Deskripsi ringkas dokumen teknis"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[{{ $syarat_id }}][file_pendukung]" accept=".pdf,.doc,.docx" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary">
        {{-- <p class="text-sm text-gray-500" id="fileNameDisplayDokumenTeknis"></p> --}}
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>
</div>
