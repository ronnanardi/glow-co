<label class="selectable-option d-block {{ $attributes->get('wrapperClass', 'mb-3') }}">
    <input type="radio"
           name="{{ $name }}"
           value="{{ $value }}"
           class="d-none"
           {{ $checked ? 'checked' : '' }}
           {{ $attributes->whereStartsWith('data-') }}>
    <div class="selectable-box {{ $center }}">
        {{ $slot }}
    </div>
</label>