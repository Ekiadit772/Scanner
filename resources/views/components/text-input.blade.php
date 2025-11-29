@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-input placeholder:text-white-dark']) }}>
