<x-dashboard.basecomponent>

    <x-dashboard.cardheader title="Dance">
        <div class="row">
            <div class="col">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#create_modal">
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
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
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
        route="registration">

    </x-dashboard.paginationcomponent>


    <x-dashboard.createmodal create_route="register" modal_size="modal-lg">
       

    </x-dashboard.createmodal>



    {{-- EDIT MODAL --}}


    <x-dashboard.modalform route="edit_user" modal_size="modal-lg" id="edit_modal" title="Edit Student Information">
        <div class="row g-3">
            <input type="hidden" name="id" id="id">
           


        </div>

    </x-dashboard.modalform>





    <script>
        document.addEventListener('DOMContentLoaded', function () {

            


        });


    </script>
</x-dashboard.basecomponent>