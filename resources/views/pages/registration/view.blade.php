<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Enrollees">
            <div class="row">
                <div class="col">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#create_modal">
                        Enroll
                    </button>
                </div>
                <div class="col">
                    <x-dashboard.cardsearchbar search_route="registration" placeholder="Juan Dela Cruz">
                    
                    </x-dashboard.cardsearchbar>
                </div>
            </div>
            


        </x-dashboard.cardheader>
        
        <div class="table-responsive">
            <div class="container mt-3">
                <div class="row">
                    @for($i = 0; $i < count($users); $i += 2)
                        @php
                            $p1 = $users[$i];
                            $p2 = $users[$i + 1] ?? null;
                        @endphp

                        {{-- First Card --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <img src="{{ $p1->photo ? asset('storage/' . $p1->photo) : asset('default-profile.png') }}"
                                        alt="Profile" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $p1->fname }} {{ $p1->mname }} {{ $p1->lname }}</h6>
                                        <p class="mb-0 text-muted">RFID: {{ $p1->rfid ?? 'N/A' }}</p>
                                        <p class="mb-0 text-muted">Email: {{ $p1->email }}</p>
                                        @if ($p1->dance_name)
                                            <p class="mb-0 text-muted">Dance: {{ $p1->dance_name }}</p>
                                        @endif
                                    </div>
                                    <div class="ms-2 text-end">
                                        @if ($p1->enrollment_id)
                                            <span class="badge bg-success mb-1">Enrolled</span><br>
                                        @endif
                                        <a href="" class="text-primary me-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a student_id="{{ $p1->id }}" data-bs-toggle="modal" data-bs-target="#confirm_delete_modal"
                                            id="delete_btn_{{ $p1->id }}" href="#" class="text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Second Card (if exists) --}}
                        @if ($p2)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <img src="{{ $p2->photo ? asset('storage/' . $p2->photo) : asset('default-profile.png') }}"
                                            alt="Profile" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $p2->fname }} {{ $p2->mname }} {{ $p2->lname }}</h6>
                                            <p class="mb-0 text-muted">RFID: {{ $p2->rfid ?? 'N/A' }}</p>
                                            <p class="mb-0 text-muted">Email: {{ $p2->email }}</p>
                                            @if ($p2->dance_name)
                                                <p class="mb-0 text-muted">Dance: {{ $p2->dance_name }}</p>
                                            @endif
                                        </div>
                                        <div class="ms-2 text-end">
                                            @if ($p2->enrollment_id)
                                                <span class="badge bg-success mb-1">Enrolled</span><br>
                                            @endif
                                            <a class="text-primary me-2">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a student_id="{{ $p2->id }}" data-bs-toggle="modal" data-bs-target="#confirm_delete_modal"
                                                id="delete_btn_{{ $p2->id }}" href="#" class="text-danger">
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

    </div>
    
    <x-dashboard.createmodal create_route="register" modal_size="modal-lg">
        <div class="row g-3">
        
            
        
            
        
            <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" name="fname" class="form-control" required>
            </div>
        
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="mname" class="form-control">
            </div>
        
            <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" name="lname" class="form-control" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other" selected>Other</option>
                </select>
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contactno" class="form-control">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Emergency Contact Person</label>
                <input type="text" name="e_contact" class="form-control">
            </div>
        
            <div class="col-md-6">
                <label class="form-label">Emergency Contact No.</label>
                <input type="text" name="e_contact_no" class="form-control">
            </div>
        
            
        
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Password Confirm</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Dance Category</label>
                <select class="form-control" name="dance" required>
                    @foreach ($dances as $dance)
                        <option value="{{$dance->id}}">{{$dance->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">RFID</label>
                <input type="text" name="rfid" class="form-control" required>
            </div>

            
        </div>
    </x-dashboard.createmodal>
</x-dashboard.basecomponent>