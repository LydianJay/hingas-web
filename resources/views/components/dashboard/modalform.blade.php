@props([
    'route', 
    'modal_size'    => '', 
    'id'            => 'edit_modal', 
    'title'         => 'Modal', 
    'btn_text'      => 'Save',
    'btn_id'        => 'modal-form-btn-id-generic'
])
<div class="modal fade" id="{{$id}}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{$modal_size}}">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{$title}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($route) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="{{$btn_id}}">{{$btn_text}}</button>
                </div>
            </form>
        </div>
    </div>
</div>