<x-dashboard.basecomponent>

    <x-dashboard.cardheader title="Dance">
        <div class="row">
            <div class="col">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#create-modal">
                    Add Dance
                </button>
            </div>
            <div class="col">
                <x-dashboard.cardsearchbar search_route="dance" placeholder="hiphop">

                </x-dashboard.cardsearchbar>
            </div>
        </div>



    </x-dashboard.cardheader>
    <div class="card-body">
        
        <div class="my-4">
            <div class="table-responsive shadow-sm rounded-3">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th class="ps-4">Dance</th>
                            <th>No. of Sessions</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dances as $dance)
                            <tr>
                                <td class="fw-semibold ps-4">{{$dance->name}}</td>
                                <td>{{$dance->session_count}}</td>
                                <td class="text-center fw-bold text-success">
                                    ₱{{number_format($dance->price, 2)}}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-id="{{$dance->id}}"
                                        data-name="{{$dance->name}}"
                                        data-amount="{{$dance->price}}"
                                        data-count="{{$dance->session_count}}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#edit-modal"
                                    >
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-id="{{$dance->id}}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#confirm-delete"
                                    >
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    <x-dashboard.paginationcomponent search="{{$search}}" page="{{$page}}" totalPages="{{$totalPages}}"
        route="dance">

    </x-dashboard.paginationcomponent>


    


    {{-- CREATE MODAL --}}


    <x-dashboard.modalform route="create_dance" modal_size="modal-lg" id="create-modal" title="Dance Creation Form" btn_text="Create">
        <div class="row g-3">
            <div class="row my-2">
                <div class="col">
                    <label for="" class="form-label mb-0">Dance Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                <div class="col">
                    <label class="form-label mb-0">No. of Session</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="session_count">
                    </div>
                </div>
                <div class="col">
                    <label class="form-label mb-0">Amount(PHP)</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="price">
                    </div>
                </div>
            </div>
        
        </div>

    </x-dashboard.modalform>



    {{-- EDIT--}}

    <x-dashboard.modalform route="edit_dance" modal_size="modal-lg" id="edit-modal" title="Edit Dance"
        btn_text="Edit">
        <input hidden name="id" id="e-id">
        <div class="row g-3">
            <div class="row my-2">
                <div class="col">
                    <label for="" class="form-label mb-0">Dance Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" id="e-name">
                    </div>
                </div>
                <div class="col">
                    <label class="form-label mb-0">No. of Session</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="session_count" id="e-count">
                    </div>
                </div>
                <div class="col">
                    <label class="form-label mb-0">Amount(PHP)</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control"  step="0.2" name="price" id="e-amount">
                    </div>
                </div>
            </div>
    
        </div>
    
    </x-dashboard.modalform>


    <x-dashboard.modalform route="delete_dance" modal_size="modal-lg" id="confirm-delete" title="Remove Dance"
        btn_text="Confirm" btn_id="modal-delete-btn">
        <input type="hidden" name="id" id="d-id">
        <div class="card-body py-5 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-triangle-exclamation text-danger fs-1"></i>
            </div>
            <h4 class="card-title mb-2 text-danger">This Action is Irreversible</h4>
            <p class="card-text text-muted">
                You are about to remove this user from this system
            </p>
            <p class="card-text text-muted">
                Please confirm that you fully understand the impact of this action. Proceed only if you are absolutely sure.
            </p>
        </div>
    
    </x-dashboard.modalform>



    <script>
        document.addEventListener('DOMContentLoaded', function () {

            
            document.getElementById('edit-modal').addEventListener('show.bs.modal', function (e) {
                let btn = e.relatedTarget;
                let attributes = [
                    'id',
                    'name',
                    'amount',
                    'count',
                ];

                attributes.forEach(e => {
                   document.getElementById('e-' + e).value = btn.getAttribute('data-' + e);
                });



            });



            document.getElementById('confirm-delete').addEventListener('show.bs.modal', function (e) {
                let btn = e.relatedTarget;
                let attributes = [
                    'id',
                ];

                attributes.forEach(e => {
                    document.getElementById('d-' + e).value = btn.getAttribute('data-' + e);
                });

            });
            

        });


    </script>
</x-dashboard.basecomponent>