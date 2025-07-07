<x-site.basecomponent>
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-lg p-4" style="min-width: 350px; max-width: 400px; width: 100%; border-radius: 1rem;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems with your input:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="alert {{session('status')['alert']}} alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status')['msg'] }}
                </div>
            @endif
           
            <div class="text-center mb-4">
                <img src="{{ asset('assets/school_content/admin_small_logo/1.png') }}" alt="Logo" width="60">
                <h4 class="mt-2">Welcome Back</h4>
                <p class="text-muted small">Please login to your account</p>
            </div>
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                {{-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div> --}}

                <!-- Submit -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>

                
            </form>
        </div>
    </div>
</x-site.basecomponent>