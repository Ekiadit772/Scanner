<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <!-- form controls -->
    <div class="panel lg:row-span-3">
        <div class="flex items-center justify-between mb-5">
            <h5 class="font-semibold text-lg dark:text-white-light">Form controls</h5>
        </div>
        <div class="mb-5">
            <form class="space-y-5" method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">Name</label>
                    <x-text-input id="name" class="block mt-1 w-full" type="name" name="name"
                        :value="old('name', $role->name)" required autocomplete="username" placeholder="Enter name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <label for="level">Level</label>
                    <select id="level" name="level"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="" disabled>Pilih Level</option>
                        @for ($i = 10; $i <= 100; $i += 10)
                            <option value="{{ $i }}"
                                {{ old('level', $role->level) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('level')" class="mt-2" />
                </div>

                <button type="submit" class="btn btn-primary !mt-6">Submit</button>
            </form>
        </div>
    </div>
</x-app-layout>
