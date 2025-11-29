<x-guest-layout>

    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="{{ asset('assets/images/auth/bg-gradient.png') }}" alt="image"
                class="h-full w-full object-cover" />
        </div>
        <div
            class="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <div
                class="relative flex w-full max-w-[1502px] flex-col justify-between overflow-hidden rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 lg:min-h-[758px] lg:flex-row lg:gap-10 xl:gap-0">
                <div
                    class="relative hidden w-full items-center justify-center bg-gradient-to-bl from-[#056BDA] to-[#033974] p-5 lg:inline-flex lg:max-w-[835px] xl:-ms-32 ltr:xl:skew-x-[14deg] rtl:xl:skew-x-[-14deg]">
                    <div
                        class="absolute inset-y-0 w-8 from-primary/10 via-transparent to-transparent ltr:-right-10 ltr:bg-gradient-to-r rtl:-left-10 rtl:bg-gradient-to-l xl:w-16 ltr:xl:-right-20 rtl:xl:-left-20">
                    </div>
                    <div class="ltr:xl:-skew-x-[14deg] rtl:xl:skew-x-[14deg]">
                        <a href="/" class="w-48 block lg:w-72 ms-10">
                            <img src="{{ asset('assets/images/logo_diskominfo_full_putih.png') }}" alt="Logo"
                                class="w-full" />
                            <div class="text-lg text-white mt-3 font-bold">
                                Manajemen Pemerintahan Digital
                            </div>
                        </a>

                        <div class="ms-32 mt-24 hidden w-full max-w-[140px] lg:block">
                            <img src="{{asset('/assets/images/auth/login.svg')}}" alt="Cover Image"
                                class="w-full object-cover  " />
                        </div>
                    </div>
                </div>
                <div
                    class="relative flex w-full flex-col items-center justify-center gap-6 px-4 pb-16 pt-6 sm:px-6 lg:max-w-[667px]">
                    <div
                        class="flex w-full max-w-[440px] items-center gap-2 lg:absolute lg:end-6 lg:top-6 lg:max-w-full">
                        <a href="/" class="block w-40 lg:hidden">
                            <img src="{{ asset('assets/images/logo_diskominfo_full.png') }}" alt="Logo"
                                class="w-full" />
                        </a>

                    </div>
                    <div class="w-full max-w-[440px] lg:mt-16">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold  !leading-snug text-[#056BDA] md:text-4xl">Silahkan Masuk
                            </h1>
                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />
                            <p class="text-base font-bold leading-normal text-white-dark">Masukan email untuk mereset password</p>
                        </div>
                        <form method="POST" action="{{ route('password.email') }}" class="space-y-5 dark:text-white">
                            @csrf
                            <div>
                                <label for="Email">Email</label>
                                <div class="relative text-white-dark">
                                    <x-text-input id="Email" type="email" name="email" placeholder="Enter Email"
                                        class="ps-10" />

                                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                            <path opacity="0.5"
                                                d="M10.65 2.25H7.35C4.23873 2.25 2.6831 2.25 1.71655 3.23851C0.75 4.22703 0.75 5.81802 0.75 9C0.75 12.182 0.75 13.773 1.71655 14.7615C2.6831 15.75 4.23873 15.75 7.35 15.75H10.65C13.7613 15.75 15.3169 15.75 16.2835 14.7615C17.25 13.773 17.25 12.182 17.25 9C17.25 5.81802 17.25 4.22703 16.2835 3.23851C15.3169 2.25 13.7613 2.25 10.65 2.25Z"
                                                fill="currentColor" />
                                            <path
                                                d="M14.3465 6.02574C14.609 5.80698 14.6445 5.41681 14.4257 5.15429C14.207 4.89177 13.8168 4.8563 13.5543 5.07507L11.7732 6.55931C11.0035 7.20072 10.4691 7.6446 10.018 7.93476C9.58125 8.21564 9.28509 8.30993 9.00041 8.30993C8.71572 8.30993 8.41956 8.21564 7.98284 7.93476C7.53168 7.6446 6.9973 7.20072 6.22761 6.55931L4.44652 5.07507C4.184 4.8563 3.79384 4.89177 3.57507 5.15429C3.3563 5.41681 3.39177 5.80698 3.65429 6.02574L5.4664 7.53583C6.19764 8.14522 6.79033 8.63914 7.31343 8.97558C7.85834 9.32604 8.38902 9.54743 9.00041 9.54743C9.6118 9.54743 10.1425 9.32604 10.6874 8.97558C11.2105 8.63914 11.8032 8.14522 12.5344 7.53582L14.3465 6.02574Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <button type="submit"
                                    class="btn btn-primary !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                                    Email Password Reset Link
                                </button>
                        </form>

                        <div class="flex items-center justify-between mt-4">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    href="{{ route('login') }}">
                                    {{ __('Kembali') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <p class="absolute bottom-6 w-full text-center dark:text-white">
                        © <span id="footer-year">2022</span>. Diskominfo
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
