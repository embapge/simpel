@props(['class' => '', 'name' => '', 'id' => ''])

<div wire:ignore x-data="select2(@entangle($attributes->wire('model')).live)">
    <select style="width: 100%" x-ref="mySelect2" x-model='select2' {{ $attributes }} name="{{ $name }}"
        class="@error($name) is-invalid @enderror" id={{ $id }}>
        <option value="">Pilih</option>
        @foreach ($datas as $data)
            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
        @endforeach
    </select>
</div>
