<style>
    #myTable2-wrapper {
        width: 100%;
        overflow-x: hidden !important;
    }

    #myTable2 th,
    #myTable2 td {
        max-width: 250px;
        overflow-wrap: anywhere;
    }

    #myTable2 {
        width: 100% !important;
        word-wrap: break-word;
        white-space: normal;
    }

    #myTable2 td,
    #myTable2 th {
        white-space: normal !important;
        word-break: break-word !important;
    }

    .subgrid-row table {
        width: 100%;
        table-layout: fixed;
    }

    .subgrid-row td,
    .subgrid-row th {
        white-space: normal;
        word-break: break-word;
    }

    #myTable2 th:nth-child(1),
    #myTable2 td:nth-child(1) {
        width: 50px !important;
        text-align: center;
        white-space: nowrap;
    }
</style>

<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let datatable = null;
        let currentPage = 1;
        let perPage = 10;
        let searchTerm = "";
        let filterOpdPemohon = null;
        let filterOpdPenyedia = null;
        let filterKelompok = null;

        $('#filter-opd-pengusul').select2({
            placeholder: 'Pilih Perangkat Daerah Pemohon',
            allowClear: true,
            ajax: {
                url: "{{ url('/api/rekapitulasi-penggunaan-layanan/report-perangkat-daerah') }}",
                dataType: 'json',
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });

        $('#filter-opd-penyedia').select2({
            placeholder: "Pilih Perangkat Daerah Penyedia",
            allowClear: true,
            ajax: {
                url: "{{ url('/api/rekapitulasi-penggunaan-layanan/report-penyedia-layanan') }}",
                dataType: 'json',
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });

        $('#filter-kelompok').select2({
            placeholder: "Pilih Kelompok Layanan",
            allowClear: true
        });



        $('#filter-opd-penyedia, #filter-opd-pengusul, #filter-kelompok').on('change', function() {
            Alpine.store('tableStore').reloadTable();
        });

        $('#search-layanan').on('keyup', function() {
            Alpine.store('tableStore').reloadTable();
        });

    });
</script>


<script>
    window.addEventListener("resize", () => {
        const tableEl = document.querySelector('#myTable2');
        if (tableEl) {
            tableEl.style.width = "100%";
            tableEl.style.tableLayout = "auto";
            setTimeout(() => {
                tableEl.style.tableLayout = "fixed";
            }, 100);
        }
    });
