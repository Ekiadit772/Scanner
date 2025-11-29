<!-- resources/views/syarat/forms/lainnya.blade.php -->
<div class="space-y-3 p-4">
    <input type="hidden" name="syarat[{{ $syarat_id }}][__present]" value="1">
    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Dokumen</label>
        <input type="text" name="syarat[{{ $syarat_id }}][nama]" class="form-input w-full" required>
    </div>
    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[{{ $syarat_id }}][deskripsi]" class="form-textarea w-full"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[{{ $syarat_id }}][file_pendukung]"
            accept=".pdf,.doc,.docx"class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary" />
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>

</div>
