<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="post" action="{{ route('profile.update') }}"
        class="border border-[#ebedf2] dark:border-[#191e3a] rounded-md p-4 mb-5 bg-white dark:bg-[#0e1726]">
        @csrf
        @method('patch')
        <h6 class="text-lg font-bold mb-5">Informasi Umum</h6>
        <div class="flex flex-col sm:flex-row">
            <div class="ltr:sm:mr-4 rtl:sm:ml-4 w-full sm:w-2/12 mb-5">
                <img src="{{ asset('assets/images/profile-default.jpg') }}" alt="image"
                    class="w-20 h-20 md:w-32 md:h-32 rounded-full object-cover mx-auto" />
            </div>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="nip" :value="__('NIP')" />
                    <x-text-input id="nip" name="nip" type="number" class="mt-1 block w-full"
                        :value="old('nip', $user->nip)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                </div>
                <div>
                    <x-input-label for="phone" :value="__('No Telp')" />
                    <x-text-input id="phone" name="phone" type="number" class="mt-1 block w-full"
                        :value="old('phone', $user->phone)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" readonly x-tooltip="Tidak bisa diubah"/>
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <x-text-input id="role" type="text" class="mt-1 block w-full" x-tooltip="Tidak bisa diubah"
                        data-placement="top" role="tooltip" :value="old('role', $user->getRoleNames()->first())" readonly />
                </div>

                @php
                    $perangkatDaerah = $user->perangkatDaerah;
                    if ($user->perangkat_daerah_id == -1 || !$perangkatDaerah) {
                        $resultPerangkatDaerah = 'Semua Dinas';
                    } else {
                        $resultPerangkatDaerah = $perangkatDaerah->nama;
                    }

                    $bidang = $user->penyediaLayanan;
                    if ($user->perangkat_daerah_id == -1 || !$bidang) {
                        $resultBidang = 'Semua Bidang';
                    } else {
                        $resultBidang = $bidang->nama_bidang;
                    }

                    $hasBidang = $user->perangkat_daerah_id == -1 || $bidang;
                @endphp

                <div>
                    <x-input-label for="perangkat_daerah" :value="__('Perangkat Daerah')" />
                    <x-text-input id="perangkat_daerah" type="text" class="mt-1 block w-full"
                        x-tooltip="Tidak bisa diubah" data-placement="top" role="tooltip" :value="old('perangkat_daerah', $resultPerangkatDaerah)" readonly />
                </div>

                @if ($hasBidang)
                    <div>
                        <x-input-label for="bidang" :value="__('Bidang')" />
                        <x-text-input id="bidang" type="text" class="mt-1 block w-full"
                            x-tooltip="Tidak bisa diubah" data-placement="top" role="tooltip" :value="old('bidang', $resultBidang)"
                            readonly />
                    </div>
                @endif
                <div class="sm:col-span-2 mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <div class="flex items-center gap-4">

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
