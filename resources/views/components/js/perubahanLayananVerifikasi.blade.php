<script>
    document.addEventListener("alpine:init", () => {
        // === STORE TABLE ===
        Alpine.store('tableStore', {
            datatable: null,

            async initTable() {
                await this.loadData();
            },

            async loadData() {
                const status = {{ $status }};
                const res = await fetch(
                    `{{ url('/api/perubahan-layanan/get-by-status') }}/${status}`);
                const result = await res.json();
                console.log(status);

                if (this.datatable) {
                    this.datatable.destroy();
                }

                const tableData = (result.data || []).map((item) => [
                    item.id, // 0
                    item.no, // 1
                    `
                        <div style="min-width:100px;" class="mt-2"><b>Nomor: ${item.no_antrian}</b></div>
                         <small>Tanggal: ${item.tanggal}</small><br>`,
                    `<b>${item.pemohon_nama}</b><br>
                         <small>
                            <div style="display:flex; gap:6px; align-items:center;"><span style="min-width:80px;">Jabatan: ${item.jabatan}</span></div>
                            <div style="margin-top:2px;">Bidang/ Unit Kerja: ${item.unit_kerja}</div>
                         </small>`, // 3
                    `<b>${item.jenis_perubahan}</b>`, // 4
                    item.area_perubahan_nama, // 5
                    item.status_id, // 6 (hidden)
                    item.buttons, // 7 (aksi)
                ]);

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: [
                            "id",
                            "No",
                            "Permohonan",
                            "Pemohon",
                            "Jenis Perubahan",
                            "Area Perubahan",
                            "status_id",
                            "Aksi"
                        ],
                        data: tableData
                    },
                    searchable: true,
                    perPage: 10,
                    columns: [{
                            select: 0,
                            hidden: true
                        },
                        {
                            select: 6,
                            hidden: true
                        },
                        {
                            select: 6,
                            sortable: false,
                            render: (data) => data
                        }
                    ],
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
    });
</script>
