<div>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        @foreach ($items as $index => $item)
            <li class="{{ $index > 0 ? "before:content-['/'] ltr:before:mr-1 rtl:before:ml-1" : '' }}">
                @if (isset($item['url']) && $index !== count($items) - 1)
                    <a href="{{ url($item['url']) }}" class="text-primary hover:underline">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
