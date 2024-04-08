@props(['label' => '', 'name' => '', 'class' => ''])

<div wire:ignore x-data="datepicker(@entangle($attributes->wire('model')).live)">
    <label for="myDatePicker" class="form-label">{{ $label }}</label>
    <input type="text" x-ref="myDatePicker" x-model='selectedDate' class="form-control {{ $class }}"
        placeholder="DD / MM / YY" {{ $attributes->get('inputAttributes') }} name="{{ $name }}">
    @error('selectedDate')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
    @enderror
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datepicker', (model) => ({
                selectedDate: model,
                init() {
                    flatpickr.localize(flatpickr.l10ns.id);
                    flatpickr(this.$refs.myDatePicker, {
                        altInput: true,
                        altFormat: "d F Y",
                        dateFormat: "Y-m-d",
                    });
                },
            }));
        });
    </script>
@endonce
