<x-dashboard.basecomponent>

    <x-dashboard.cardheader title="Enrollees">
        <div class="row">
            <div class="col">
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#create_modal">
                    Add Student
                </button>
            </div>
            <div class="col">
                <x-dashboard.cardsearchbar search_route="registration" placeholder="Juan Dela Cruz">
                
                </x-dashboard.cardsearchbar>
            </div>
        </div>
        


    </x-dashboard.cardheader>
    <div class="card-body">
        
        
        <div class="px-2 mt-3">
            <div class="row">
                @for($i = 0; $i < count($users); $i += 2)
                    @php
                        $p1 = $users[$i];
                        $p2 = $users[$i + 1] ?? null;
                    @endphp

                    {{-- First Card --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm" 
                            role="button" 
                            data-id="{{$p1->id}}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#enroll_modal"
                            data-fname="{{$p1->fname}}"
                            data-lname="{{$p1->lname}}"
                            data-mname="{{$p1->mname}}"
                            data-photo="{{$p1->photo ? asset('storage/' . $p1->photo) : asset('default-profile.png') }}"
                            data-enroll="{{ $p1->d_name ?? 'None' }}"
                            data-rfid="{{$p1->rfid}}"
                        >
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ $p1->photo ? asset('storage/' . $p1->photo) : asset('default-profile.png') }}"
                                    alt="Profile" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $p1->fname }} {{ $p1->mname }} {{ $p1->lname }}</h6>
                                    <p class="mb-0 text-muted">RFID: {{ $p1->rfid ?? 'N/A' }}</p>
                                    <p class="mb-0 text-muted">Email: {{ $p1->email }}</p>
                                </div>
                                <div class="ms-2 text-end">
                                    @if ($p1->e_id)
                                        <span class="badge bg-success mb-1">{{ucfirst($p1->d_name)}}</span><br>
                                    @endif
                                    <a href="" class="text-primary me-2 text-decoration-none"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#edit_modal"
                                        data-id="{{$p1->id}}"
                                        data-dob="{{$p1->dob}}"
                                        data-rfid="{{$p1->rfid}}"
                                        data-email="{{$p1->email}}"
                                        data-fname="{{$p1->fname}}"
                                        data-lname="{{$p1->lname}}"
                                        data-mname="{{$p1->mname}}"
                                        data-gender="{{$p1->gender}}"
                                        data-contactno="{{$p1->contactno}}"
                                        data-address="{{$p1->address}}"
                                        data-e_contact="{{$p1->e_contact}}"
                                        data-e_contact_no="{{$p1->e_contact_no}}"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a data-id="{{ $p1->id }}" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Second Card (if exists) --}}
                    @if ($p2)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm"
                                role="button" 
                                data-id="{{$p2->id}}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#enroll_modal"
                                data-fname="{{$p2->fname}}"
                                data-lname="{{$p2->lname}}"
                                data-mname="{{$p2->mname}}"
                                data-photo="{{$p2->photo ? asset('storage/' . $p2->photo) : asset('default-profile.png') }}"
                                data-enroll="{{$p2->d_name ?? 'None'}}"
                                data-rfid="{{$p2->rfid}}"
                            >
                                <div class="card-body d-flex align-items-center">
                                    <img src="{{ $p2->photo ? asset('storage/' . $p2->photo) : asset('default-profile.png') }}"
                                        alt="Profile" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $p2->fname }} {{ $p2->mname }} {{ $p2->lname }}</h6>
                                        <p class="mb-0 text-muted">RFID: {{ $p2->rfid ?? 'N/A' }}</p>
                                        <p class="mb-0 text-muted">Email: {{ $p2->email }}</p>
                                    </div>
                                    <div class="ms-2 text-end">
                                        @if ($p2->e_id)
                                            <span class="badge bg-success mb-1">{{ucfirst($p2->d_name)}}</span><br>
                                        @endif
                                        <a class="text-primary me-2 text-decoration-none" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit_modal"
                                            data-id="{{$p2->id}}"
                                            data-dob="{{$p2->dob}}"
                                            data-rfid="{{$p2->rfid}}"
                                            data-email="{{$p2->email}}"
                                            data-fname="{{$p2->fname}}"
                                            data-lname="{{$p2->lname}}"
                                            data-mname="{{$p2->mname}}"
                                            data-gender="{{$p2->gender}}"
                                            data-contactno="{{$p2->contactno}}"
                                            data-address="{{$p2->address}}"
                                            data-e_contact="{{$p2->e_contact}}"
                                            data-e_contact_no="{{$p2->e_contact_no}}"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a data-id="{{ $p2->id }}" data-bs-toggle="modal" data-bs-target="#confirm-delete"
                                          class="text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        
        </div>

    </div>
    <x-dashboard.paginationcomponent 
        search="{{$search}}"
        page="{{$page}}"
        totalPages="{{$totalPages}}"
        route="registration"
    >

    </x-dashboard.paginationcomponent>
    
    <x-dashboard.createmodal create_route="register" modal_size="modal-lg">
        <div class="row g-3">
        
            <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" required
                value="{{ old('fname') }}">
                @error('fname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="mname" class="form-control"
                value="{{ old('mname') }}">
                @error('mname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        
            <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" required
                value="{{ old('lname') }}">
                @error('lname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required
                value="{{ old('email') }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required value="{{ old('gender') }}">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other" selected>Other</option>
                </select>
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" required
                value="{{ old('dob') }}">
                @error('dob')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contactno" class="form-control" value="{{ old('contactno') }}">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Emergency Contact Person</label>
                <input type="text" name="e_contact" class="form-control" value="{{ old('e_contact') }}">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Emergency Contact No.</label>
                <input type="text" name="e_contact_no" class="form-control" value="{{ old('e_contact_no') }}">
            </div>
        
            
            <div class="col-md-6">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control" >
            </div>

           

            <div class="col-md-6">
                <label class="form-label">RFID</label>
                <input type="text" name="rfid" class="form-control" value="{{ old('rfid') }}">
            </div>

            
        </div>


    </x-dashboard.createmodal>



    {{-- EDIT MODAL --}}


    <x-dashboard.modalform route="edit_user" modal_size="modal-lg" id="edit_modal" title="Edit Student Information">
        <div class="row g-3">
            <input type="hidden" name="id" id="id">
            <div class="col-md-4">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" name="fname" id="fname" class="form-control" required
                    value="{{ old('fname') }}">
                @error('fname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-4">
                <label for="mname" class="form-label">Middle Name</label>
                <input type="text" name="mname" id="mname" class="form-control"
                    value="{{ old('mname') }}">
                @error('mname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-4">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" name="lname" id="lname" class="form-control" required
                    value="{{ old('lname') }}">
                @error('lname')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required
                    value="{{ old('email') }}">
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" name="dob" id="dob" class="form-control" required
                    value="{{ old('dob') }}">
                @error('dob')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            
            <div class="col-md-6">
                <label for="contactno" class="form-label">Contact Number</label>
                <input type="text" name="contactno" id="contactno" class="form-control" value="{{ old('contactno') }}">
            </div>
            
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
            </div>
            
            <div class="col-md-6">
                <label for="e_contact" class="form-label">Emergency Contact Person</label>
                <input type="text" name="e_contact" id="e_contact" class="form-control" value="{{ old('e_contact') }}">
            </div>
            
            <div class="col-md-6">
                <label for="e_contact_no" class="form-label">Emergency Contact No.</label>
                <input type="text" name="e_contact_no" id="e_contact_no" class="form-control" value="{{ old('e_contact_no') }}">
            </div>
            
            <div class="col-md-6">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" name="photo" id="photo" class="form-control">
            </div>
            
            <div class="col-md-6">
                <label for="rfid" class="form-label">RFID</label>
                <input type="text" name="rfid" id="rfid" class="form-control" value="{{ old('rfid') }}">
            </div>
            
            
        </div>

    </x-dashboard.modalform>




    <x-dashboard.modalform 
        route="enroll" 
        modal_size="modal-lg" 
        id="enroll_modal" 
        title="Enrollee Information"
        btn_text="Enroll"
        btn_id="modal-form-btn"
    >

        <div class="card shadow-lg border-0 rounded-3 p-3" style="max-width: 600px; margin:auto; background:#f8f9fa;">
            <div class="row g-3 align-items-center">
                
                <!-- Photo Left -->
                <div class="col-md-4 text-center">
                    <img id="e-photo" src="" alt="Student Photo" 
                        class="img-thumbnail border border-3 border-primary rounded"
                        style="width:150px; height:180px; object-fit:cover;">
                </div>

                <!-- Details Right -->
                <div class="col-md-8">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div class="mx-1">
                            <h5 class="fw-bold mb-1">
                        
                                <span id="e-fname"></span>
                                <span id="e-mname"></span>
                                <span id="e-lname"></span>
                            </h5>
                        
                        </div>
                        
                        <button type="button" class="btn-sm btn btn-primary"
                        id="collect-fee-btn" 
                        data-bs-toggle="modal" 
                        data-bs-target="#collect-fee">
                        Collect Fee
                    </button>

                    </div>
                        
                    

                    <div class="mb-1">
                        <span class="fw-semibold">RFID:</span>
                        <span id="e-rfid"></span>
                    </div>

                    <hr>

                    <p class="small text-secondary mb-0">HINGAS Life Style Studio</p>
                    <p class="small text-secondary mb-0">Client Information</p>
                    <span class="badge bg-success mt-1" id="e-enroll">Enrolled</span>
                    <p class="small text-danger mb-0">Amount balance: ₱<span id="balance">0</span> </p>
                </div>
            </div>
            <div class="row my-2" id="enrollment-form">
                <label for="" class="form-label mt-2 mb-0">Dance</label>
                <input type="text" name="id" hidden id="e-id" value="">
                <div class="input-group mb-2">
                    <select name="dance" class="form-control">
                        @foreach ($dances as $dance)
                            <option value="{{$dance->id}}">{{$dance->name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="" class="form-label mt-2 mb-0">Initial Payment</label>

                <div class="input-group mb-2">
                    <input type="number" class="form-control" step="0.2" name="payment" value="0">
                </div>
                
            </div>


            {{-- Add an enrollment inputs --}}
            {{-- Dance Class Select --}}

            
        </div>
    
    
    </x-dashboard.modalform>



    {{-- Collect Fee --}}

    <x-dashboard.modalform route="collect_fee" modal_size="modal-lg" id="collect-fee" title="Collect Fee"
        btn_text="Collect" btn_id="collect-modal-form-btn">
    
        <div class="card shadow-lg border-0 rounded-3 p-3" style="max-width: 600px; margin:auto; background:#f8f9fa;">
            <div class="row g-3 align-items-center">
    
                <!-- Photo Left -->
                <div class="col-md-4 text-center">
                    <img id="collect-photo" src="" alt="Student Photo"
                        class="img-thumbnail border border-3 border-primary rounded"
                        style="width:150px; height:180px; object-fit:cover;">
                </div>
    
                <!-- Details Right -->
                <div class="col-md-8">
                    <h5 class="modal-title">Collections</h5>
                    <input type="hidden" name="id" id="collect-payment-id">
                    <label for="" class="form-label mt-2 mb-0">Payment</label>
                    <div class="input-group mb-2">
                        <input type="number" class="form-control" step="0.2" name="amount" value="0" id="collect-payment-input">
                    </div>
                </div>
            </div>
          
        </div>
    
    
    </x-dashboard.modalform>




    <x-dashboard.modalform 
        route="delete_user" 
        modal_size="modal-lg" 
        id="confirm-delete" 
        title="Remove User" 
        btn_text="Confirm"
        btn_id="modal-delete-btn"
    >
        <input type="hidden" name="id" id="delete-id">

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
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('edit_modal').addEventListener('show.bs.modal', function (e){
                let btn         = e.relatedTarget;
                let attributes  = [
                                    'rfid',
                                    'email',
                                    'fname',
                                    'lname',
                                    'mname',
                                    'gender',
                                    'dob',
                                    'contactno',
                                    'address',
                                    'e_contact',
                                    'e_contact_no',
                                    'id',
                                ]; 

                attributes.forEach(e => {
                    document.getElementById(e).value = btn.getAttribute('data-' + e);

                });


                
            });
            

            document.getElementById('enroll_modal').addEventListener('show.bs.modal', function (e){
                let btn         = e.relatedTarget;
                let attributes  = [
                                    'rfid',
                                    'fname',
                                    'lname',
                                    'mname',
                                    'id',
                                    'photo',
                                    'enroll',
                                ]; 

                attributes.forEach(attr => {
                    let el = document.getElementById('e-' + attr);

                    if(attr === 'photo') {
                        el.src = btn.getAttribute('data-' + attr); 
                        document.getElementById('collect-photo').src = btn.getAttribute('data-' + attr);
                    } 
                    else if (attr === 'id') {
                        el.value = btn.getAttribute('data-' + attr);
                        document.getElementById('collect-payment-id').value = btn.getAttribute('data-' + attr);
                        // console.log(el.value, btn.getAttribute('data-' + attr));
                    }
                    else if(attr == 'enroll'){
                        el.innerText = btn.getAttribute('data-' + attr);
                    }
                    else {
                        el.innerText = btn.getAttribute('data-' + attr) || '—';
                    }
                });

                document.getElementById('collect-fee-btn').disabled = true;

                let route = "{{ route('get_enrollment_details') }}?id=" + btn.getAttribute('data-id');
                fetch(route, {
                    method: 'get',
                })
                .then(response => response.json())
                .then(data => {
                    let submit_btn = document.getElementById('modal-form-btn');
                    console.log(data);
                    console.log(data.balance > 0);

                    if(data.enrollment != null) {
                        submit_btn.disabled = true;
                        document.getElementById('enrollment-form').classList.add('d-none');
                        document.getElementById('balance').innerText = data.balance;
                        let payment_input = document.getElementById('collect-payment-input');

                        if(data.balance > 0) {
                            document.getElementById('collect-fee-btn').disabled = false;
                            payment_input.max = data.balance;
                            payment_input.min = 0;
                            payment_input.value = data.balance;
                        }
                        
                    } else {
                        document.getElementById('enrollment-form').classList.remove('d-none');
                        
                        submit_btn.disabled = false;
                    }
                    // console.log(data.balance > 0);
                    // if (data.balance > 0) {
                    //     document.getElementById('balance').innerText        = data.balance;
                    //     document.getElementById('collect-fee-btn').disabled = false;
                    //     payment_input.max = data.balance;
                    //     payment_input.min = 0;
                    //     payment_input.value = data.balance;
                    // }
                })
                .catch(error => {
                    console.error(error.message);
                });
                
            });
            


            document.getElementById('confirm-delete').addEventListener('show.bs.modal', function (e){
                let btn = e.relatedTarget;
                document.getElementById('delete-id').value = btn.getAttribute('data-id');
            });

        });


    </script>
</x-dashboard.basecomponent>