<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.app_title') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo/logo-white.png') }}" type="image/x-icon">

    <!-- Bootstrap & FontAwesome -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap_custom.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.js"
        integrity="sha512-D4pL3vNgjkHR/qq+nZywuS6Hg1gwR+UzrdBW6Yg8l26revKyQHMgPq9CLJ2+HHalepS+NuGw1ayCCsGXu9JCXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css">

    <style>
        body {
            background-color: var(--bs-pastel-blue);
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--bs-skin-blue);
        }

        .sidebar .list-group-item {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
        }

        .sidebar .list-group-item:hover,
        .sidebar .list-group-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar .submenu .list-group-item {
            padding-left: 1.5rem;
            background-color: var(--bs-dark-blue);
            border-radius: 4px;
            color: white;
        }

        .dashboard-card {
            border: 1px solid var(--bs-gray-300);
            border-radius: 1rem;
            background-color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-logout {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
        }

        .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>

<body>
    <div class="container-fluid px-0">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg sticky-top shadow-sm px-3" style="background-color: var(--bs-skin-blue);">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/logo/logo-white.png') }}" alt="logo" style="width: 60px;">
                <h5 class="text-white mb-0 ms-3">{{ config('app.app_title') }}</h5>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <!-- User Dropdown -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button"
                        id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user-circle me-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fa fa-sign-out-alt me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                        <li>
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit-admin">
                                <i class="fa fa-user me-2"></i> Profile
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar Toggle (Mobile) -->
                <button class="btn btn-outline-light d-lg-none" id="sidebarToggle">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </nav>

        <div class="row g-0">
            <!-- Sidebar -->
            <aside class="col-lg-2 col-md-3 sidebar d-none d-md-block" id="sidebar">
                <ul class="list-group p-3">
                    @foreach (config('menu') as $key => $menu)
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" data-bs-target="#submenu{{ $key }}">
                            <span><i class="{{ $menu['icon'] }} me-2"></i> {{ $menu['menu'] }}</span>
                            <i class="fa fa-angle-left" id="icon-item-{{ $key }}"></i>
                        </li>
                        @if (!empty($menu['submenu']))
                            <ul class="list-group collapse submenu my-1" id="submenu{{ $key }}">
                                @foreach ($menu['submenu'] as $subKey => $item)
                                    <li class="list-group-item my-1">
                                        <a href="{{ route($item['route']) }}"
                                            class="text-decoration-none text-white">{{ $subKey }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="col px-4 py-4">
                <div class="dashboard-card">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            sidebarToggle?.addEventListener('click', function () {
                sidebar.classList.toggle('d-none');
            });

            @foreach (config('menu') as $key => $menu)
                const submenu{{ $key }} = document.getElementById("submenu{{ $key }}");
                submenu{{ $key }}?.addEventListener('hide.bs.collapse', () => {
                    document.getElementById('icon-item-{{ $key }}').classList.replace('fa-angle-down', 'fa-angle-left');
                });
                submenu{{ $key }}?.addEventListener('show.bs.collapse', () => {
                    document.getElementById('icon-item-{{ $key }}').classList.replace('fa-angle-left', 'fa-angle-down');
                });
            @endforeach
        });
    </script>

    <x-dashboard.modalform 
        route="edit_admin" 
        id="edit-admin" 
        title="Edit Admin Password"
        modal_size="modal-md"
    >

        <div class="card card-body">
            <p class="fs-5 fw bold mb-0">Password</p>
            <input type="password" name="password" class="form-control">
            <p class="fs-5 fw bold mb-0">Confirm Password</p>
            <input type="password" name="password_confirmation" class="form-control">

        </div>

    </x-dashboard.modalform>

    <!-- Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
</body>

</html>