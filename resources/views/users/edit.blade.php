<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <!-- form controls -->
    <div class="panel lg:row-span-3">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Form controls</h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5" method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">Name</label>
                    <x-text-input id="name" class="block mt-1 w-full" type="name" name="name"
                        :value="old('name', $user->name)" required autocomplete="username" placeholder="Enter name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <label for="name">Email</label>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email', $user->email)" required autocomplete="username" placeholder="Enter Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <label for="name">Username</label>
                    <x-text-input id="username" class="block mt-1 w-full" type="username" name="username"
                        :value="old('username', $user->username)" required autocomplete="username" placeholder="Enter username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>
                <div>
                    <label for="name">Phone</label>
                    <x-text-input id="phone" class="block mt-1 w-full" type="phone" name="phone"
                        :value="old('phone', $user->phone)" required autocomplete="username" placeholder="Enter phone" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
                <div>
                    <label for="role">Role</label>
                    <select id="role" name="role"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="" disabled selected>Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ $userRole == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>
                {{-- <div>
                    <label for="name">Password</label>
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                        :value="old('password')" required autocomplete="username" placeholder="Enter password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div> --}}
                <div class="flex justify-between">
                    <a href="{{ route('users.index') }}"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">kembali</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
