<script>
    document.addEventListener("alpine:init", () => {
        Alpine.store('tableStore', {
            datatable: null,

            async initTable() {
                await this.loadData();
            },

            async loadData() {
                const res = await fetch("{{ url('/api/perangkat-daerah/get-perangkat-daerah') }}");
                const result = await res.json();

                if (this.datatable) {
                    this.datatable.destroy();
                }

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: ["No", "Kode", "Nama", "Nama Kepala Dinas",
                            "NIP Kepala Dinas", "id", "Aksi"
                        ],
                        data: result.data
                    },
                    searchable: true,
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50, 100],
                    columns: [{
                            select: 5,
                            hidden: true
                        },
                        {
                            select: 6,
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

        Alpine.data("PerangkatDaerahFormHandler", () => ({
            async submitForm(e) {
                e.preventDefault();

                const form = this.$refs.PerangkatDaerahForm;
                const formData = new FormData(form);
                const perdaId = form.getAttribute('data-id');
                let url = '';
                let method = 'POST';
                let action = '';

                if (perdaId) {
                    url = `{{ url('/api/perangkat-daerah/update') }}/${perdaId}`;
                    formData.append('_method', 'PUT');
                    action = 'Perangkat Daerah berhasil diperbarui!';
                } else {
                    url = `{{ url('/api/perangkat-daerah/store') }}`;
                    action = 'Perangkat Daerah berhasil ditambahkan!';
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

                    window.dispatchEvent(new CustomEvent('close-perangkat-daerah-modal'));

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

    window.addEventListener('open-modal', () => {
        enableFormFields();
        document.getElementById('PerangkatDaerahForm').reset();
        document.getElementById('PerangkatDaerahForm').removeAttribute('data-id');

        const img = document.getElementById('previewGambar');
        img.style.display = 'none';
        img.src = '';
    });

    async function deletePerangkatDaerah(hashedId) {
        Swal.fire({
            title: "Hapus perangkat daerah?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal"
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(
                    `{{ url('/api/perangkat-daerah') }}/${hashedId}/destroy`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')
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
                    throw new Error(data.message || "Gagal menghapus perangkat daerah.");
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
                    title: data.message || 'Perangkat daerah berhasil dihapus!',
                });

                setTimeout(async () => {
                    await Alpine.store('tableStore').reloadTable();
                }, 800);

            } catch (err) {
                Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.",
                    "error");
            }
        });
    }


    function editPerangkatDaerah(hashedId) {
        enableFormFields();

        const img = document.getElementById('previewGambar');
        img.style.display = 'none';
        img.src = '';

        fetch(`{{ url('/api/perangkat-daerah') }}/${hashedId}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const perda = data.data.perangkatDaerah;
                    document.getElementById('kode').value = perda.kode;
                    document.getElementById('nama').value = perda.nama;
                    document.getElementById('kadis_nama').value = perda.kadis_nama ?? '';
                    document.getElementById('kadis_nip').value = perda.kadis_nip ?? '';

                    document.getElementById('PerangkatDaerahForm').setAttribute('data-id', hashedId);
                    document.getElementById('modal-title').textContent = 'Edit Perangkat Daerah';

                    window.dispatchEvent(new CustomEvent('open-edit-modal'));
                } else {
                    Swal.fire("Gagal!", data.message || "Gagal mengambil data perangkat daerah.",
                        "error");
                }
            })
            .catch(err => Swal.fire("Gagal!", err.message, "error"));
    }

    function detailPerangkatDaerah(hashedId) {
        fetch(`{{ url('/api/perangkat-daerah') }}/${hashedId}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const perda = data.data.perangkatDaerah;

                    document.getElementById('kode').value = perda.kode;
                    document.getElementById('nama').value = perda.nama;
                    document.getElementById('kadis_nama').value = perda.kadis_nama ?? '';
                    document.getElementById('kadis_nip').value = perda.kadis_nip ?? '';

                    disableFormFields();

                    if (perda.kadis_tte) {
                        const img = document.getElementById('previewGambar');

                        const fileName = perda.kadis_tte.split('/').pop();

                        img.src = `{{ url('/api/perangkat-daerah/tte') }}/${fileName}`;
                        img.style.display = 'block';
                    } else {
                        document.getElementById('previewGambar').style.display = 'none';
                    }

                    document.getElementById('modal-title').textContent = 'Detail Perangkat Daerah';

                    window.dispatchEvent(new CustomEvent('open-edit-modal'));
                } else {
                    Swal.fire("Gagal!", data.message || "Gagal mengambil data perangkat daerah.",
                        "error");
                }
            })
            .catch(err => Swal.fire("Gagal!", err.message, "error"));
    }

    function disableFormFields() {
        document.querySelectorAll(
                '#PerangkatDaerahForm input, #PerangkatDaerahForm textarea, #PerangkatDaerahForm select')
            .forEach(el => el.setAttribute('disabled', true));
    }

    function enableFormFields() {
        document.querySelectorAll(
                '#PerangkatDaerahForm input, #PerangkatDaerahForm textarea, #PerangkatDaerahForm select')
            .forEach(el => el.removeAttribute('disabled'));
    }
</script>
