<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Reservations">
            <div class="row mt-3 align-items-end pb-2 border-bottom">
                <div class="col">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#create-modal">
                        Add Reservation
                    </button>
                </div>
                <div class="col-6">

                    <x-dashboard.cardsearchbar search_route="rooms" placeholder="Room name">

                    </x-dashboard.cardsearchbar>
                    <form action="{{route('reservations')}}" method="GET">
                        <div class="d-flex flex-row align-items-center justify-content-evenly mt-3 mb-2">
                            <input type="date" name="date" value="{{ request()->date }}" class="form-control">
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-evenly my-1">
                            <input type="time" name="start" class="form-control" value="{{ request()->start }}"> <span class="mx-3 fw-bold fs-5">-</span> <input type="time"
                                name="end" class="form-control" value="{{ request()->end }}">
                        </div>
                        <div class="d-flex flex-row align-items-center justify-content-start my-1 mt-2">
                            <button class="btn btn-sm btn-outline-primary mx-1">Filter</button>
                            <a href="{{route('reservations')}}" class="btn btn-sm btn-outline-info mx-1">Clear Filter</a>
                        </div>
                    </form>
                    
                </div>
            </div>
        </x-dashboard.cardheader>

        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Name</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($res as $r)
                    <tr class="text-center">
                        <td>{{$r->reservee}}</td>
                        <th>{{$r->name}}</th>
                        <td>{{date('M d, Y', strtotime($r->date))}}</td>
                        <td>{{date("h:i:s A", strtotime($r->time))}}</td>
                        <td>{{$r->hours}}</td>
                        <td>
                            <div class="d-flex flex-row justify-content-center">
                                <button 
                                    class="btn btn-sm btn-outline-danger mx-1" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirm-delete" 
                                    data-id="{{$r->id}}"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button 
                                    class="btn btn-sm btn-outline-info mx-1" 
                                    data-id="{{$r->id}}"
                                    data-room_id="{{$r->room_id}}"
                                    data-reservee="{{$r->reservee}}" 
                                    data-hours="{{$r->hours}}" 
                                    data-date="{{$r->date}}"
                                    data-time="{{$r->time}}"
                                    data-address="{{$r->address}}"
                                    data-contactno="{{$r->contactno}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit-modal">
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
                <a href="{{ route('reservations', array_merge(request()->query(), ['page' => $page - 1])) }}"
                    class="page-link">Prev</a>
            </li>
    
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('reservations', array_merge(request()->query(), ['page' => $i])) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </li>
    
            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route('reservations', array_merge(request()->query(), ['page' => $page + 1])) }}"
                    class="page-link">Next</a>
            </li>
        </ul>
    </div>





    <x-dashboard.modalform route="reserve_room" modal_size="modal-lg" id="create-modal" title="Reserve Room"
        btn_text="Reserve"
        btn_id="create-btn">

        <div class="card card-body">

            <p class="fs-6 mb-0"></p>
            <select name="room_id" id="room-select" class="form-select mb-2" required>
                <option value="">Select Room</option>
                @foreach ($rooms as $r)
                    <option value="{{$r->id}}">{{$r->name}} - ₱{{number_format($r->rate, 2)}}</option>                
                @endforeach
            </select>


            <p class="fs-6 mb-0">Name</p>
            <input type="text" name="reservee" class="form-control mb-2" required>

            <p class="fs-6 mb-0">Address</p>
            <input type="text" name="address" class="form-control mb-2" required>

            <p class="fs-6 mb-0">Contact No.</p>
            <input type="text" name="contactno" class="form-control mb-2" required>

           

            <p class="fs-6 mb-0">Date</p>
            <input type="date" name="date" id="date" class="form-control mb-1" value="{{ old('date', now()->format('Y-m-d')) }}" required>

            <p class="fs-6 mb-0">Start Time</p>
            <input type="time" name="time" class="form-control mb-1" id="time" value="{{ old('time') }}" required>

            <p class="fs-6 mb-0">Hours</p>
            <input type="number" name="hours" class="form-control" id="hours" value="1" required>


            <p class="mb-0 mt-5 text-muted">Active Reservations on this selected date</p>
            <div class="table-responsive my-2">
                <table class="table table-sm table-light table-striped">
                    <thead>
                        <tr class="text-center">    
                            <th>Start</th>
                            <th>End</th>
                            <th>Hours</th>
                        </tr>
                    </thead>
                    <tbody id="res-table">
                    </tbody>
                </table>
            </div>


        </div>




    </x-dashboard.modalform>


    <x-dashboard.modalform btn_id="btn-edit" route="edit_reserved_room" modal_size="modal-lg" id="edit-modal" title="Edit Room" btn_text="Edit">

        <div class="card card-body">
        
            <p class="fs-6 mb-0"></p>
            <select name="room_id" id="e-room_id" class="form-select mb-2" required>
                <option value="">Select Room</option>
                @foreach ($rooms as $r)
                    <option value="{{$r->id}}">{{$r->name}} - ₱{{number_format($r->rate, 2)}}</option>
                @endforeach
            </select>
            <input type="hidden" name="id" id="e-id">
            <p class="fs-6 mb-0">Name</p>
            <input type="text" name="reservee" id="e-reservee" class="form-control mb-2" required>
        
            <p class="fs-6 mb-0">Address</p>
            <input type="text" name="address" id="e-address" class="form-control mb-2" required>
        
            <p class="fs-6 mb-0">Contact No.</p>
            <input type="text" name="contactno" id="e-contactno" class="form-control mb-2" required>
        
        
        
            <p class="fs-6 mb-0">Date</p>
            <input type="date" name="date" id="e-date" class="form-control mb-1" value="{{ old('date', now()->format('Y-m-d')) }}"
                required>
        
            <p class="fs-6 mb-0">Start Time</p>
            <input type="time" name="time" class="form-control mb-1" id="e-time" value="{{ old('time') }}" required>
        
            <p class="fs-6 mb-0">Hours</p>
            <input type="number" name="hours" class="form-control" id="e-hours" value="1" required>
        
        
            {{-- <p class="mb-0 mt-5 text-muted">Active Reservations on this selected date</p>
            <div class="table-responsive my-2">
                <table class="table table-sm table-light table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Start</th>
                            <th>End</th>
                            <th>Hours</th>
                        </tr>
                    </thead>
                    <tbody id="e-res-table">
                    </tbody>
                </table>
            </div> --}}
        
        
        </div>



    </x-dashboard.modalform>






    <x-dashboard.modalform route="delete_room" modal_size="modal-lg" id="confirm-delete" title="Remove Room"
        btn_text="Confirm"
        btn_id="btn-delete">
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
                Please confirm that you fully understand the impact of this action. Proceed only if you are absolutely
                sure.
            </p>
        </div>


    </x-dashboard.modalform>


    <script>

        document.addEventListener('DOMContentLoaded', function () {


            document.getElementById('edit-modal').addEventListener('show.bs.modal', function (e) {
                let attr = e.relatedTarget;
                let attrName = [
                    'id',
                    'room_id',
                    'reservee',
                    'hours',
                    'date',
                    'time',
                    'address',
                    'contactno',
                ];


                attrName.forEach(e => {
                    document.getElementById('e-' + e).value = attr.getAttribute('data-' + e);
                    
                });
                getReservations();
                
            });

            document.getElementById('confirm-delete').addEventListener('show.bs.modal', function (e) {
                let attr = e.relatedTarget;
                document.getElementById('d-id').value = attr.getAttribute('data-id');
            });


            
            let timeInput   = document.getElementById('time');
            let dateInput   = document.getElementById('date');
            let roomSelect  = document.getElementById('room-select');


            
            dateInput.addEventListener('input', function () {
                getReservations();
            });
            roomSelect.addEventListener('input', function(){
                console.log('Select Room');
                getReservations();
            });


            function to12Hour(date, h) {
                let hours       = date.getHours();
                let minutes     = date.getMinutes();
                let ampm        = hours >= 12 ? "PM" : "AM";

                hours           = (hours + h) % 12;
                hours           = hours ? hours : 12;
                minutes         = minutes.toString().padStart(2, "0");

                return `${hours}:${minutes} ${ampm}`;
            }


            function getReservations() {
                let date    = dateInput.value;
                let room    = roomSelect.value;
                let route   = `{{ route('get_reservations') }}?date=${date}&room=${room}`;

                fetch(route) 
                .then(response => response.json()) 
                .then(data => {
                    let table   = document.getElementById('res-table');
                    // let table2  = document.getElementById('e-res-table');
                    console.log(data, route);
                    table.innerHTML = "";
                    // table2.innerHTML = "";
                    data.forEach(e => {
                        let row     = document.createElement('tr');
                        let start   = new Date(e.date + " " + e.time);
                        row.innerHTML = 
                        `
                            <td>${to12Hour(start, 0)}</td>
                            <td>${to12Hour(start, e.hours)}</td>
                            <td>${e.hours.toString()}</td>
                        `;
                        row.classList.add('text-center');
                        table.appendChild(row);
                        // let rowClone = row.cloneNode(true);
                        // table2.appendChild(rowClone);
                    }); 
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            }
            

        });


    </script>


</x-dashboard.basecomponent>