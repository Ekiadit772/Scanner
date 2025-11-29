{{-- NOT USED --}}

{{-- <x-app-layout>
    <x-alert />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atur Permission untuk Role: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST"
        class="mt-6 bg-white p-6 rounded-lg shadow">
        @csrf

        @php
            // Kelompokkan permission berdasarkan kata terakhir (misal 'pengguna' atau 'role')
            $groupedPermissions = [];
            foreach ($permissions as $permission) {
                // Ambil modul dari nama permission (kata terakhir)
                $parts = explode(' ', $permission->name);
                $modul = ucfirst(end($parts));
                $groupedPermissions[$modul][] = $permission->name;
            }
        @endphp

        <div class="space-y-6">
            @foreach ($groupedPermissions as $modul => $items)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="font-semibold text-lg mb-3 border-b pb-2 text-gray-700">
                        {{ __('Manajemen ') . $modul }}
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @foreach ($items as $name)
                            <label class="flex items-center space-x-2 p-2 bg-white border rounded hover:bg-blue-50">
                                <input type="checkbox" name="permissions[]" value="{{ $name }}"
                                    {{ in_array($name, $rolePermissions) ? 'checked' : '' }}
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-800 capitalize">{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('roles.index') }}"
                class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-app-layout> --}}
