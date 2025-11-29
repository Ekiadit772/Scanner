@php
    $prev = null;
@endphp

@foreach ($logs as $log)
    @php
        $durasi = null;

        if ($prev) {
            $durasi = \Carbon\Carbon::parse($log->created_at)->diffForHumans(
                \Carbon\Carbon::parse($prev->created_at),
                true,
            );
        }
    @endphp

    <div class="timeline-item grid grid-cols-[160px_30px_1fr] py-1 relative">

        <div class="flex flex-col text-[#3b3f5c] dark:text-white-light font-semibold">
            <span>{{ $log->created_at->translatedFormat('d F Y') }}</span>
            <small class="text-xs text-gray-500">
                {{ $log->created_at->format('H:i') }}
                @if ($durasi)
                    • {{ $durasi }} dari proses sebelumnya
                @endif
            </small>

        </div>

        <div class="relative">
            <div class="absolute left-1/2 -translate-x-1/2 top-[5px] w-3 h-3 rounded-full border-2 bg-white z-10"
                style="border-color: {{ $log->status->color ?? '#3b82f6' }};">
            </div>

            @if (!$loop->last)
                <div class="absolute left-1/2 -translate-x-1/2 top-[18px] w-[2px]"
                    style="height: calc(100% - 6px); background-color: {{ $log->status->color ?? '#3b82f6' }};">
                </div>
            @endif
        </div>

        <div class="pb-5">
            <p class="text-[#3b3f5c] dark:text-white-light font-semibold text-[14px] leading-tight">
                {{ $log->status->nama_status }}
            </p>

            <p class="text-white-dark text-xs font-bold leading-tight">
                {{ $log->created_by ?? 'System' }}


            </p>

            @if ($log->keterangan)
                <p class="text-gray-700 text-[12px] mt-1 max-w-[380px] leading-snug">
                    {{ $log->keterangan }}
                </p>
            @endif
        </div>

    </div>

    @php
        $prev = $log;
    @endphp
@endforeach
