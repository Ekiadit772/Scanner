<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
    const userPerangkatId = @json(auth()->user()->perangkat_daerah_id);
    const userPerangkatNama = @json(auth()->user()->perangkatDaerah->nama ?? null);

    $(document).ready(function() {
        let datatable = null;
        let currentPage = 1;
        let perPage = 10;

        let filterPemohon = null;
        let filterPenyedia = null;
        let filterLayanan = null;
        let statusId = null;
        let searchTerm = "";

        $('#filter-pemohon').select2({
            placeholder: "Pilih Pemohon",
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ url('/api/permintaan-layanan/get-perangkat-daerah') }}",
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

        $('#filter-penyedia').select2({
            placeholder: "Pilih Penyedia Layanan",
            width: '100%',
            allowClear: true
        });
        $('#filter-layanan').select2({
            placeholder: "Pilih Layanan",
            width: '100%',
            allowClear: true
        });

        $('#filter-status').select2({
            placeholder: "Pilih Status",
            width: '100%',
            allowClear: true,
            ajax: {
                url: `{{ url('/api/status-transaksi') }}`,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        jenis: $('#jenis_transaksi').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });

        if (Number(userPerangkatId) !== -1) {
            filterPemohon = String(userPerangkatId);
            $('#filter-pemohon')
                .html(`<option value="${filterPemohon}" selected>${userPerangkatNama}</option>`)
                .trigger('change')
                .prop('disabled', true);

            $('#filter-penyedia').select2({
                placeholder: "Pilih Penyedia Layanan",
                allowClear: true,
                ajax: {
                    url: `{{ url('/api/permintaan-layanan/get-bidang') }}`,
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
        }

        $('#filter-status').on('change', function() {
            statusId = $(this).val() ? $(this).val() : null;

            console.log(statusId);
            reloadTable();
        });

        $('#filter-pemohon').on('change', function() {
            filterPemohon = $(this).val();
            filterPenyedia = null;
            filterLayanan = null;

            $('#filter-penyedia').val(null).trigger('change');
            $('#filter-layanan').val(null).trigger('change');

            if (filterPemohon) {
                $('#filter-penyedia').select2({
                    placeholder: "Pilih Penyedia Layanan",
                    allowClear: true,
                    ajax: {
                        url: `{{ url('/api/permintaan-layanan/get-bidang') }}`,
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
            }
            reloadTable();
        });

        $('#filter-penyedia').on('change', function() {
            filterPenyedia = $(this).val();
            filterLayanan = null;

            $('#filter-layanan').val(null).trigger('change');

            if (filterPenyedia) {
                $('#filter-layanan').select2({
                    placeholder: "Pilih Layanan",
                    allowClear: true,
                    ajax: {
                        url: `{{ url('/api/permintaan-layanan/get-layanan') }}/${filterPenyedia}`,
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
            }
            reloadTable();
        });

        $('#filter-layanan').on('change', function() {
            filterLayanan = $(this).val();
            reloadTable();
        });

        $('#search-manajemen').on('keyup', function() {
            searchTerm = $(this).val();
            reloadTable();
        });

        // Baca query string ?status=
        const urlParams = new URLSearchParams(window.location.search);
        const statusQuery = urlParams.get('status');
        if (statusQuery) {
            $('#filter-status').val(parseInt(statusQuery)).trigger('change');
            statusId = parseInt(statusQuery);
        }

        function reloadTable() {
            loadData(1);
        }

        function loadData(page = 1) {
            $.ajax({
                url: "{{ url('/api/permintaan-layanan/get-permintaan-layanan') }}",
                method: "GET",
                data: {
                    page,
                    per_page: perPage,
                    pemohon_id: filterPemohon,
                    penyedia_layanan_id: filterPenyedia,
                    layanan_id: filterLayanan,
                    status_id: statusId,
                    search: searchTerm
                },
                beforeSend: function() {
                    $("#myTable2").html(
                        "<tr><td colspan='7' class='p-3 text-center'>Memuat data...</td></tr>");
                },
                success: function(result) {
                    if (datatable) datatable.destroy();
                    $("#myTable2").html("");

                    const tableData = (result.data || []).map((item) => [
                        item.id, // 0
                        item.no, // 1
                        `<span class="badge badge-outline-primary text-[10px]">${item.status}</span>
                        <div style="min-width:100px;" class="mt-2"><b>Nomor: ${item.status_id==1?item.no_antrian:item.no_permohonan}</b></div>
                         <small>Tanggal: ${item.tanggal}</small><br>`,
                        `<b>${item.pemohon_nama}</b><br>
                         <small>
                            <div style="display:flex; gap:6px; align-items:center;"><span style="min-width:80px;">NIP: ${item.nip}</span></div>
                            <div style="display:flex; gap:6px; align-items:center;"><span style="min-width:60px;">Nama Pemohon: ${item.nama_pemohon}</span></div>
                            <div style="display:flex; gap:6px; align-items:center;"><span style="min-width:80px;">Jabatan: ${item.jabatan}</span></div>
                            <div style="margin-top:2px;">Bidang/ Unit Kerja: ${item.unit_kerja}</div>
                         </small>`, // 3
                        `<b>${item.layanan_nama}</b><br><small>Penyedia: <b>${item.penyedia_nama}</b></small>`, // 4
                        item.deskripsi_spek, // 5
                        item.status_id, // 6 (hidden)
                        item.buttons, // 7 (aksi)
                        item.status, // 8 (hidden - teks)
                        item.perangkat_daerah_pemohon_id // 9 (hidden - untuk kontrol edit)
                    ]);

                    // console.log(tableData);


                    datatable = new simpleDatatables.DataTable("#myTable2", {
                        data: {
                            headings: [
                                "id",
                                "No",
                                "Permohonan",
                                "Pemohon",
                                "Layanan yang Diminta",
                                "Deskripsi Spek",
                                "status_id",
                                "Aksi",
                                "_status_text_",
                                "_pemohon_perangkat_id_"
                            ],
                            data: tableData
                        },
                        searchable: false,
                        perPage: perPage,
                        perPageSelect: [5, 10, 20, 50],
                        columns: [{
                                select: 0,
                                hidden: true
                            }, // id
                            {
                                select: 6,
                                hidden: true
                            }, // status_id
                            {
                                select: 8,
                                hidden: true
                            }, // status_text
                            {
                                select: 9,
                                hidden: true
                            }, // perangkat_daerah_pemohon_id
                            {
                                select: 7,
                                sortable: false,
                                render: (data) => data
                            }
                        ]
                    });

                    setTimeout(() => {
                        const perPageSelector = document.querySelector(
                            '.dataTable-selector');
                        if (perPageSelector) {
                            $(perPageSelector).off('change').on('change', function() {
                                const newVal = parseInt($(this).val());
                                if (!isNaN(newVal) && newVal !== perPage) {
                                    perPage = newVal;
                                    reloadTable();
                                }
                            });
                        }
                    }, 300);

                    createPagination(result.last_page || 1, result.current_page || 1);
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire("Error", "Gagal memuat data permintaan layanan.", "error");
                }
            });
        }

        function createPagination(totalPages, current) {
            let html = '';

            if (totalPages > 5) {
                html +=
                    `<button class="page-btn-prev bg-gray-200 text-gray-700 px-3 py-1 rounded mx-1" ${current === 1 ? 'disabled' : ''}>Prev</button>`;
            }

            let start = Math.max(1, current - 2);
            let end = Math.min(totalPages, start + 4);
            if (end - start < 4) start = Math.max(1, end - 4);

            for (let i = start; i <= end; i++) {
                html +=
                    `<button class="page-btn ${i===current?'bg-blue-600 text-white':'bg-gray-200 text-gray-700'} px-3 py-1 rounded mx-1" data-page="${i}">${i}</button>`;
            }

            if (totalPages > 5) {
                html +=
                    `<button class="page-btn-next bg-gray-200 text-gray-700 px-3 py-1 rounded mx-1" ${current === totalPages ? 'disabled' : ''}>Next</button>`;
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

        loadData();

        function loadSummary() {
            $.ajax({
                url: "{{ url('/api/permintaan-layanan/summary') }}",
                method: "GET",
                success: function(res) {
                    $("#summary-dalam-antrian").text(res.dalam_antrian);
                    $("#summary-verifikasi").text(res.verifikasi);
                    $("#summary-proses").text(res.proses);
                    $("#summary-selesai").text(res.selesai);
                    $("#summary-ditolak").text(res.ditolak);
                }
            });
        }

        loadSummary();
    });
</script>
