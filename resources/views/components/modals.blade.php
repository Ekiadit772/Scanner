@props([
    'modalId',
    'titleId',
    'titleDefault',
    'titleEdit',
    'formId',
    'errorBoxId',
    'openEvent',
    'editEvent',
    'closeEvent',
    'buttonLabel',
    'detailEvent' => null,
])

<div>
    <div id="{{ $modalId }}" x-data="{
        open: false,
        isEdit: false,
        isView: false,
        toggle() { this.open = !this.open },
        openModal(edit = false, view = false) {
            this.isEdit = edit;
            this.isView = view;
            this.open = true;
    
            if (view) {
                document.getElementById('{{ $titleId }}').textContent = 'Detail Pengguna';
            } else {
                document.getElementById('{{ $titleId }}').textContent = edit ? '{{ $titleEdit }}' : '{{ $titleDefault }}';
            }
        },
        closeModal() {
            this.open = false;
            this.isEdit = false;
            this.isView = false;
    
            const form = document.getElementById('{{ $formId }}');
            if (form) form.reset();
    
            const errBox = document.getElementById('{{ $errorBoxId }}');
            if (errBox) errBox.innerHTML = '';
    
            form?.removeAttribute('data-id');
            document.getElementById('{{ $titleId }}').textContent = '{{ $titleDefault }}';
        }
    }" x-on:{{ $openEvent }}.window="openModal(false)"
        x-on:{{ $editEvent }}.window="openModal(true)" x-on:{{ $closeEvent }}.window="closeModal()"
        @if ($detailEvent) x-on:{{ $detailEvent }}.window="openModal(false, true)" @endif>


        {{-- Tombol Trigger --}}
        <div class="flex items-center justify-center">
            <button type="button" class="w-full sm:w-auto btn btn-sm btn-primary"
                @click="$dispatch('{{ $openEvent }}')">
                {{ $buttonLabel }}
            </button>
        </div>

        {{-- Background Modal --}}
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'"
            @click.self="closeModal()">
            <div class="flex items-center justify-center min-h-screen px-4" @click.self="closeModal()">
                <div x-show="open" class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg" id="{{ $titleId }}">{{ $titleDefault }}</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="closeModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    {{-- Slot Form --}}
                    <div class="p-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
