<!-- resources/views/syarat/forms/nda.blade.php -->
<div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded">
    <strong>Jenis Dokumen:</strong> Dokumen Non-disclosure Agreement
</div>
<div class="p-4 space-y-3">
    <input type="hidden" name="syarat[nda][__present]" value="1">

    <div class="mb-2">
        <label class="block text-sm font-medium">Nomor Dokumen</label>
        <input type="text" name="syarat[nda][nomor_dokumen]" class="form-input w-full" maxlength="50">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Dokumen</label>
        <input type="text" name="syarat[nda][nama_dokumen]" class="form-input w-full" maxlength="200">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Tanggal</label>
        <input type="date" name="syarat[nda][tanggal]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Pihak 1</label>
        <input type="text" name="syarat[nda][nama_pihak_1]" class="form-input w-full" maxlength="100">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Pihak 2</label>
        <input type="text" name="syarat[nda][nama_pihak_2]" class="form-input w-full" maxlength="100">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[nda][deskripsi]" class="form-textarea w-full" placeholder="Deskripsi ringkas NDA"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[nda][file_pendukung]" accept=".pdf,.doc,.docx"
            class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary">
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>
</div>