</script>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.store('tableStore', {
            datatable: null,

            async initTable() {
                await this.loadData(1);
            },

            async loadData(page = 1) {
                // Ambil nilai filter dari elemen select2 & search
                const opdPemohon = $('#filter-opd-pengusul').val();
                const opdPenyedia = $('#filter-opd-penyedia').val();
                const kelompok = $('#filter-kelompok').val();
                const search = $('#search-layanan').val();

                const queryString = new URLSearchParams({
                    page,
                    opd_pengusul_id: opdPemohon || '',
                    opd_penyedia_id: opdPenyedia || '',
                    kelompok_id: kelompok || '',
                    search: search || '',
                }).toString();

                const res = await fetch(
                    `{{ url('/api/rekapitulasi-penggunaan-layanan/get-rekapitulasi-penggunaan-layanan') }}?${queryString}`
                );
                const result = await res.json();

                const tableData = result.data.map((row) => [
                    row.no,
                    '+',
                    row.kelompok_layanan,
                    row.nama_layanan,
                    row.penyedia_layanan,
                    row.total_pengajuan,
                    row.id
                ]);

                if (this.datatable) this.datatable.destroy();

                this.datatable = new simpleDatatables.DataTable('#myTable2', {
                    data: {
                        headings: [
                            "No",
                            "+",
                            "Kelompok Layanan",
                            "Nama Layanan",
                            "Penyedia Layanan",
                            "Total Pengajuan",
                            "id"
                        ],
                        data: tableData
                    },
                    searchable: false,
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50, 100],
                    observe: false,
                    columns: [{
                            select: 6,
                            hidden: true
                        },
                        {
                            select: 1,
                            sortable: false
                        },
                        {
                            select: 5,
                            sortable: false
                        }
                    ],
                    layout: {
                        top: "{search}",
                        bottom: "{info}{select}{pager}"
                    }
                });

                setTimeout(() => {
                    document.querySelectorAll('#myTable2 tbody tr').forEach((tr, i) => {
                        tr.dataset.layananId = tableData[i][6];
                    });
                    this.addSubgridHandlers();
                }, 300);
            },

            addSubgridHandlers() {
                const table = document.querySelector('#myTable2');

                table.querySelectorAll('tbody tr').forEach((row) => {
                    const expandCell = row.cells[1];
                    expandCell.style.cursor = "pointer";
                    expandCell.style.textAlign = "center";
                    expandCell.style.fontWeight = "bold";
                    expandCell.style.color = "blue";

                    expandCell.addEventListener('click', async () => {
                        const layananId = row.dataset.layananId;
                        const isExpanded = row.nextElementSibling?.classList
                            .contains('subgrid-row');

                        // Tutup subgrid jika sudah terbuka
                        if (isExpanded) {
                            row.nextElementSibling.remove();
                            expandCell.textContent = '+';
                            return;
                        }

                        // Tutup subgrid lain yang terbuka
                        document.querySelectorAll('.subgrid-row').forEach(r => r
                            .remove());
                        document.querySelectorAll(
                            '#myTable2 tbody tr td:nth-child(2)').forEach(
                            cell => cell.textContent = '+');

                        expandCell.textContent = '-';
                        await this.loadSubgrid(row, layananId, 1);
                    });
                });
            },

            async loadSubgrid(row, layananId, page = 1) {
                if (row.nextElementSibling?.classList.contains('subgrid-row')) {
                    row.nextElementSibling.remove();
                }

                const loadingRow = document.createElement('tr');
                loadingRow.classList.add('subgrid-row');
                loadingRow.innerHTML = `
        <td colspan="7" class="p-3 text-sm text-gray-500">Loading...</td>
    `;
                row.insertAdjacentElement('afterend', loadingRow);

                // Fetch data dari API
                const subRes = await fetch(
                    `{{ url('/api/rekapitulasi-penggunaan-layanan/get-rekapitulasi-penggunaan-layanan') }}/${layananId}/pengajuan?page=${page}`
                );
                const subResult = await subRes.json();
                const {
                    data,
                    pagination
                } = subResult;

                const subTableHtml = `
        <tr class="subgrid-row bg-gray-50">
            <td colspan="7">
                <table class="w-full border text-sm mt-2">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border">No</th>
                            <th class="p-2 border">Nomor Permohonan (Tanggal)</th>
                            <th class="p-2 border">Nama Pemohon</th>
                            <th class="p-2 border">Deskripsi Spek</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(sub => `
                            <tr>
                                <td class="p-2 border">${sub.no}</td>
                                <td class="p-2 border">${sub.nomor_permohonan}</td>
                                <td class="p-2 border">${sub.nama_pemohon}</td>
                                <td class="p-2 border">${sub.deskripsi_spek}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div class="flex justify-between items-center text-sm mt-2">
                    <button class="prev-page px-2 py-1 bg-gray-200 rounded"
                        ${pagination.current_page === 1 ? 'disabled' : ''}>
                        ⬅ Prev
                    </button>

                    <span>Page ${pagination.current_page} of ${pagination.last_page}</span>

                    <button class="next-page px-2 py-1 bg-gray-200 rounded"
                        ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
                        Next ➡
                    </button>
                </div>
            </td>
        </tr>
    `;

                // Replace loadingRow dengan subgrid baru
                loadingRow.outerHTML = subTableHtml;

                // Rebind tombol pagination agar tetap di row yang sama
                const subgridRow = row.nextElementSibling;
                const prevBtn = subgridRow.querySelector('.prev-page');
                const nextBtn = subgridRow.querySelector('.next-page');

                prevBtn?.addEventListener('click', () => this.loadSubgrid(row, layananId, page -
                    1));
                nextBtn?.addEventListener('click', () => this.loadSubgrid(row, layananId, page +
                    1));
            },

            async reloadTable() {
                await this.loadData(1);
            }
        });

        Alpine.data("multipleTable", () => ({
            async init() {
                await Alpine.store('tableStore').initTable();
            }
        }));
    });
</script>
