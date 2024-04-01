@props(['name' => "", "customer"])

<div wire:ignore x-data="select2(@entangle($attributes->wire('model')).live)">
    <select name="{{ $name }}" class="form-select p-2" style="width: 100%" x-ref="mySelect2" x-model='select2' {{ $attributes->get('inputAttributes') }}>
        <option value="">Pilih</option>
        <option value="primary">Satu</option>
        <option value="secondary">Dua</option>
    </select>
</div>

@once
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('select2', (model) => ({
            select2: model,
            init(){
                let alpine = this;
                let ownSelect = this.$refs.mySelect2;
                $(ownSelect).val(model.initialValue).trigger("change");
                
                $(ownSelect).select2({
                    placeholder: 'Select an option',
                    dropdownParent: $(ownSelect).parents(".modal").length ? $(ownSelect).parents(".modal") : false,
                    theme: 'bootstrap-5',
                    allowClear: true
                }).on("select2:unselecting", function(e){
                    $(ownSelect).data("state", "unselected");
                }).on("select2:open", function(e){
                    if($(ownSelect).data("state") === "unselected"){
                        $(ownSelect).removeData("state");
                        $(ownSelect).select2("close");
                    }
                });
                
                $(ownSelect).on("select2:select select2:clear",  function (e) {
                    e.preventDefault();
                    alpine.select2 = this.value;
                });
            },
        }));
    });
</script>
@endonce