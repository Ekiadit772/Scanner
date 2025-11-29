<!-- resources/views/syarat/forms/surat_rekomendasi.blade.php -->
<div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded">
    <strong>Jenis Dokumen:</strong> Surat Rekomendasi
</div>
<div class="p-4 space-y-3">
    <input type="hidden" name="syarat[surat_rekomendasi][__present]" value="1">

    <div class="mb-2">
        <label class="block text-sm font-medium">Nomor Surat</label>
        <input type="text" name="syarat[surat_rekomendasi][nomor_surat]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Tanggal</label>
        <input type="date" name="syarat[surat_rekomendasi][tanggal]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="syarat[surat_rekomendasi][nama]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">NIP</label>
        <input type="text" name="syarat[surat_rekomendasi][nip]" class="form-input w-full" maxlength="50">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Jabatan</label>
        <input type="text" name="syarat[surat_rekomendasi][jabatan]" class="form-input w-full" maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[surat_rekomendasi][deskripsi]" class="form-textarea w-full"
            placeholder="Deskripsi ringkas surat surat_rekomendasi"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[surat_rekomendasi][file_pendukung]" accept=".pdf,.doc,.docx" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary">
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>
</div>
