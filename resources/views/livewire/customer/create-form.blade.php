<div>
<div class="modal fade" id="CustomerCreateModal" tabindex="-1" style="display: none;" aria-hidden="true" data-bs-focus="false" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel4">Create</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-3 mb-3">
                    <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" wire:model='form.name' class="form-control @error('form.name') is-invalid @enderror" placeholder="PT. Makmur Sentosa">
                    @error('form.name')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="pic_name" class="form-label">PIC Name <span class="text-danger">*</span></label>
                    <input type="text" id="pic_name" wire:model='form.pic_name' class="form-control @error('form.pic_name') is-invalid @enderror" placeholder="Ahmad Dani">
                    @error('form.pic_name')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-xl-3 mb-3">
                    <label for="group" class="form-label">Group</label>
                    <input type="text" id="group" wire:model='form.group' class="form-control" placeholder="Ahmad Dani">
                </div>
                <div class="col-xl-3 mb-3">
                    <x-datepicker label="Didirikan" wire:model='form.established'/>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mb-3">
                    <label for="email_address" class="form-label">Email</label>
                    @foreach ($form->emails as $iEmail => $email)
                    <div class="" wire:key="{{ $iEmail }}">
                    <input type="hidden" class="form-control @error('form.emails.{{ $iEmail }}.id') is-invalid @enderror" placeholder="example@test.com" wire:model="form.emails.{{ $iEmail }}.id">
                    <div class="input-group mb-3 @error('form.emails.{{ $iEmail }}.address') is-invalid @enderror">
                        <input type="text" class="form-control @error('form.emails.{{ $iEmail }}.address') is-invalid @enderror" placeholder="example@test.com" wire:model="form.emails.{{ $iEmail }}.address">
                        <button class="btn btn-outline-{{ $iEmail ? 'danger' : 'primary' }}" type="button" wire:click='{{ $iEmail ? 'removeEmail('.$iEmail.')' : 'addEmail'  }}'><i class='bx bx-{{ $iEmail ? 'minus' : 'plus' }}'></i></button>
                    </div>
                    @error('form.emails.{{ $iEmail }}.address')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    </div>
                    @endforeach
                </div>
                <div class="col-xl-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    @foreach ($form->phones as $iPhone => $number)
                    <div class="" wire:key="{{ $iPhone }}">
                        <input type="hidden" class="form-control @error('form.phones.{{ $iPhone }}.id') is-invalid @enderror" placeholder="08128275XXXX" wire:model="form.phones.{{ $iPhone }}.id">
                    <div class="input-group mb-3 @error('form.phones.{{ $iPhone }}.number') is-invalid @enderror">
                        <input type="text" class="form-control @error('form.phones.{{ $iPhone }}.number') is-invalid @enderror" placeholder="08128275XXXX" wire:model="form.phones.{{ $iPhone }}.number">
                        <button class="btn btn-outline-{{ $iPhone ? 'danger' : 'primary' }}" type="button" id="phone_number" name="phone_number[]" wire:click='{{ $iPhone ? 'removePhone('.$iPhone.')' : 'addPhone'  }}'><i class='bx bx-{{ $iPhone ? 'minus' : 'plus' }}'></i></button>
                    </div>
                    @error('form.phones.{{ $iPhone }}.number')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    </div>
                    @endforeach
                </div>
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button wire:click="save" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>
  
  