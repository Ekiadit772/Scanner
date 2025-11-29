<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let datatable = null;
        let currentPage = 1;
        let perPage = 10;
        let searchTerm = "";
        let filterOpd = null;
        let filterBidang = null;
        let filterKelompok = null;
        let filterJenis = null;

        $('#filter-opd').select2({
            placeholder: 'Pilih Perangkat Daerah',
            allowClear: true,
            ajax: {
                url: "{{ url('/api/katalog-layanan/perangkat-daerah') }}",
                dataType: 'json',
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });


        $('#filter-bidang').select2({
            placeholder: "Pilih Bidang",
            allowClear: true
        });

        $('#filter-kelompok').select2({
            placeholder: "Pilih Kelompok Layanan",
            allowClear: true
        });
        
        $('#filter-jenis').select2({
            placeholder: "Pilih Jenis Layanan",
            allowClear: true
        });

        $('#filter-opd').on('change', function() {
            filterOpd = $(this).val();
            filterBidang = null;
            $('#filter-bidang').val(null).trigger('change');
            $('#filter-bidang').select2({
                placeholder: "Pilih Bidang",
                allowClear: true,
                ajax: {
                    url: "{{ url('/api/katalog-layanan/penyedia-layanan') }}",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            perangkat_daerah_id: filterOpd
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            reloadTable();
        });

        // Event change bidang / kelompok / search
        $('#filter-bidang').on('change', function() {
            filterBidang = $(this).val();
            reloadTable();
        });
        $('#filter-kelompok').on('change', function() {
            filterKelompok = $(this).val();
            reloadTable();
        });
        $('#filter-jenis').on('change', function() {
            filterJenis = $(this).val();
            reloadTable();
        });
        $('#search-layanan').on('keyup', function() {
            searchTerm = $(this).val();
            reloadTable();
        });

        function reloadTable() {
            loadData(1);
        }

        function loadData(page = 1) {
            $.ajax({
                url: "{{ url('/api/katalog-layanan/get-katalog-layanan') }}",
                method: "GET",
                data: {
                    page,
                    per_page: perPage,
                    search: searchTerm,
                    opd: filterOpd,
                    bidang: filterBidang,
                    kelompok: filterKelompok,
                    jenis: filterJenis
                },
                dataType: "json",
                beforeSend: function() {
                    $("#myTable2").html("<tr><td colspan='7'>Memuat data...</td></tr>");
                },
                success: function(result) {
                    const rows = result.data.map(item => ([
                        item.no,
                        `
                        <b>${item.perangkat_daerah}</b><br>
                        <small>${item.bidang}</small>
                    `,
                        `
                        <div class="space-y-1">
                            <span class="badge badge-outline-primary mb-2 text-[10px]">${item.status}</span>
                            <b class="block ">${item.nama}</b>
                            <small class="block">
                                <span>Kode: ${item.kode}</span><br>
                                <span class="badge badge-outline-success">${item.kelompok}</span>
                            </small>
                        </div>
                    `,
                        item.deskripsi ?? '-',
                        `<div class=" gap-x-6 text-sm text-gray-700">
                            <div class="flex items-start gap-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.082 3.01787L20.1081 3.76741L20.082 3.01787ZM16.5 3.48757L16.2849 2.76907V2.76907L16.5 3.48757ZM13.6738 4.80287L13.2982 4.15375L13.2982 4.15375L13.6738 4.80287ZM3.9824 3.07501L3.93639 3.8236L3.9824 3.07501ZM7 3.48757L7.19136 2.76239V2.76239L7 3.48757ZM10.2823 4.87558L9.93167 5.5386L10.2823 4.87558ZM13.6276 20.0694L13.9804 20.7312L13.6276 20.0694ZM17 18.6335L16.8086 17.9083H16.8086L17 18.6335ZM19.9851 18.2229L20.032 18.9715L19.9851 18.2229ZM10.3724 20.0694L10.0196 20.7312H10.0196L10.3724 20.0694ZM7 18.6335L7.19136 17.9083H7.19136L7 18.6335ZM4.01486 18.2229L3.96804 18.9715H3.96804L4.01486 18.2229ZM2.75 16.1437V4.99792H1.25V16.1437H2.75ZM22.75 16.1437V4.93332H21.25V16.1437H22.75ZM20.0559 2.26832C18.9175 2.30798 17.4296 2.42639 16.2849 2.76907L16.7151 4.20606C17.6643 3.92191 18.9892 3.80639 20.1081 3.76741L20.0559 2.26832ZM16.2849 2.76907C15.2899 3.06696 14.1706 3.6488 13.2982 4.15375L14.0495 5.452C14.9 4.95981 15.8949 4.45161 16.7151 4.20606L16.2849 2.76907ZM3.93639 3.8236C4.90238 3.88297 5.99643 3.99842 6.80864 4.21274L7.19136 2.76239C6.23055 2.50885 5.01517 2.38707 4.02841 2.32642L3.93639 3.8236ZM6.80864 4.21274C7.77076 4.46663 8.95486 5.02208 9.93167 5.5386L10.6328 4.21257C9.63736 3.68618 8.32766 3.06224 7.19136 2.76239L6.80864 4.21274ZM13.9804 20.7312C14.9714 20.2029 16.1988 19.6206 17.1914 19.3587L16.8086 17.9083C15.6383 18.2171 14.2827 18.8702 13.2748 19.4075L13.9804 20.7312ZM17.1914 19.3587C17.9943 19.1468 19.0732 19.0314 20.032 18.9715L19.9383 17.4744C18.9582 17.5357 17.7591 17.6575 16.8086 17.9083L17.1914 19.3587ZM10.7252 19.4075C9.71727 18.8702 8.3617 18.2171 7.19136 17.9083L6.80864 19.3587C7.8012 19.6206 9.0286 20.2029 10.0196 20.7312L10.7252 19.4075ZM7.19136 17.9083C6.24092 17.6575 5.04176 17.5357 4.06168 17.4744L3.96804 18.9715C4.9268 19.0314 6.00566 19.1468 6.80864 19.3587L7.19136 17.9083ZM21.25 16.1437C21.25 16.8295 20.6817 17.4279 19.9383 17.4744L20.032 18.9715C21.5062 18.8793 22.75 17.6799 22.75 16.1437H21.25ZM22.75 4.93332C22.75 3.47001 21.5847 2.21507 20.0559 2.26832L20.1081 3.76741C20.7229 3.746 21.25 4.25173 21.25 4.93332H22.75ZM1.25 16.1437C1.25 17.6799 2.49378 18.8793 3.96804 18.9715L4.06168 17.4744C3.31831 17.4279 2.75 16.8295 2.75 16.1437H1.25ZM13.2748 19.4075C12.4825 19.8299 11.5175 19.8299 10.7252 19.4075L10.0196 20.7312C11.2529 21.3886 12.7471 21.3886 13.9804 20.7312L13.2748 19.4075ZM13.2982 4.15375C12.4801 4.62721 11.4617 4.65083 10.6328 4.21257L9.93167 5.5386C11.2239 6.22189 12.791 6.18037 14.0495 5.452L13.2982 4.15375ZM2.75 4.99792C2.75 4.30074 3.30243 3.78463 3.93639 3.8236L4.02841 2.32642C2.47017 2.23065 1.25 3.49877 1.25 4.99792H2.75Z" fill="#1C274D"/>
                                    <path d="M12 5.854V20.9999" stroke="#1C274D" stroke-width="1.5"/>
                                    <path d="M5 9L9 10" stroke="#1C274D" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M5 13L9 14" stroke="#1C274D" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M19 13L15 14" stroke="#1C274D" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M19 5.5V9.51029C19 9.78587 19 9.92366 18.9051 9.97935C18.8103 10.035 18.6806 9.97343 18.4211 9.85018L17.1789 9.26011C17.0911 9.21842 17.0472 9.19757 17 9.19757C16.9528 9.19757 16.9089 9.21842 16.8211 9.26011L15.5789 9.85018C15.3194 9.97343 15.1897 10.035 15.0949 9.97935C15 9.92366 15 9.78587 15 9.51029V6.95002" stroke="#1C274D" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span>${item.sla} SLA</span>
                            </div>

                            <div class="flex items-start gap-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 13.4L10.7143 15L15 11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 16.0002C21 18.8286 21 20.2429 20.1213 21.1215C19.2426 22.0002 17.8284 22.0002 15 22.0002H9C6.17157 22.0002 4.75736 22.0002 3.87868 21.1215C3 20.2429 3 18.8286 3 16.0002V13.0002M16 4.00195C18.175 4.01406 19.3529 4.11051 20.1213 4.87889C21 5.75757 21 7.17179 21 10.0002V12.0002M8 4.00195C5.82497 4.01406 4.64706 4.11051 3.87868 4.87889C3.11032 5.64725 3.01385 6.82511 3.00174 9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z" stroke="#1C274C" stroke-width="1.5"/>
                                </svg>
                                <span>${item.syarat} Syarat</span>
                            </div>
                        </div>
                    `,
                        item.id,
                        item.buttons
                    ]));

                    if (datatable) {
                        datatable.destroy();
                        datatable = null;
                    }
                    // console.log(result);

                    $("#myTable2").html("");
                    datatable = new simpleDatatables.DataTable("#myTable2", {
                        data: {
                            headings: ["No", "Penyedia", "Layanan", "Deskripsi",
                                "SLA & Syarat", "id", "Aksi"
                            ],
                            data: rows
                        },
                        searchable: false,
                        perPage: perPage,
                        perPageSelect: [5, 10, 20, 50],
                        columns: [{
                                select: 0,
                                sortable: false,
                                width: "150px"
                            },
                            {
                                select: 1,
                                render: 'html'
                            },
                            {
                                select: 2,
                                render: 'html'
                            },
                            {
                                select: 3,
                                render: 'html'
                            },
                            {
                                select: 4,
                                render: 'html'
                            },
                            {
                                select: 5,
                                hidden: true
                            },
                            {
                                select: 6,
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

                    createPagination(result.last_page, result.current_page);
                }
            });
        }

        // Pagination
        function createPagination(totalPages, current) {
            let html = '';

            if (totalPages > 5) {
                html += `
            <button class="page-btn-prev bg-gray-200 text-gray-700 px-3 py-1 rounded mx-1"
                ${current === 1 ? 'disabled' : ''}>Prev</button>`;
            }

            let start = Math.max(1, current - 2);
            let end = Math.min(totalPages, start + 4);

            if (end - start < 4) {
                start = Math.max(1, end - 4);
            }

            for (let i = start; i <= end; i++) {
                html += `
            <button class="page-btn ${i===current?'bg-blue-600 text-white':'bg-gray-200 text-gray-700'}
                px-3 py-1 rounded mx-1" data-page="${i}">
                ${i}
            </button>`;
            }

            if (totalPages > 5) {
                html += `
            <button class="page-btn-next bg-gray-200 text-gray-700 px-3 py-1 rounded mx-1"
                ${current === totalPages ? 'disabled' : ''}>Next</button>`;
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
        // Event klik pagination
        $(document).on("click", ".page-btn", function() {
            const page = $(this).data("page");
            currentPage = page;
            loadData(currentPage, searchTerm);
        });

        $(document).on("click", ".btn-delete", function() {
            const hashedId = $(this).data("id");

            Swal.fire({
                title: "Hapus Katalog?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal"
            }).then(result => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: `{{ url('/api/katalog-layanan') }}/${hashedId}/destroy`,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message ||
                                "Katalog berhasil dihapus!",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        reloadTable();
                    },
                    error: function(xhr) {
                        Swal.fire("Gagal!", xhr.responseJSON?.message ||
                            "Terjadi kesalahan.", "error");
                    }
                });
            });
        });

        function loadSummary() {
            $.ajax({
                url: "{{ url('/api/katalog-layanan/summary') }}",
                method: "GET",
                success: function(res) {
                    $("#summary-layanan-permintaan").text(res.layanan_permintaan);
                    $("#summary-layanan-perubahan").text(res.layanan_perubahan);
                    $("#summary-layanan-insiden").text(res.layanan_insiden);
                    // $("#summary-kelompok-data").text(res.data);
                    // $("#summary-kelompok-data").text(res.data);
                    // $("#summary-kelompok-aplikasi").text(res.aplikasi);
                    // $("#summary-kelompok-infrastruktur").text(res.infrastruktur);
                    // $("#summary-kelompok-suprastruktur").text(res.suprastruktur);
                    $("#summary-status-dalam-antrian").text(res.dalam_antrian);
                    $("#summary-status-verifikasi").text(res.verifikasi);
                }
            });
        }

        loadSummary();
    });
</script>
