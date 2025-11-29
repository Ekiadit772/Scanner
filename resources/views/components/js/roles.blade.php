<script>
    document.addEventListener("alpine:init", () => {
        Alpine.store('roleTableStore', {
            datatable: null,

            async initTable() {
                await this.loadData();
            },

            async loadData() {
                const res = await fetch("{{ url('/api/roles/get-roles') }}");
                const result = await res.json();

                if (this.datatable) this.datatable.destroy();

                this.datatable = new simpleDatatables.DataTable('#tableRoles', {
                    data: {
                        headings: ["No", "Nama Role", "id", "Aksi"],
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

        Alpine.data("roleTable", () => ({
            async init() {
                await Alpine.store('roleTableStore').initTable();
            }
        }));

    });

    // === Fungsi Load Permission ===
    function loadPermissions(roleHashId, roleName) {
        const panel = document.getElementById('permissionPanel');
        const content = document.getElementById('permissionContent');
        const title = document.getElementById('roleTitle');
        panel.classList.remove('hidden');
        title.textContent = `Permission Role: ${roleName}`;
        content.innerHTML = `<div class="text-center text-gray-500 py-6">Memuat data...</div>`;

        fetch(`{{ url('/api/roles') }}/${roleHashId}/permissions`)
            .then(res => res.json())
            .then(data => renderPermissions(data))
            .catch(() => {
                content.innerHTML = `<p class="text-red-500">Gagal memuat data permission.</p>`;
            });
    }

    // === Render Permission ke Panel ===
    function renderPermissions(data) {
        const content = document.getElementById('permissionContent');
        const grouped = data.permissions;

        let html = `<form id="permissionForm" class="space-y-4">`;

        Object.keys(grouped).forEach(modul => {
            const [groupName, rawTitle] = modul.split(" : ");

            html += `
        <div class="border rounded-lg p-4 bg-gray-50">
            <h3 class="font-semibold text-lg mb-3 text-gray-700 border-b pb-1">
                ${groupName} : <span class="font-bold">${rawTitle}</span>
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
    `;
            grouped[modul].forEach(perm => {
                html += `
                    <label class="flex items-center space-x-2 p-2 bg-white border rounded hover:bg-blue-50">
                        <input type="checkbox" name="permissions[]" value="${perm.full}" ${perm.checked ? 'checked' : ''}>
                        <span class="capitalize">${perm.action}</span>
                    </label>
                `;
            });
            html += `</div></div>`;
        });

        html += `
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>`;

        content.innerHTML = html;

        const form = document.getElementById('permissionForm');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const selected = formData.getAll('permissions[]');
            savePermissions(data.role.id, selected);
        });
    }

    // === Simpan Permission via AJAX ===
    function savePermissions(roleHashId, selectedPermissions) {
        fetch(`{{ url('/api/roles') }}/${roleHashId}/permissions`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    permissions: selectedPermissions
                })
            })
            .then(res => res.json())
            .then(data => Swal.fire("Berhasil!", data.message, "success"))
            .catch(() => Swal.fire("Gagal!", "Terjadi kesalahan saat menyimpan.", "error"));
    }
</script>
