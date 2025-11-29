    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.store('tableStore', {
                datatable: null,

                async initTable() {
                    await this.loadData();
                },

                async loadData() {
                    const res = await fetch("{{ url('/api/jenis-insiden/get-jenis-insiden') }}");
                    const result = await res.json();

                    if (this.datatable) {
                        this.datatable.destroy();
                    }

                    this.datatable = new simpleDatatables.DataTable('#myTable2', {
                        data: {
                            headings: ["No", "Nama", "id", "Aksi"],
                            data: result.data
                        },
                        searchable: false,
                        perPage: 10,
                        perPageSelect: [10, 20, 30, 50, 100],
                        columns: [{
                                select: 2,
                                hidden: true
                            },
                            {
                                select: 3,
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

            Alpine.data("JenisInsidenFormHandler", () => ({
                async submitForm(e) {
                    e.preventDefault();

                    const form = this.$refs.JenisInsidenForm;
                    const formData = new FormData(form);
                    const jenisInsidenhashedId = form.getAttribute('data-id');
                    let url = '';
                    let method = 'POST';
                    let action = '';

                    if (jenisInsidenhashedId) {
                        url = `{{ url('/api/jenis-insiden/update') }}/${jenisInsidenhashedId}`;
                        formData.append('_method', 'PUT');
                        action = 'Jenis insiden berhasil diperbarui!';
                    } else {
                        url = `{{ url('/api/jenis-insiden/store') }}`;
                        action = 'Jenis insiden berhasil ditambahkan!';
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

                        window.dispatchEvent(new CustomEvent('close-jenis-insiden-modal'));

                        const toast = window.Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500,
                            padding: '1.5em',
                        });
                        toast.fire({
                            icon: 'success',
                            title: action,
                        });

                        setTimeout(async () => {
                            await Alpine.store('tableStore').reloadTable();
                        }, 800);

                    } catch (err) {
                        // Hapus pesan error lama
                        document.querySelectorAll('.error-text').forEach(e => e.remove());

                        if (err.errors) {
                            // Tampilkan error di bawah field masing-masing
                            Object.keys(err.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
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
                }
            }));

        });

        async function deleteJenisInsiden(hashedId) {
            Swal.fire({
                title: "Hapus jenis insiden?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal"
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const res = await fetch(`{{ url('/api/jenis-insiden') }}/${hashedId}/destroy`, {
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
                        throw new Error(data.message || "Gagal menghapus jenis insiden.");
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
                        title: data.message || 'Jenis insiden berhasil dihapus!',
                    });

                    setTimeout(async () => {
                        await Alpine.store('tableStore').reloadTable();
                    }, 800);

                } catch (err) {
                    Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.", "error");
                }
            });
        }


        function editJenisInsiden(hashedId) {
            fetch(`{{ url('/api/jenis-insiden') }}/${hashedId}/edit`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const jenisInsiden = data.data.jenisInsiden;
                        document.getElementById('nama').value = jenisInsiden.nama;

                        document.getElementById('JenisInsidenForm').setAttribute('data-id', hashedId);
                        document.getElementById('modal-title').textContent = 'Edit Jenis Insiden';

                        window.dispatchEvent(new CustomEvent('open-edit-modal'));
                    } else {
                        Swal.fire("Gagal!", data.message || "Gagal mengambil data jenis insiden.", "error");
                    }
                })
                .catch(err => Swal.fire("Gagal!", err.message, "error"));
        }
    </script>
