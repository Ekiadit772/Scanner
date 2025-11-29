<x-app-layout>
    {{-- CDN ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
    </ul>

    <div class="pt-5">
        <!-- === SEMUA CHART DALAM SATU GRID === -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 mt-8">
            <!-- Chart Layanan -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Layanan per Kelompok
                </h2>
                <div id="layananChart" class="w-full max-w-sm mx-auto"></div>
            </div>

            <!-- Chart Status -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Permintaan Layanan per Status
                </h2>
                <div id="statusChart" class="w-full max-w-sm mx-auto"></div>
            </div>

            <!-- Chart Manajemen Perubahan -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Manajemen Perubahan per Status
                </h2>
                <div id="perubahanChart" class="w-full max-w-sm mx-auto"></div>
            </div>

            <!-- Chart Manajemen Insiden -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Manajemen Insiden per Status
                </h2>
                <div id="insidenChart" class="w-full max-w-sm mx-auto"></div>
            </div>
        </div>

        <!-- === TABEL UMUM === -->
        <div id="tableContainer" class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow mt-8 hidden">
            <h2 id="tableTitle" class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4"></h2>
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 border">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                    <tr id="tableHead">
                        <th class="px-3 py-2 border">No</th>
                        <th class="px-3 py-2 border">Nama Layanan</th>
                        <th class="px-3 py-2 border">Penyedia</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            try {
                // === FETCH DATA UNTUK CHART LAYANAN ===
                const chartResponse = await fetch("{{ url('/api/dashboard/chartData') }}");
                const chartData = await chartResponse.json();

                const labels = chartData.map(item => item.nama);
                const values = chartData.map(item => item.total);
                const total = values.reduce((a, b) => a + b, 0);

                const layananChart = new ApexCharts(document.querySelector("#layananChart"), {
                    chart: {
                        type: 'donut',
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        events: {
                            dataPointSelection: async function(event, chartContext, config) {
                                const index = config.dataPointIndex;
                                const kelompokNama = labels[index];
                                const detailResponse = await fetch(
                                    `{{ url('/api/dashboard/layananByKelompok') }}/${kelompokNama}`
                                );
                                const layananList = await detailResponse.json();

                                document.getElementById("tableTitle").textContent =
                                    `Daftar Layanan Berdasarkan Kelompok: ${kelompokNama}`;

                                const thead = document.getElementById("tableHead");
                                thead.innerHTML = `
                    <th class="px-3 py-2 border">No</th>
                    <th class="px-3 py-2 border">Nama Layanan</th>
                    <th class="px-3 py-2 border">Penyedia</th>
                    `;

                                const tbody = document.getElementById("tableBody");
                                tbody.innerHTML = layananList.map((item, i) => `
                    <tr class="border-b">
                        <td class="px-3 py-2 border">${i + 1}</td>
                        <td class="px-3 py-2 border">${item.nama_layanan}</td>
                        <td class="px-3 py-2 border">${item.penyedia}</td>
                    </tr>
                    `).join("");

                                document.getElementById("tableContainer").classList.remove(
                                    "hidden");
                            }
                        }
                    },
                    series: values,
                    labels: labels,
                    colors: ['#F59E0B', '#3B82F6', '#EF4444', '#EAB308', '#8B5CF6', '#06B6D4',
                        '#84CC16', '#F97316', '#22C55E'
                    ],
                    legend: {
                        position: 'bottom',
                        formatter: function(name) {
                            return name.length > 15 ? name.substring(0, 15) + '...' : name;
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 12,
                        colors: ['#fff']
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: () => total
                                    }
                                }
                            }
                        }
                    },
                    states: {
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    },
                });

                layananChart.render();

                // === FETCH DATA UNTUK CHART STATUS ===
                const statusResponse = await fetch("{{ url('/api/dashboard/statusChartData') }}");
                const statusData = await statusResponse.json();

                const statusLabels = statusData.map(item => item.status);
                const statusValues = statusData.map(item => item.total);
                const totalStatus = statusValues.reduce((a, b) => a + b, 0);

                const statusChart = new ApexCharts(document.querySelector("#statusChart"), {
                    chart: {
                        type: 'donut',
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        events: {
                            dataPointSelection: async function(event, chartContext, config) {
                                const index = config.dataPointIndex;
                                const statusNama = statusLabels[index];

                                const detailResponse = await fetch(
                                    `{{ url('/api/dashboard/permintaanByStatus') }}/${statusNama}`
                                );
                                const permintaanList = await detailResponse.json();

                                // Ubah isi tabel
                                document.getElementById("tableTitle").textContent =
                                    `Daftar Permintaan Layanan Berdasarkan Status: ${statusNama}`;

                                const thead = document.getElementById("tableHead");
                                thead.innerHTML = `
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Nama Layanan</th>
                                    <th class="px-3 py-2 border">Penyedia</th>
                                    <th class="px-3 py-2 border text-center">Jumlah</th>
                                `;

                                const tbody = document.getElementById("tableBody");
                                tbody.innerHTML = permintaanList.map((item, i) => `
                                    <tr class="border-b">
                                        <td class="px-3 py-2 border">${i + 1}</td>
                                        <td class="px-3 py-2 border">${item.nama_layanan}</td>
                                        <td class="px-3 py-2 border">${item.penyedia}</td>
                                        <td class="px-3 py-2 border text-center">${item.jumlah}</td>
                                    </tr>
                                `).join("");

                                document.getElementById("tableContainer").classList.remove(
                                    "hidden");
                            }
                        }
                    },
                    series: statusValues,
                    labels: statusLabels,
                    colors: ['#F97316', '#14B8A6', '#EAB308', '#EF4444', '#22C55E'],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 12,
                        colors: ['#fff']
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: () => totalStatus
                                    }
                                }
                            }
                        }
                    },
                    states: {
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    },
                });
                statusChart.render();

                // === CHART 3: MANAJEMEN PERUBAHAN ===
                const statusPerubahanResponse = await fetch("{{ url('/api/dashboard/perubahanChartData') }}");
                const statusPerubahanData = await statusPerubahanResponse.json();

                const statusPerubahanLabels = statusPerubahanData.map(item => item.status);
                const statusPerubahanValues = statusPerubahanData.map(item => item.total);
                const totalPerubahanStatus = statusPerubahanValues.reduce((a, b) => a + b, 0);

                const perubahanChart = new ApexCharts(document.querySelector("#perubahanChart"), {
                    chart: {
                        type: 'donut',
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        events: {
                            dataPointSelection: async function(event, chartContext, config) {
                                const index = config.dataPointIndex;
                                const statusNama = statusPerubahanLabels[index];

                                const detailResponse = await fetch(
                                    `{{ url('/api/dashboard/perubahanByStatus') }}/${statusNama}`
                                );
                                const perubahanList = await detailResponse.json();

                                // Ubah isi tabel
                                document.getElementById("tableTitle").textContent =
                                    `Daftar Perubahan Layanan Berdasarkan Status: ${statusNama}`;

                                const thead = document.getElementById("tableHead");
                                thead.innerHTML = `
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Nama Layanan</th>
                                    <th class="px-3 py-2 border">Penyedia</th>
                                    <th class="px-3 py-2 border text-center">Jumlah</th>
                                `;

                                const tbody = document.getElementById("tableBody");
                                tbody.innerHTML = perubahanList.map((item, i) => `
                                    <tr class="border-b">
                                        <td class="px-3 py-2 border">${i + 1}</td>
                                        <td class="px-3 py-2 border">${item.nama_layanan}</td>
                                        <td class="px-3 py-2 border">${item.penyedia}</td>
                                        <td class="px-3 py-2 border text-center">${item.jumlah}</td>
                                    </tr>
                                `).join("");

                                document.getElementById("tableContainer").classList.remove(
                                    "hidden");
                            }
                        }
                    },
                    series: statusPerubahanValues,
                    labels: statusPerubahanLabels,
                    colors: ['#22C55E', '#EAB308', '#EF4444'],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 12,
                        colors: ['#fff']
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: false,
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: () => totalPerubahanStatus
                                    }
                                }
                            }
                        }
                    },
                    states: {
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    },
                });
                perubahanChart.render();

                // === CHART 4: MANAJEMEN INSIDEN ===
                const statusInsidenResponse = await fetch("{{ url('/api/dashboard/insidenChartData') }}");
                const statusInsidenData = await statusInsidenResponse.json();

                const statusInsidenLabels = statusInsidenData.map(item => item.status);
                const statusInsidenValues = statusInsidenData.map(item => item.total);
                const totalInsidenStatus = statusInsidenValues.reduce((a, b) => a + b, 0);

                const insidenChart = new ApexCharts(document.querySelector("#insidenChart"), {
                    chart: {
                        type: 'donut',
                        height: 320,
                        toolbar: {
                            show: false
                        },
                        events: {
                            dataPointSelection: async function(event, chartContext, config) {
                                const index = config.dataPointIndex;
                                const statusNama = statusInsidenLabels[index];

                                const detailResponse = await fetch(
                                    `{{ url('/api/dashboard/insidenByStatus') }}/${statusNama}`
                                );
                                const insidenList = await detailResponse.json();

                                // Ubah isi tabel
                                document.getElementById("tableTitle").textContent =
                                    `Daftar Insiden Layanan Berdasarkan Status: ${statusNama}`;

                                const thead = document.getElementById("tableHead");
                                thead.innerHTML = `
                                    <th class="px-3 py-2 border">No</th>
                                    <th class="px-3 py-2 border">Nama Layanan</th>
                                    <th class="px-3 py-2 border">Penyedia</th>
                                    <th class="px-3 py-2 border text-center">Jumlah</th>
                                `;

                                const tbody = document.getElementById("tableBody");
                                tbody.innerHTML = insidenList.map((item, i) => `
                                    <tr class="border-b">
                                        <td class="px-3 py-2 border">${i + 1}</td>
                                        <td class="px-3 py-2 border">${item.nama_layanan}</td>
                                        <td class="px-3 py-2 border">${item.penyedia}</td>
                                        <td class="px-3 py-2 border text-center">${item.jumlah}</td>
                                    </tr>
                                `).join("");

                                document.getElementById("tableContainer").classList.remove(
                                    "hidden");
                            }
                        }
                    },
                    series: statusInsidenValues,
                    labels: statusInsidenLabels,
                    colors: ['#3B82F6', '#EAB308', '#F97316'],
                    legend: {
                        position: 'bottom'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 12,
                        colors: ['#fff']
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: false,
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: () => totalInsidenStatus
                                    }
                                }
                            }
                        }
                    },
                    states: {
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    },
                });
                insidenChart.render();

            } catch (error) {
                console.error("Gagal memuat data dashboard:", error);
            }
        });
    </script>
</x-app-layout>
