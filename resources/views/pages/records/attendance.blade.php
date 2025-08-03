<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Attendance Records">
        </x-dashboard.cardheader>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>RFID</th>
                    <th>Dance Type</th>
                    <th>User</th>
                    <th>Date</th>

                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendance as $att)
                    <tr>
                        <td>{{ $att->rfid }}</td>
                        <td><span class="bg-success badge fw-bold fs-6">{{ $att->name }}</span></td>
                        <td>{{ $att->fname . ' ' . $att->mname . ' ' . $att->lname }}</td>
                        <td>{{ $att->date }}</td>
                        <td>{{ $att->time_in }}</td>
                        <td>{{ $att->time_out ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
    
        <ul class="pagination justify-content-between">
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a href="{{ route('attendance') }}?page={{ $page - 1 }}" class="page-link">Prev</a>
            </li>
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('attendance') }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </li>
    
            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route('attendance') }}?page={{ $page + 1 }}" class="page-link">Next</a>
            </li>
        </ul>
    </div>

</x-dashboard.basecomponent>