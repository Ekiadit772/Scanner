<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
    </ul>
    <div class="pt-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6 text-white">
            <!-- Layanan Tersedia -->
            <div class="panel bg-gradient-to-r from-blue-500 to-blue-400">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Layanan Tersedia</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="layananTersedia" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Total Layanan Tersedia
                </div>
            </div>

            <!-- Users Visit -->
            <div class="panel bg-gradient-to-r from-gray-500 to-gray-400 cursor-pointer"
                onclick="window.location='{{ route('permintaan-layanan.index') }}'">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Permintaan Layanan</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="pengajuan" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Total Permintaan Layanan Layanan
                </div>
            </div>

            <div class="panel bg-gradient-to-r from-orange-500 to-orange-400">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Dalam Antrian</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="dalam_antrian" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Permintaan Layanan Dalam Antrian
                </div>
            </div>

            <div class="panel bg-gradient-to-r from-teal-500 to-teal-400 cursor-pointer"
                onclick="window.location='{{ route('permintaan-layanan.index') }}?status=2'">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Verifikasi</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="verifikasi" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Permintaan Layanan Sudah Diverifikasi
                </div>
            </div>

            <!-- Sessions -->
            <div class="panel bg-gradient-to-r from-yellow-500 to-yellow-400 cursor-pointer"
                onclick="window.location='{{ route('permintaan-layanan.index') }}?status=3'">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Proses</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">

                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>

                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="proses" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Permintaan Layanan Sedang Diproses
                </div>
            </div>

            <!-- Time On-Site -->
            <div class="panel bg-gradient-to-r from-red-500 to-red-400 cursor-pointer"
                onclick="window.location='{{ route('permintaan-layanan.index') }}?status=5'">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Ditolak/ Tidak Disetujui</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>

                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="ditolak" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Data Permintaan Layanan ditolak/ tidak disetujui
                </div>
            </div>

            <!-- Bounce Rate -->
            <div class="panel bg-gradient-to-r from-green-500 to-green-400 cursor-pointer"
                onclick="window.location='{{ route('permintaan-layanan.index') }}?status=4'">
                <div class="flex justify-between">
                    <div class="ltr:mr-1 rtl:ml-1 text-md font-semibold">Selesai</div>
                    <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                        <a href="javascript:;" @click="toggle">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hover:opacity-80 opacity-70">
                                <circle cx="5" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <circle cx="19" cy="12" r="2" stroke="currentColor"
                                    stroke-width="1.5" />
                            </svg>
                        </a>
                        <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                            class="ltr:right-0 rtl:left-0 text-black dark:text-white-dark">
                            <li><a href="javascript:;" @click="toggle">View Report</a></li>
                            <li><a href="javascript:;" @click="toggle">Edit Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center mt-5">
                    <div id="selesai" class="text-3xl font-bold ltr:mr-3 rtl:ml-3"> ... </div>
                </div>
                <div class="flex items-center font-semibold mt-5">
                    Data Permintaan Layanan selesai
                </div>
            </div>

        </div>

        <!-- === BARIS 1: LAYANAN === -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">
            <!-- Chart Layanan -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Distribusi Layanan per Kelompok
                </h2>
                <div class="relative w-full max-w-sm mx-auto aspect-square">
                    <canvas id="layananChart"></canvas>
                </div>
            </div>

            <!-- Tabel Layanan -->
            <div id="tableLayananContainer" class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow hidden">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Daftar Layanan Berdasarkan Kelompok
                </h2>
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 border">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">No</th>
                            <th class="px-3 py-2 border">Nama Layanan</th>
                            <th class="px-3 py-2 border">Penyedia</th>
                        </tr>
                    </thead>
                    <tbody id="tableLayananBody"></tbody>
                </table>
            </div>
        </div>

        <!-- === BARIS 2: STATUS === -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">
            <!-- Chart Status -->
            <div class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Statistik Permintaan Layanan per Status
                </h2>
                <div class="relative w-full max-w-sm mx-auto aspect-square">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Tabel Status -->
            <div id="tableStatusContainer" class="panel bg-white dark:bg-gray-800 p-4 rounded-lg shadow hidden">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Daftar Permintaan Layanan Berdasarkan Status
                </h2>
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 border">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">No</th>
                            <th class="px-3 py-2 border">Nama Layanan</th>
                            <th class="px-3 py-2 border">Penyedia</th>
                        </tr>
                    </thead>
                    <tbody id="tableStatusBody"></tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                // === Fetch data cards ===
                const response = await fetch("{{ url('/api/dashboard/cardsData') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                document.getElementById('layananTersedia').textContent = data.layanan_tersedia ?? '-';
                document.getElementById('pengajuan').textContent = data.pengajuan ?? '-';
                document.getElementById('dalam_antrian').textContent = data.dalam_antrian ?? '-';
                document.getElementById('verifikasi').textContent = data.verifikasi ?? '-';
                document.getElementById('proses').textContent = data.proses ?? '-';
                document.getElementById('ditolak').textContent = data.ditolak ?? '-';
                document.getElementById('selesai').textContent = data.selesai ?? '-';

                // === Fetch data chart ===
                const chartResponse = await fetch("{{ url('/api/dashboard/chartData') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const chartData = await chartResponse.json();

                const labels = chartData.map(item => item.nama);
                const values = chartData.map(item => item.total);

                const ctx = document.getElementById('layananChart').getContext('2d');
                const layananChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                                '#06B6D4', '#F97316', '#84CC16', '#E11D48'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#374151'
                                }
                            }
                        },
                        onClick: async (evt, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const kelompokNama = labels[index];

                                const detailResponse = await fetch(
                                    `{{ url('/api/dashboard/layananByKelompok') }}/${kelompokNama}`
                                );
                                const layananList = await detailResponse.json();

                                const tbody = document.getElementById('tableLayananBody');
                                tbody.innerHTML = '';
                                layananList.forEach((item, i) => {
                                    tbody.innerHTML += `
                        <tr class="border-b">
                            <td class="px-3 py-2 border">${i + 1}</td>
                            <td class="px-3 py-2 border">${item.nama_layanan}</td>
                            <td class="px-3 py-2 border">${item.penyedia}</td>
                        </tr>
                    `;
                                });

                                document.getElementById('tableLayananContainer').classList.remove(
                                    'hidden');
                            }
                        }
                    }
                });


            } catch (error) {
                console.error('Gagal memuat data dashboard:', error);
            }

            // === Fetch data chart status ===
            const statusResponse = await fetch("{{ url('/api/dashboard/statusChartData') }}", {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const statusData = await statusResponse.json();

            const statusLabels = statusData.map(item => item.status);
            const statusValues = statusData.map(item => item.total);

            const ctx2 = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: [
                            '#F97316', // Dalam Antrian
                            '#14B8A6', // Verifikasi
                            '#EAB308', // Proses
                            '#EF4444', // Ditolak
                            '#22C55E' // Selesai
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#374151'
                            }
                        }
                    },
                    onClick: async (evt, activeEls) => {
                        if (activeEls.length > 0) {
                            const index = activeEls[0].index;
                            const statusNama = statusLabels[index];

                            const detailResponse = await fetch(
                                `{{ url('/api/dashboard/permintaanByStatus') }}/${statusNama}`
                            );
                            const permintaanList = await detailResponse.json();

                            document.querySelector('#tableStatusContainer h2').textContent =
                                `Daftar Permintaan Layanan Berdasarkan Status ${statusNama}`;

                            const tbody = document.getElementById('tableStatusBody');
                            tbody.innerHTML = '';
                            permintaanList.forEach((item, i) => {
                                tbody.innerHTML += `
                <tr class="border-b">
                    <td class="px-3 py-2 border">${i + 1}</td>
                    <td class="px-3 py-2 border">${item.nama_layanan}</td>
                    <td class="px-3 py-2 border">${item.penyedia}</td>
                    <td class="px-3 py-2 border text-center">${item.jumlah}</td>
                </tr>
            `;
                            });

                            const thead = document.querySelector('#tableStatusContainer thead tr');
                            if (!thead.querySelector('th[data-jumlah]')) {
                                thead.innerHTML +=
                                    '<th class="px-3 py-2 border" data-jumlah>Jumlah</th>';
                            }

                            document.getElementById('tableStatusContainer').classList.remove(
                                'hidden');
                        }
                    }

                }
            });

        });
    </script>

</x-app-layout>
