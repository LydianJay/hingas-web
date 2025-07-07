

@props(['create_route', 'modal_size' => ''])
<div class="modal fade" id="create_modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{$modal_size}}">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Create</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($create_route) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>