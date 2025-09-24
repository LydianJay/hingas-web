<x-dashboard.basecomponent>
    <div class="card-body">
        <x-dashboard.cardheader title="Payment History">
            <div class="row">
                <div class="col">
                    
                </div>
                <div class="col">
                    <x-dashboard.cardsearchbar search_route="fee_collection" placeholder="Juan Dela Cruz">
            
                    </x-dashboard.cardsearchbar>
                </div>
            </div>
        </x-dashboard.cardheader>

        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>Date</th>
                    <th>Name</th>
                    <th>Dance Type</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($payments as $p)
                    <tr>
                        <td>{{ date('M d, Y', strtotime($p->date)) }}</td>
                        <td>{{ $p->fname . ' ' . $p->lname }}</td>
                        <td><span class="badge bg-info">{{$p->name}}</span></td>
                        <td>{{ number_format($p->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">

        <ul class="pagination justify-content-between">
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a href="{{ route('fee_collection') }}?page={{ $page - 1 }}" class="page-link">Prev</a>
            </li>
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('fee_collection') }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </li>

            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route('fee_collection') }}?page={{ $page + 1 }}" class="page-link">Next</a>
            </li>
        </ul>
        <p class="text-muted mb-0">Total Count: <strong>{{ $count }}</strong></p>
    </div>

</x-dashboard.basecomponent>