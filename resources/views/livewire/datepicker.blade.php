<div>

<div wire:ignore x-data="datepicker()">
    <label for="myDatePicker" class="form-label">Didirikan</label>
    <input type="text" x-ref="myDatePicker" x-model='selectedDate' class="form-control" placeholder="DD / MM / YY" >
</div>

@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('datepicker', (model) => ({
            selectedDate: model,
            init(){
                flatpickr.localize(flatpickr.l10ns.id);
                flatpickr(this.$refs.myDatePicker, {
                    altInput: true,
                    altFormat: "d F Y",
                    dateFormat: "Y-m-d",
                });
                console.log(this.$refs.myDatePicker);
            },
        }));
    });
</script>
@endonce
</div>
