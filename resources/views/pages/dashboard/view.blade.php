<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Dashboard">

        </x-dashboard.cardheader>
        
        <div class="d-flex flex-row justify-content-evenly flex-wrap">
            <div class="card shadow-sm bg-success" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $totalUsers }}</p> 
                </div>
            </div>
            <div class="card shadow-sm bg-info" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title">Total Enrollments</h5>
                    <p class="card-text">{{ $totalEnrollments }}</p>
                </div>
            </div>
            <div class="card shadow-sm bg-warning" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title">Total Sessions</h5>
                    <p class="card-text">{{ $totalDanceSessions }}</p>
                </div>
            </div>
            <div class="card shadow-sm bg-danger" style="width: 14rem;">
                <div class="card-body">
                    <h5 class="card-title">Total Dances</h5>
                    <p class="card-text">{{ $totalDances }}</p>
                </div>
            </div>
        </div>

    </div>


</x-dashboard.basecomponent>