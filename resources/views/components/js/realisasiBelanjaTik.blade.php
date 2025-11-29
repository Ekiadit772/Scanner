<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {

        let datatable = null;
        let currentPage = 1;
        let perPage = 10;

        let filterPerangkatDaerah = null;
        let filterTahun = null;
        let searchTerm = "";

        $('#filter-perangkat-daerah').select2({
            placeholder: "Pilih Perangkat Daerah",
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ url('/api/insiden-layanan/get-perangkat-daerah') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term
                }),
                processResults: data => ({
                    results: data.results
                })
            }
        });

        $('#filter-tahun').select2({
            placeholder: "Pilih Tahun",
            width: '100%',
            allowClear: true,
        });

        $('#filter-perangkat-daerah').on('change', function() {
            filterPerangkatDaerah = $(this).val();
            reloadTable();
        });

        $('#filter-tahun').on('change', function() {
            filterTahun = $(this).val();
            reloadTable();
        });

        $('#search-manajemen').on('keyup', function() {
            searchTerm = $(this).val();
            reloadTable();
        });

        function reloadTable() {
            loadData(1);
        }

        function loadData(page = 1) {

            $.ajax({
                url: "{{ url('/api/realisasi-belanja-tik/list') }}",
                method: "GET",
                data: {
                    page,
                    per_page: perPage,
                    search: searchTerm,
                    perangkat_daerah_id: filterPerangkatDaerah,
                    tahun: filterTahun
                },
                beforeSend: function() {
                    $("#myTable2").html(
                        "<tr><td colspan='7' class='p-3 text-center'>Memuat...</td></tr>");
                },
                success: function(result) {
                    if (datatable) datatable.destroy();
                    $("#myTable2").html("");

                    const tableData = (result.data || []).map((item) => [
                        item.no,
                        item.perangkat_daerah,
                        item.judul,
                        item.tahun,
                        formatRupiah(item.jumlah_anggaran),
                        item.sumber_anggaran,
                        item.nama_ppk
                    ]);

                    datatable = new simpleDatatables.DataTable("#myTable2", {
                        data: {
                            headings: [
                                "NO",
                                "Perangkat Daerah",
                                "Judul",
                                "Tahun",
                                "Jumlah Anggaran",
                                "Sumber Anggaran",
                                "Nama PPK"
                            ],
                            data: tableData
                        },
                        searchable: false,
                        perPage: perPage,
                        perPageSelect: [5, 10, 20, 50]
                    });

                    createPagination(result.last_page, result.current_page);
                }
            });
        }

        function createPagination(totalPages, current) {
            let html = '';

            if (totalPages > 5) {
                html +=
                    `<button class="page-btn-prev bg-gray-200 px-3 py-1 rounded mx-1" ${current === 1 ? 'disabled' : ''}>Prev</button>`;
            }

            let start = Math.max(1, current - 2);
            let end = Math.min(totalPages, start + 4);
            if (end - start < 4) start = Math.max(1, end - 4);

            for (let i = start; i <= end; i++) {
                html +=
                    `<button class="page-btn ${i===current?'bg-blue-600 text-white':'bg-gray-200'} px-3 py-1 rounded mx-1" data-page="${i}">${i}</button>`;
            }

            if (totalPages > 5) {
                html +=
                    `<button class="page-btn-next bg-gray-200 px-3 py-1 rounded mx-1" ${current === totalPages ? 'disabled' : ''}>Next</button>`;
            }

            $("#pagination-container").html(html);
        }

        $(document).on("click", ".page-btn", function() {
            currentPage = $(this).data("page");
            loadData(currentPage);
        });

        $(document).on("click", ".page-btn-prev", function() {
            if (currentPage > 1) {
                currentPage--;
                loadData(currentPage);
            }
        });

        $(document).on("click", ".page-btn-next", function() {
            currentPage++;
            loadData(currentPage);
        });

        function formatRupiah(angka) {
            if (!angka) return "Rp 0";
            return "Rp " + parseInt(angka)
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function loadSummary() {
            $.ajax({
                url: "{{ url('/api/realisasi-belanja-tik/summary') }}",
                method: "GET",
                success: function(res) {
                    animateCounter("summary-total-anggaran", 0, res.total_anggaran ?? 0);
                    $("#summary-total-layanan").text(res.total_layanan ?? 0);
                }
            });
        }

        function animateCounter(elementId, start, end, duration = 1000) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                document.getElementById(elementId).innerText = formatRupiah(value);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }


        loadData();
        loadSummary();

    });
</script>
