@props(['permintaan'])

@php
    $steps = [
        'Dalam Antrian' => 'Dalam Antrian',
        'verifikasi' => 'Verifikasi',
        'proses' => 'Proses',
        'selesai' => 'Selesai',
    ];

    if ($permintaan === 'ditolak') {
        $steps['ditolak'] = 'Ditolak';
    }

    $keys = array_keys($steps);
    $currIndex = array_search($permintaan, $keys);
@endphp

<style>
    .step-arrow {
        position: relative;
    }

    .step-arrow::before {
        content: "";
        position: absolute;
        right: -15px;
        top: 0;
        bottom: 0;
        margin: auto;
        width: 0;
        height: 0;
        border-top: 18px solid transparent;
        border-bottom: 18px solid transparent;
        z-index: 2;
    }

    .step-arrow::after {
        content: "";
        position: absolute;
        right: -17px;
        top: 0;
        bottom: 0;
        margin: auto;
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        border-left: 17px solid white;
        z-index: 1;
    }

    .step-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-size: 14px;
        font-weight: bold;
    }

    @media (max-width: 640px) {
        .step-arrow {
            padding: 6px 8px !important;
            font-size: 12px;
        }

        .step-icon {
            width: 18px;
            height: 18px;
            font-size: 11px;
            margin-right: 4px;
        }

        .step-arrow::before {
            border-top-width: 14px;
            border-bottom-width: 14px;
            right: -12px;
            border-left-width: 12px;
        }

        .step-arrow::after {
            border-top-width: 16px;
            border-bottom-width: 16px;
            border-left-width: 14px;
            right: -14px;
        }
    }
</style>


<ol class="flex font-semibold">
    @foreach ($steps as $key => $label)
        @php
            $index = array_search($key, $keys);

            $isPassed = $index < $currIndex;
            $isCurrent = $index === $currIndex;

            /* BG UTAMA SEKARANG GREEN */
            $bg = $isPassed || $isCurrent ? 'bg-[#00AB55] text-white' : 'bg-[#ebedf2] text-[#9a9acb]';

            /* SEGITIGA UTAMA GREEN */
            $segitiga = $isPassed || $isCurrent ? '#00AB55' : '#ebedf2';

            // IKON
            if ($isPassed) {
                $iconBg = '#4ade80'; // hijau terang
                $iconColor = 'white';
                $icon = '✓';
            } elseif ($isCurrent) {
                $iconBg = '#bbf7d0'; // hijau muda
                $iconColor = '#166534';
                $icon = '✓';
            } else {
                $iconBg = '#e5e7eb'; // abu
                $iconColor = '#9ca3af';
                $icon = '';
            }
        @endphp

        <li class="{{ $loop->first ? 'rounded-tl-md rounded-bl-md' : '' }}">
            <a href="javascript:;"
                class="step-arrow p-2 flex items-center relative {{ $bg }}
                    {{ $loop->first ? 'ltr:pl-3 rtl:pr-3 ltr:pr-2 rtl:pl-2' : 'px-3 ltr:pl-6 rtl:pr-6' }}"
                style="--segitiga-color: {{ $segitiga }}">

                <div class="step-icon" style="background: {{ $iconBg }}; color: {{ $iconColor }};">
                    {{ $icon }}
                </div>

                {{ $label }}
            </a>

            <style>
                a.step-arrow:nth-of-type({{ $loop->iteration }})::before {
                    border-left: 15.5px solid var(--segitiga-color);
                }
            </style>
        </li>
    @endforeach
</ol>
