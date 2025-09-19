<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Active Enrollments">
        </x-dashboard.cardheader>

        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Name</th>
                    <th>Dance</th>
                    <th>Session Used</th>
                    <th>Remaining Balance</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($enrollments as $p)
                    <tr>
                        <td>{{ $p->fname . ' ' . $p->lname }}</td>
                        <td><span class="badge bg-info">{{$p->name}}</span></td>
                        <td>{{ $p->ses }}</td>
                        <td class="{{$p->price - $p->paid > 0 ? 'text-danger' : 'text-success' }}">₱ {{ number_format($p->price - $p->paid, 2)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">

        <ul class="pagination justify-content-between">
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a href="{{ route('enrollment') }}?page={{ $page - 1 }}" class="page-link">Prev</a>
            </li>
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('enrollment') }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </li>

            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route('enrollment') }}?page={{ $page + 1 }}" class="page-link">Next</a>
            </li>
        </ul>
        <p class="text-muted mb-0">Total Count: <strong>{{ $count }}</strong></p>
    </div>

</x-dashboard.basecomponent>