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
                    `{{ url('/api/permintaan-layanan/get-by-status') }}/${status}`);
                const result = await res.json();
                // console.log(res);

                if (this.datatable) {
                    this.datatable.destroy();
                }

                const tableData = result.data.map((item) => [
                    item.id,
                    item.no,
                    `   <span style="min-width:100px;"><b>Nomor: ${item.no_antrian}</b></span><br>
                        <small>Tanggal: ${item.tanggal}</small>`,

                    `<b>${item.pemohon_nama}</b><br>
                        <small>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <span style="min-width:80px;">NIP: ${item.nip}</span>

                            </div>
                            <div style="display:flex; gap:6px; align-items:center;">
                                 <span style="min-width:60px;">Nama Pemohon: ${item.nama_pemohon}</span>
                            </div>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <span style="   min-width:80px;">Jabatan: ${item.jabatan}</span>
                            </div>
                            <div style="margin-top:2px;">Bidang/ Unit Kerja: ${item.unit_kerja}</div>
                        </small>`,

                    `<b>${item.layanan_nama}</b><br>
                        <small>Penyedia: <b>${item.penyedia_nama}</b></small>`,

                    item.deskripsi_spek,
                    item.buttons
                ]);

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: [
                            "id",
                            "No",
                            "Permohonan",
                            "Pemohon",
                            "Layanan yang Diminta",
                            "Deskripsi Spek",
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
