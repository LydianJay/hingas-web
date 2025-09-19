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

        {{-- Chart.js --}}
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Overview</h5>
                <canvas id="dashboardChart" height="100"></canvas>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Monthly Fee Collection</h5>
                <canvas id="monthlyPaymentsChart" height="100"></canvas>
            </div>  
        </div>
    </div>


    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Total Enrollments', 'Total Sessions', 'Total Dances'],
                datasets: [{
                    label: 'Counts',
                    data: [
                        {{ $totalUsers }},
                        {{ $totalEnrollments }},
                        {{ $totalDanceSessions }},
                        {{ $totalDances }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(23, 162, 184, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });




        const ctx2 = document.getElementById('monthlyPaymentsChart').getContext('2d');
            const monthlyPaymentsChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($paymentMonths) !!},
                    datasets: [{
                        label: 'Total Collected',
                        data: {!! json_encode($paymentTotals) !!},
                        backgroundColor: 'rgba(23, 162, 184, 0.3)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true } },
                    scales: { y: { beginAtZero: true } }
                }
            });
    </script>

</x-dashboard.basecomponent>