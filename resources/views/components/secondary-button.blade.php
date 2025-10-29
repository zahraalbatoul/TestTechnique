<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-outline uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
