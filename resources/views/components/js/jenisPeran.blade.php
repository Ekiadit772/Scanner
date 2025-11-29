<script>
    document.addEventListener("alpine:init", () => {
        Alpine.store('tableStore', {
            datatable: null,

            async initTable() {
                await this.loadData();
            },

            async loadData() {
                const res = await fetch("{{ url('/api/jenis-peran/get-jenis-peran') }}");
                const result = await res.json();

                if (this.datatable) {
                    this.datatable.destroy();
                }

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: ["No", "Nama", "Peran / Tugas",
                            "id", "Aksi"
                        ],
                        data: result.data
                    },
                    searchable: false,
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50, 100],
                    columns: [{
                            select: 3,
                            hidden: true
                        },
                        {
                            select: 4,
                            sortable: false,
                            render: (data) => data
                        }
                    ],
                    layout: {
                        top: "{search}",
                        bottom: "{info}{select}{pager}"
                    }
                });
            },

            async reloadTable() {
                await this.loadData();
            }
        });

        Alpine.data("multipleTable", () => ({
            async init() {
                await Alpine.store('tableStore').initTable();
            }
        }));

        Alpine.data("JenisPeranFormHandler", () => ({
            async submitForm(e) {
                e.preventDefault();
                const form = this.$refs.JenisPeranForm;
                const formData = new FormData(form);
                const jenisPeranHashedId = form.getAttribute('data-id');

                // Hapus error lama sebelum submit
                form.querySelectorAll('.error-text').forEach(e => e.remove());

                const baseUrl = window.location.origin;
                let url = '',
                    action = '';
                if (jenisPeranHashedId) {
                    url = "{{ url('/api/jenis-peran/update') }}/" + jenisPeranHashedId;
                    formData.append('_method', 'PUT');
                    action = 'Jenis peran berhasil diperbarui!';
                } else {
                    url = "{{ url('/api/jenis-peran/store') }}";
                    action = 'Jenis peran berhasil ditambahkan!';
                }

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await res.json();
                    if (!res.ok) throw data;

                    // Tutup modal dan tampilkan notifikasi sukses
                    window.dispatchEvent(new CustomEvent('close-modal'));
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: action,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Reload tabel
                    setTimeout(() => Alpine.store('tableStore').reloadTable(), 800);

                } catch (err) {
                    // Bersihkan error lama
                    form.querySelectorAll('.error-text').forEach(e => e.remove());

                    if (err.errors) {
                        Object.keys(err.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                const errorMsg = document.createElement('p');
                                errorMsg.classList.add('text-red-500', 'text-sm',
                                    'mt-1', 'error-text');
                                errorMsg.innerText = err.errors[field][0];
                                input.insertAdjacentElement('afterend', errorMsg);
                            }
                        });
                    } else {
                        Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.",
                            "error");
                    }
                }
            },

            // fungsi clearForm
            clearForm() {
                const form = this.$refs.JenisPeranForm;
                form.reset();
                form.removeAttribute('data-id');
                form.querySelectorAll('.error-text').forEach(e => e.remove());
                document.getElementById('modal-title').textContent = 'Tambah Jenis Peran';
            }
        }));

        // Tambahkan listener untuk event modal
        window.addEventListener('open-modal', () => {
            const form = document.getElementById('JenisPeranForm');
            if (form) {
                form.reset();
                form.removeAttribute('data-id');
                form.querySelectorAll('.error-text').forEach(e => e.remove());
                document.getElementById('modal-title').textContent = 'Tambah Jenis Peran';
            }
        });

        window.addEventListener('close-modal', () => {
            const form = document.getElementById('JenisPeranForm');
            if (form) {
                form.querySelectorAll('.error-text').forEach(e => e.remove());
            }
        });

    });

    async function deleteJenisPeran(hashedId) {
        Swal.fire({
            title: "Hapus jenis peran?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal"
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`{{ url('/api/jenis-peran') }}/${hashedId}/destroy`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json'
                    }
                });

                let data = {};
                try {
                    data = await res.json();
                } catch {
                    data = {};
                }

                if (!res.ok) {
                    throw new Error(data.message || "Gagal menghapus jenis peran.");
                }

                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    padding: '1.5em',
                });
                toast.fire({
                    icon: 'success',
                    title: data.message || 'Jenis peran berhasil dihapus!',
                });

                setTimeout(async () => {
                    await Alpine.store('tableStore').reloadTable();
                }, 800);

            } catch (err) {
                Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.", "error");
            }
        });
    }


    function editJenisPeran(hashedId) {
        fetch(`{{ url('/api/jenis-peran') }}/${hashedId}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const jenisPeran = data.data.jenisPeran;
                    document.getElementById('nama').value = jenisPeran.nama;
                    document.getElementById('peran').value = jenisPeran.peran;

                    document.getElementById('JenisPeranForm').setAttribute('data-id', hashedId);
                    document.getElementById('modal-title').textContent = 'Edit Jenis Peran';

                    window.dispatchEvent(new CustomEvent('open-edit-modal'));
                } else {
                    Swal.fire("Gagal!", data.message || "Gagal mengambil data jenis peran.", "error");
                }
            })
            .catch(err => Swal.fire("Gagal!", err.message, "error"));
    }
</script>
