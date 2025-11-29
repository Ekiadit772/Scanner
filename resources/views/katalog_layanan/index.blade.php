<x-app-layout>
    <x-alert />
    <script src="{{ asset('assets/js/simple-datatables.js') }}"></script>

    <div class="flex flex-col sm:flex-row justify-between gap-3">
        <x-breadcrumb :items="[['label' => 'Pelaporan'], ['label' => 'Riwayat Katalog Layanan']]" />

        {{-- <a href="{{ route('katalog-layanan.create') }}" class="btn btn-primary btn-sm">Tambah</a> --}}
    </div>

    <!-- BARIS 1: LAYANAN (Permintaan, Perubahan, Insiden) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-secondary">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.06935 5.25839C2 5.62595 2 6.06722 2 6.94975V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14V11.7979C22 9.16554 22 7.84935 21.2305 6.99383C21.1598 6.91514 21.0849 6.84024 21.0062 6.76946C20.1506 6 18.8345 6 16.2021 6H15.8284C14.6747 6 14.0979 6 13.5604 5.84678C13.2651 5.7626 12.9804 5.64471 12.7121 5.49543C12.2237 5.22367 11.8158 4.81578 11 4L10.4497 3.44975C10.1763 3.17633 10.0396 3.03961 9.89594 2.92051C9.27652 2.40704 8.51665 2.09229 7.71557 2.01738C7.52976 2 7.33642 2 6.94975 2C6.06722 2 5.62595 2 5.25839 2.06935C3.64031 2.37464 2.37464 3.64031 2.06935 5.25839ZM12.25 10C12.25 9.58579 12.5858 9.25 13 9.25H18C18.4142 9.25 18.75 9.58579 18.75 10C18.75 10.4142 18.4142 10.75 18 10.75H13C12.5858 10.75 12.25 10.4142 12.25 10Z"
                        fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Layanan Permintaan</h4>
                <p id="summary-layanan-permintaan" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-primary">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.06935 5.25839C2 5.62595 2 6.06722 2 6.94975V9.25H21.9531C21.8809 8.20117 21.6973 7.51276 21.2305 6.99383C21.1598 6.91514 21.0849 6.84024 21.0062 6.76946C20.1506 6 18.8345 6 16.2021 6H15.8284C14.6747 6 14.0979 6 13.5604 5.84678C13.2651 5.7626 12.9804 5.64471 12.7121 5.49543C12.2237 5.22367 11.8158 4.81578 11 4L10.4497 3.44975C10.1763 3.17633 10.0396 3.03961 9.89594 2.92051C9.27652 2.40704 8.51665 2.09229 7.71557 2.01738C7.52976 2 7.33642 2 6.94975 2C6.06722 2 5.62595 2 5.25839 2.06935C3.64031 2.37464 2.37464 3.64031 2.06935 5.25839ZM21.9978 10.75H2V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14V11.7979C22 11.4227 21.9978 10.75 21.9978 10.75Z"
                        fill="currentColor" />
                </svg>
            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Layanan Perubahan</h4>
                <p id="summary-layanan-perubahan" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-warning">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2 6.94975C2 6.06722 2 5.62595 2.06935 5.25839C2.37464 3.64031 3.64031 2.37464 5.25839 2.06935C5.62595 2 6.06722 2 6.94975 2C7.33642 2 7.52976 2 7.71557 2.01738C8.51665 2.09229 9.27652 2.40704 9.89594 2.92051C10.0396 3.03961 10.1763 3.17633 10.4497 3.44975L11 4C11.8158 4.81578 12.2237 5.22367 12.7121 5.49543C12.9804 5.64471 13.2651 5.7626 13.5604 5.84678C14.0979 6 14.6747 6 15.8284 6H16.2021C18.8345 6 20.1506 6 21.0062 6.76946C21.0849 6.84024 21.1598 6.91514 21.2305 6.99383C22 7.84935 22 9.16554 22 11.7979V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V6.94975Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12.25 10C12.25 9.58579 12.5858 9.25 13 9.25H18C18.4142 9.25 18.75 9.58579 18.75 10C18.75 10.4142 18.4142 10.75 18 10.75H13C12.5858 10.75 12.25 10.4142 12.25 10Z"
                        fill="white" />
                    <path
                        d="M16.9856 3.02094C16.8321 3 16.6492 3 16.2835 3H12L12.3699 3.38312C13.0359 4.07299 13.2919 4.33051 13.5877 4.50096C13.7594 4.5999 13.9415 4.67804 14.1304 4.73383C14.4559 4.82993 14.8128 4.83538 15.7546 4.83538L16.089 4.83538C17.0914 4.83536 17.8995 4.83535 18.5389 4.91862C18.6984 4.93939 18.8521 4.96582 19 5C18.8144 3.96313 18.0043 3.15985 16.9856 3.02094Z"
                        fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Layanan Insiden</h4>
                <p id="summary-layanan-insiden" class="text-xl font-bold">0</p>
            </div>
        </div>


    </div>

    <!-- BARIS 2: KELOMPOK LAYANAN (4 Card) -->
    {{-- <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5">
        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-info">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22 11V13C22 16.7712 22 18.6569 20.8284 19.8284C19.8541 20.8028 18.3859 20.9668 15.75 20.9944V3.00559C18.3859 3.03321 19.8541 3.19724 20.8284 4.17157C22 5.34315 22 7.22876 22 11Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 3H14H14.25L14.25 21H14H10C6.22876 21 4.34315 21 3.17157 19.8284C2 18.6569 2 16.7712 2 13V11C2 7.22876 2 5.34315 3.17157 4.17157C4.34315 3 6.22876 3 10 3ZM4.75 10C4.75 9.58579 5.08579 9.25 5.5 9.25H11.5C11.9142 9.25 12.25 9.58579 12.25 10C12.25 10.4142 11.9142 10.75 11.5 10.75H5.5C5.08579 10.75 4.75 10.4142 4.75 10ZM5.75 14C5.75 13.5858 6.08579 13.25 6.5 13.25H10.5C10.9142 13.25 11.25 13.5858 11.25 14C11.25 14.4142 10.9142 14.75 10.5 14.75H6.5C6.08579 14.75 5.75 14.4142 5.75 14Z"
                        fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Data</h4>
                <p id="summary-kelompok-data" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-info">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.1809 4.2755C14.581 4.3827 14.8185 4.79396 14.7113 5.19406L10.7377 20.0238C10.6304 20.4239 10.2192 20.6613 9.81909 20.5541C9.41899 20.4469 9.18156 20.0356 9.28876 19.6355L13.2624 4.80583C13.3696 4.40573 13.7808 4.16829 14.1809 4.2755Z"
                        fill="currentColor" />
                    <path
                        d="M16.4425 7.32781C16.7196 7.01993 17.1938 6.99497 17.5017 7.27206L19.2392 8.8358C19.9756 9.49847 20.5864 10.0482 21.0058 10.5467C21.4468 11.071 21.7603 11.6342 21.7603 12.3295C21.7603 13.0248 21.4468 13.5881 21.0058 14.1123C20.5864 14.6109 19.9756 15.1606 19.2392 15.8233L17.5017 17.387C17.1938 17.6641 16.7196 17.6391 16.4425 17.3313C16.1654 17.0234 16.1904 16.5492 16.4983 16.2721L18.1947 14.7452C18.9826 14.0362 19.5138 13.5558 19.8579 13.1467C20.1882 12.7541 20.2603 12.525 20.2603 12.3295C20.2603 12.1341 20.1882 11.9049 19.8579 11.5123C19.5138 11.1033 18.9826 10.6229 18.1947 9.91383L16.4983 8.387C16.1904 8.10991 16.1654 7.63569 16.4425 7.32781Z"
                        fill="currentColor" />
                    <path
                        d="M7.50178 8.387C7.80966 8.10991 7.83462 7.63569 7.55752 7.32781C7.28043 7.01993 6.80621 6.99497 6.49833 7.27206L4.76084 8.8358C4.0245 9.49847 3.41369 10.0482 2.99428 10.5467C2.55325 11.071 2.23975 11.6342 2.23975 12.3295C2.23975 13.0248 2.55325 13.5881 2.99428 14.1123C3.41369 14.6109 4.02449 15.1606 4.76082 15.8232L6.49833 17.387C6.80621 17.6641 7.28043 17.6391 7.55752 17.3313C7.83462 17.0234 7.80966 16.5492 7.50178 16.2721L5.80531 14.7452C5.01743 14.0362 4.48623 13.5558 4.14213 13.1467C3.81188 12.7541 3.73975 12.525 3.73975 12.3295C3.73975 12.1341 3.81188 11.9049 4.14213 11.5123C4.48623 11.1033 5.01743 10.6229 5.80531 9.91383L7.50178 8.387Z"
                        fill="currentColor" />
                </svg>

            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Aplikasi</h4>
                <p id="summary-kelompok-aplikasi" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-info">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M21.25 8.5C21.25 7.09554 21.25 6.39331 20.9129 5.88886C20.767 5.67048 20.5795 5.48298 20.3611 5.33706C19.9199 5.04224 19.3274 5.00529 18.246 5.00066C18.2501 5.29206 18.25 5.59655 18.25 5.91051L18.25 6V7.25H19.25C19.6642 7.25 20 7.58579 20 8C20 8.41421 19.6642 8.75 19.25 8.75H18.25V10.25H19.25C19.6642 10.25 20 10.5858 20 11C20 11.4142 19.6642 11.75 19.25 11.75H18.25V13.25H19.25C19.6642 13.25 20 13.5858 20 14C20 14.4142 19.6642 14.75 19.25 14.75H18.25V21.25H16.75V6C16.75 4.11438 16.75 3.17157 16.1642 2.58579C15.5784 2 14.6356 2 12.75 2H10.75C8.86438 2 7.92157 2 7.33579 2.58579C6.75 3.17157 6.75 4.11438 6.75 6V21.25H5.25V14.75H4.25C3.83579 14.75 3.5 14.4142 3.5 14C3.5 13.5858 3.83579 13.25 4.25 13.25H5.25V11.75H4.25C3.83579 11.75 3.5 11.4142 3.5 11C3.5 10.5858 3.83579 10.25 4.25 10.25H5.25V8.75H4.25C3.83579 8.75 3.5 8.41421 3.5 8C3.5 7.58579 3.83579 7.25 4.25 7.25H5.25V6L5.24999 5.9105C5.24996 5.59655 5.24992 5.29206 5.25403 5.00066C4.17262 5.00529 3.58008 5.04224 3.13886 5.33706C2.92048 5.48298 2.73298 5.67048 2.58706 5.88886C2.25 6.39331 2.25 7.09554 2.25 8.5V21.25H1.75C1.33579 21.25 1 21.5858 1 22C1 22.4142 1.33579 22.75 1.75 22.75H21.75C22.1642 22.75 22.5 22.4142 22.5 22C22.5 21.5858 22.1642 21.25 21.75 21.25H21.25V8.5ZM9 11.75C9 11.3358 9.33579 11 9.75 11H13.75C14.1642 11 14.5 11.3358 14.5 11.75C14.5 12.1642 14.1642 12.5 13.75 12.5H9.75C9.33579 12.5 9 12.1642 9 11.75ZM9 14.75C9 14.3358 9.33579 14 9.75 14H13.75C14.1642 14 14.5 14.3358 14.5 14.75C14.5 15.1642 14.1642 15.5 13.75 15.5H9.75C9.33579 15.5 9 15.1642 9 14.75ZM11.75 18.25C12.1642 18.25 12.5 18.5858 12.5 19V21.25H11V19C11 18.5858 11.3358 18.25 11.75 18.25ZM9 6.25C9 5.83579 9.33579 5.5 9.75 5.5H13.75C14.1642 5.5 14.5 5.83579 14.5 6.25C14.5 6.66421 14.1642 7 13.75 7H9.75C9.33579 7 9 6.66421 9 6.25ZM9 9.25C9 8.83579 9.33579 8.5 9.75 8.5H13.75C14.1642 8.5 14.5 8.83579 14.5 9.25C14.5 9.66421 14.1642 10 13.75 10H9.75C9.33579 10 9 9.66421 9 9.25Z"
                        fill="currentColor" />
                </svg>


            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Infrastruktur</h4>
                <p id="summary-kelompok-infrastruktur" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-info">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.90695 4.25572C7.57591 2.9589 6.39994 2 5 2C3.34315 2 2 3.34315 2 5C2 6.39994 2.9589 7.57591 4.25572 7.90695C4.25194 7.93744 4.25 7.96849 4.25 8V16C4.25 16.0315 4.25195 16.0626 4.25572 16.093C2.9589 16.4241 2 17.6001 2 19C2 20.6569 3.34315 22 5 22C6.39994 22 7.57591 21.0411 7.90695 19.7443C7.93744 19.7481 7.96849 19.75 8 19.75H16C16.0315 19.75 16.0626 19.7481 16.093 19.7443C16.4241 21.0411 17.6001 22 19 22C20.6569 22 22 20.6569 22 19C22 17.6001 21.0411 16.4241 19.7443 16.093C19.7481 16.0626 19.75 16.0315 19.75 16V8C19.75 7.96849 19.7481 7.93744 19.7443 7.90695C21.0411 7.57591 22 6.39994 22 5C22 3.34315 20.6569 2 19 2C17.6001 2 16.4241 2.9589 16.093 4.25572C16.0626 4.25195 16.0315 4.25 16 4.25H8C7.96849 4.25 7.93744 4.25194 7.90695 4.25572ZM5.74428 7.90695C5.74806 7.93744 5.75 7.96849 5.75 8L5.75 16C5.75 16.0315 5.74805 16.0626 5.74428 16.093C6.80311 16.3633 7.63667 17.1969 7.90695 18.2557C7.93744 18.2519 7.96849 18.25 8 18.25H16C16.0315 18.25 16.0626 18.2519 16.093 18.2557C16.3633 17.1969 17.1969 16.3633 18.2557 16.093C18.2519 16.0626 18.25 16.0315 18.25 16V8C18.25 7.96849 18.2519 7.93744 18.2557 7.90695C17.1969 7.63667 16.3633 6.80311 16.093 5.74428C16.0626 5.74805 16.0315 5.75 16 5.75H8C7.96849 5.75 7.93744 5.74806 7.90695 5.74429C7.63666 6.80311 6.80311 7.63667 5.74428 7.90695Z"
                        fill="currentColor" />
                </svg>


            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Suprastruktur</h4>
                <p id="summary-kelompok-suprastruktur" class="text-xl font-bold">0</p>
            </div>
        </div>
    </div> --}}

    <!-- BARIS 3: Status (2 Card) -->
    {{-- <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 mt-5">
        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-600">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C21 19.6569 21 17.7712 21 14V10C21 6.22876 21 4.34315 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 8C7.25 7.58579 7.58579 7.25 8 7.25H16C16.4142 7.25 16.75 7.58579 16.75 8C16.75 8.41421 16.4142 8.75 16 8.75H8C7.58579 8.75 7.25 8.41421 7.25 8ZM7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12ZM8 15.25C7.58579 15.25 7.25 15.5858 7.25 16C7.25 16.4142 7.58579 16.75 8 16.75H13C13.4142 16.75 13.75 16.4142 13.75 16C13.75 15.5858 13.4142 15.25 13 15.25H8Z"
                        fill="currentColor" />
                </svg>
            </div>

            <div>
                <h4 class="text-base font-semibold text-gray-700">Dalam Antrian</h4>
                <p id="summary-status-dalam-antrian" class="text-xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-5 flex items-center gap-4 border">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg text-success">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447ZM10.5431 7.51724C10.8288 7.2173 10.8172 6.74256 10.5172 6.4569C10.2173 6.17123 9.74256 6.18281 9.4569 6.48276L7.14286 8.9125L6.5431 8.28276C6.25744 7.98281 5.78271 7.97123 5.48276 8.2569C5.18281 8.54256 5.17123 9.01729 5.4569 9.31724L6.59976 10.5172C6.74131 10.6659 6.9376 10.75 7.14286 10.75C7.34812 10.75 7.5444 10.6659 7.68596 10.5172L10.5431 7.51724ZM13 8.25C12.5858 8.25 12.25 8.58579 12.25 9C12.25 9.41422 12.5858 9.75 13 9.75H18C18.4142 9.75 18.75 9.41422 18.75 9C18.75 8.58579 18.4142 8.25 18 8.25H13ZM10.5431 14.5172C10.8288 14.2173 10.8172 13.7426 10.5172 13.4569C10.2173 13.1712 9.74256 13.1828 9.4569 13.4828L7.14286 15.9125L6.5431 15.2828C6.25744 14.9828 5.78271 14.9712 5.48276 15.2569C5.18281 15.5426 5.17123 16.0173 5.4569 16.3172L6.59976 17.5172C6.74131 17.6659 6.9376 17.75 7.14286 17.75C7.34812 17.75 7.5444 17.6659 7.68596 17.5172L10.5431 14.5172ZM13 15.25C12.5858 15.25 12.25 15.5858 12.25 16C12.25 16.4142 12.5858 16.75 13 16.75H18C18.4142 16.75 18.75 16.4142 18.75 16C18.75 15.5858 18.4142 15.25 18 15.25H13Z"
                        fill="currentColor"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-base font-semibold text-gray-700">Verifikasi</h4>
                <p id="summary-status-verifikasi" class="text-xl font-bold">0</p>
            </div>
        </div>
    </div> --}}

    <div class="panel mt-6">
        <h5 class="font-semibold text-lg dark:text-white-light mb-4">Data Riwayat Katalog Layanan</h5>
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">
            <div class="flex flex-wrap gap-3">
                <div class="w-56">
                    <select id="filter-opd" class="filter-select w-full"></select>
                </div>

                <div class="w-56">
                    <select id="filter-bidang" class="filter-select w-full"></select>
                </div>

                <div class="w-56">
                    <select id="filter-jenis" class="filter-select w-full">
                        <option value="" selected disabled>Pilih Jenis Layanan</option>
                        @foreach ($jenisLayanans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="w-56">
                    <select id="filter-kelompok" class="filter-select w-full">
                        <option value="" selected disabled>Pilih Kelompok Layanan</option>
                        @foreach ($kelLayanans as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="w-64">
                <input type="text" id="search-layanan" class="form-input w-full" placeholder="Cari nama layanan">
            </div>
        </div>

        <table id="myTable2" class="whitespace-nowrap w-full"></table>
        <div id="pagination-container" class="flex justify-end mt-4"></div>
    </div>

    <table id="myTable2" class="w-full"></table>
    <div id="pagination-container" class="flex justify-end mt-4"></div>
    </div>

    <style>
        table.table-checkbox thead tr th:first-child {
            width: 5% !important;
        }

        #myTable2 thead th:first-child,
        #myTable2 tbody td:first-child {
            width: 5% !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 2.5rem !important;
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
        }

        #myTable2 td,
        #myTable2 th {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        .dataTable-container {
            overflow-x: hidden !important;
        }

        #myTable2 td {
            vertical-align: top;
        }
    </style>

    @include('components.js.katalogLayanan')
</x-app-layout>
