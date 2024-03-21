<div wire:ignore x-data="date-picker()">
    <label for="established" class="form-label">Didirikan</label>
    <input type="text" id="established" x-ref="myDatePicker" x-model="established" class="form-control" placeholder="DD / MM / YY">
</div>

@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('datepicker', () => ({
            selectedDate = null,
            init(){
                flatpickr.localize(flatpickr.l10ns.id);
                flatpickr(this.$refs.myDatePicker, {
                    altInput: true,
                    altFormat: "d F Y",
                    dateFormat: "Y-m-d",
                });
            }
        }))
    })
</script>
@endonce