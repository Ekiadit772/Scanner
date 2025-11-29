<script>
    document.addEventListener("alpine:init", () => {
        Alpine.store('tableStore', {
            datatable: null,

            async initTable() {
                await this.loadData();
            },

            async loadData() {
                const res = await fetch("{{ url('/api/jenis-layanan/get-jenis-layanan') }}");
                const result = await res.json();

                if (this.datatable) {
                    this.datatable.destroy();
                }

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: ["No", "Nama", "id", "Aksi"],
                        data: result.data
                    },
                    searchable: true,
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50, 100],
                    columns: [{
                            select: 2,
                            hidden: true
                        },
                        {
                            select: 3,
                            sortable: false,
                            render: (data, cell, row) => renderActionButtons(row)
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

        Alpine.data("JenisLayananFormHandler", () => ({
            async submitForm(e) {
                e.preventDefault();

                const form = this.$refs.JenisLayananForm;
                const formData = new FormData(form);
                const jenisLayananId = form.getAttribute('data-id');
                let url = '';
                let method = 'POST';
                let action = '';

                if (jenisLayananId) {
                    url = `{{ url('/api/jenis-layanan/update') }}/${jenisLayananId}`;
                    formData.append('_method', 'PUT');
                    action = 'Jenis Layanan berhasil diperbarui!';
                } else {
                    url = `{{ url('/api/jenis-layanan/store') }}`;
                    action = 'Jenis Layanan berhasil ditambahkan!';
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

                    window.dispatchEvent(new CustomEvent('close-jenis-layanan-modal'));

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

    function renderActionButtons(row) {
        const id = row.cells[2].data;
        let buttons = `<div class='flex items-center'>`;

        if (canEditJenisLayanan) {
            buttons +=
                `<button type="button" onclick="editJenisLayanan(${id})" class="text-blue-500 mr-2" x-tooltip="Edit">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </button>`;
        }
        if (canDeleteJenisLayanan) {
            buttons +=
                `<button type="button" onclick="deleteJenisLayanan(${id})" class="text-red-500 mr-2" x-tooltip="Hapus">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>`;
        }
        buttons += `</div>`;
        return buttons;
    }

    async function deleteJenisLayanan(id) {
        Swal.fire({
            title: "Hapus jenis layanan?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal"
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(`{{ url('/api/jenis-layanan') }}/${id}/destroy`, {
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
                    throw new Error(data.message || "Gagal menghapus jenis layanan.");
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
                    title: data.message || 'Jenis layanan berhasil dihapus!',
                });

                setTimeout(async () => {
                    await Alpine.store('tableStore').reloadTable();
                }, 800);

            } catch (err) {
                Swal.fire("Gagal!", err.message || "Terjadi kesalahan tak terduga.", "error");
            }
        });
    }


    function editJenisLayanan(id) {
        fetch(`{{ url('/api/jenis-layanan') }}/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const jenisLayanan = data.data.jenisLayanan;
                    document.getElementById('nama').value = jenisLayanan.nama;

                    document.getElementById('JenisLayananForm').setAttribute('data-id', id);
                    document.getElementById('modal-title').textContent = 'Edit Jenis Layanan';

                    window.dispatchEvent(new CustomEvent('open-edit-modal'));
                } else {
                    Swal.fire("Gagal!", data.message || "Gagal mengambil data jenis layanan.", "error");
                }
            })
            .catch(err => Swal.fire("Gagal!", err.message, "error"));
    }

    const canEditJenisLayanan = @json(auth()->user()->can('edit jenis layanan'));
    const canDeleteJenisLayanan = @json(auth()->user()->can('hapus jenis layanan'));
    const canViewJenisLayanan = @json(auth()->user()->can('lihat jenis layanan'));
    const authUserId = @json(auth()->user()->id);
</script>
