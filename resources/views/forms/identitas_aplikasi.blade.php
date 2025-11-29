<!-- resources/views/syarat/forms/identitas_aplikasi.blade.php -->
<div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded">
    <strong>Jenis Dokumen:</strong> Identitas Aplikasi
</div>

<div class="p-4 space-y-3">
    <input type="hidden" name="syarat[identitas_aplikasi][__present]" value="1">

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama Aplikasi</label>
        <input type="text" name="syarat[identitas_aplikasi][nama_aplikasi]" class="form-input w-full"
            maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Pemilik Aplikasi</label>
        <input type="text" name="syarat[identitas_aplikasi][pemilik_aplikasi]" class="form-input w-full"
            maxlength="255">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Versi</label>
        <input type="text" name="syarat[identitas_aplikasi][versi]" class="form-input w-20" maxlength="50">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[identitas_aplikasi][deskripsi]" class="form-textarea w-full"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">URL Aplikasi</label>
        <input type="url" name="syarat[identitas_aplikasi][url_aplikasi]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Username Aplikasi</label>
        <input type="text" name="syarat[identitas_aplikasi][username_aplikasi]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Password Aplikasi</label>
        <input type="text" name="syarat[identitas_aplikasi][password_aplikasi]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[identitas_aplikasi][file_pendukung]" accept=".pdf,.doc,.docx" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary">
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>
</div>
