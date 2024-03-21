@props([
    'modalName','title', 'event' => ""
])

<div class="modal fade" id="{{ $modalName }}" tabindex="-1" style="display: none;" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel4">{{ $title }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                {{ $slot }}
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
          </button>
          <button @click="$dispatch('{{ $event }}')" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>