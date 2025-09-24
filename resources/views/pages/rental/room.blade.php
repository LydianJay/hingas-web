<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Room">
            <div class="row mt-3 align-items-center pb-2 border-bottom">
                <div class="col">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#create-modal">
                        Add Room
                    </button>
                </div>
                <div class="col-6">

                    <x-dashboard.cardsearchbar search_route="rooms" placeholder="Room name">
                    
                    </x-dashboard.cardsearchbar>
                </div>
            </div>
        </x-dashboard.cardheader>

        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Room</th>
                    <th>Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $r)
                    <tr class="text-center">
                        <td>{{$r->name}}</td>
                        <td>{{$r->rate}}</td>
                        <td>
                            <div class="d-flex flex-row justify-content-center">
                                <button class="btn btn-sm btn-outline-danger mx-1"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirm-delete"
                                    data-id="{{$r->id}}"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info mx-1"
                                    data-id="{{$r->id}}"
                                    data-name="{{$r->name}}"
                                    data-rate="{{$r->rate}}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#edit-modal"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">

        <ul class="pagination justify-content-between">
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a href="{{ route('rooms') }}?page={{ $page - 1 }}" class="page-link">Prev</a>
            </li>
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('rooms') }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </li>

            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route('rooms') }}?page={{ $page + 1 }}" class="page-link">Next</a>
            </li>
        </ul>
    </div>




    <x-dashboard.modalform 
        route="create_room" 
        modal_size="modal-sm" 
        id="create-modal" 
        title="Create Room"
        btn_text="Create"
    >

        <div class="card card-body">

            <p class="fs-6 mb-0">Room Name</p>
            <input type="text" name="name" class="form-control mb-2">

            <p class="fs-6 mb-0">Hourly rate</p>
            <input type="number" step="0.01" name="rate" class="form-control">
        </div>
    



    </x-dashboard.modalform>


    <x-dashboard.modalform route="edit_room" modal_size="modal-sm" id="edit-modal" title="Edit Room"
        btn_text="Create">
    
        <div class="card card-body">
            <input type="hidden" name="id" id="e-id">
            <p class="fs-6 mb-0">Room Name</p>
            <input type="text" name="name" class="form-control mb-2" id="e-name">
    
            <p class="fs-6 mb-0">Hourly rate</p>
            <input type="number" step="0.01" name="rate" class="form-control" id="e-rate">
        </div>
    
    
    </x-dashboard.modalform>


    <x-dashboard.modalform route="delete_room" modal_size="modal-lg" id="confirm-delete" title="Remove Room"
        btn_text="Confirm" >
        <input type="hidden" name="id" id="d-id">
    
        <div class="card-body py-5 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-triangle-exclamation text-danger fs-1"></i>
            </div>
            <h4 class="card-title mb-2 text-danger">This Action is Irreversible</h4>
            <p class="card-text text-muted">
                You are about to remove this room from this system
            </p>
            <p class="card-text text-muted">
                Please confirm that you fully understand the impact of this action. Proceed only if you are absolutely sure.
            </p>
        </div>
    
    
    </x-dashboard.modalform>


    <script>

        document.addEventListener('DOMContentLoaded', function(){


            document.getElementById('edit-modal').addEventListener('show.bs.modal', function(e){
                let attr = e.relatedTarget;

                document.getElementById('e-name').value = attr.getAttribute('data-name');
                document.getElementById('e-rate').value = attr.getAttribute('data-rate');
                document.getElementById('e-id').value = attr.getAttribute('data-id');
                
                
            });

            document.getElementById('confirm-delete').addEventListener('show.bs.modal', function (e) {
                let attr = e.relatedTarget;

               
                document.getElementById('d-id').value = attr.getAttribute('data-id');


            });

        });


    </script>


</x-dashboard.basecomponent>