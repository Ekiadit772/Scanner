<x-app-layout>
    <x-alert />

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <x-breadcrumb :items="[['label' => 'Manajemen Data Master & Referensi'], ['label' => 'Peta Jalan SPBE']]" />
    </div>

    <div class="panel mt-6 text-center p-10">
        <div class="flex flex-col items-center justify-center space-y-6">
            <div class="relative">
                <div class="absolute inset-0 blur-2xl opacity-30 bg-yellow-300 rounded-full w-32 h-32 mx-auto"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-yellow-500 relative z-10 animate-bounce"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-3-3v6m9 9H3a1.5 1.5 0 01-1.5-1.5V3A1.5 1.5 0 013 1.5h18A1.5 1.5 0 0122.5 3v18A1.5 1.5 0 0121 21z" />
                </svg>
            </div>

            <h5 class="font-semibold text-2xl text-gray-800 dark:text-white-light">Halaman Sedang Dalam Konstruksi
            </h5>
            <p class="text-gray-600 dark:text-gray-400 max-w-xl">
                Kami sedang menyiapkan fitur terbaik untuk Anda. Mohon bersabar — halaman ini akan segera tersedia dalam
                waktu dekat.
            </p>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-5 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-all duration-300 shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
