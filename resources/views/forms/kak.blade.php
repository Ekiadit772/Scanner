<!-- resources/views/syarat/forms/kak.blade.php -->

<div class="space-y-3 p-4">
    <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded">
        <strong>Jenis Dokumen:</strong> KAK
    </div>
    <input type="hidden" name="syarat[kak][__present]" value="1">
    <div class="mb-2">
        <label class="block text-sm font-medium">Judul</label>
        <input type="text" name="syarat[kak][judul]" class="form-input w-full" required>
    </div>

    <div class="mb-2 flex gap-4">
        <div class="w-1/2">
            <label class="block text-sm font-medium">Tahun</label>
            <input type="number" name="syarat[kak][tahun]" class="form-input w-full" required>
        </div>

        <div class="w-1/2">
            <label class="block text-sm font-medium">Jumlah Anggaran</label>
            <input type="text" id="jumlah_anggaran" name="syarat[kak][jumlah_anggaran]"
                class="form-input w-full uang" required>
        </div>
    </div>


    <div class="mb-2">
        <label class="block text-sm font-medium">Sumber Anggaran</label>
        <input type="text" name="syarat[kak][sumber_anggaran]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Nama PPK</label>
        <input type="text" name="syarat[kak][nama_ppk]" class="form-input w-full">
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">Deskripsi</label>
        <textarea name="syarat[kak][deskripsi]" class="form-textarea w-full"></textarea>
    </div>

    <div class="mb-2">
        <label class="block text-sm font-medium">File Pendukung (pdf/doc/docx)</label>
        <input type="file" name="syarat[kak][file_pendukung]" accept=".pdf,.doc,.docx"
            class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary" />
        <p class="text-sm text-gray-500" id="fileNameDisplay"></p>
    </div>

</div>
