@props(['active' => false, 'trigger', 'menu'])

@php
    $classes = $active
        ? 'relative inline-flex items-center px-1 pt-1 border-b-4 border-white text-sm font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'relative inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-white hover:text-gray-200 hover:border-white focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<div x-data="{ open: false }"
class="{{ $classes }}">

    <!-- Trigger -->
    <button @click="open = !open" class="inline-flex items-center">
        {{ $trigger }}
        <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute left-0 mt-60 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
        <div class="py-1">
            {{ $menu }}
        </div>
    </div>
</div>
