<header class="z-40" :class="{ 'dark': $store.app.semidark && $store.app.menu === 'horizontal' }">
    <div class="shadow-sm">
        <div class="relative bg-white flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]">
            <div class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-40 ltr:-ml-1 rtl:-mr-1 inline"
                        src="{{ asset('/assets/images/logo_diskominfo_full.png') }}" alt="image" />
                    <span
                        class="text-lg ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle hidden md:inline dark:text-white-light transition-all duration-300">Manajemen Pemerintahan Digital</span>
                </a>

                <a href="javascript:;"
                    class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                    @click="$store.app.toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </a>
            </div>
            <div
                class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center justify-end space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                    <a href="javascript:;" class="relative group" @click="toggle()">
                        <span><img class="w-9 h-9 rounded-full object-cover saturate-50 group-hover:saturate-100"
                                src="{{ asset('/assets/images/profile-default.jpg') }}" alt="image" /></span>
                    </a>
                    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                        class="ltr:right-0 rtl:left-0 text-dark dark:text-white-dark top-11 !py-0 w-[230px] font-semibold dark:text-white-light/90">
                        <li>
                            <div class="flex items-center px-4 py-4">
                                <div class="flex-none">
                                    <img class="rounded-md w-10 h-10 object-cover"
                                        src="{{ asset('/assets/images/profile-default.jpg') }}" alt="image" />
                                </div>
                                <div class="ltr:pl-4 rtl:pr-4 truncate">
                                    <h4 class="text-base">{{ Auth::user()->name }}
                                        {{-- <span class="text-xs bg-success-light rounded text-success px-1 ltr:ml-2 rtl:ml-2">{{ Auth::user()->role }}</span> --}}
                                    </h4>
                                    <a class="text-black/60  hover:text-primary dark:text-dark-light/60 dark:hover:text-white"
                                        href="javascript:;">{{ Auth::user()->email }}</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" class="dark:hover:text-white" @click="toggle">
                                <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor"
                                        stroke-width="1.5" />
                                    <path opacity="0.5"
                                        d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                Profile</a>
                        </li>
                        <li class="border-t border-white-light dark:border-white-light/10">
                            <form method="POST" action="{{ route('logout') }}" @click="toggle">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();"
                                    class="flex justify-start dark:hover:text-white text-red-500">
                                    <svg class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2 shrink-0 rotate-90" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Sign Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- horizontal menu -->
        <x-common.navigation />
    </div>
</header>
