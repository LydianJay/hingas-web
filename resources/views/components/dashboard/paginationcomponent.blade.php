@props(['search', 'page', 'totalPages', 'route'])

@if($search && $search != '')
    <div class="card-footer">
        <p class="text-muted mb-0">Search results for: <strong>{{ $search }}</strong></p>
    </div>
@else
    <div class="card-footer">

        <ul class="pagination justify-content-between">
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a href="{{ route($route, array_merge(request()->query(), ['page' => $page - 1])) }}"
                    class="page-link">Prev</a>
            </li>
            <li class="page-item">
                <ul class="pagination justify-content-center">
                    @for($i = $page - 4; $i <= $page + 4; $i++)
                        @if($i < 1 || $i > $totalPages)
                            @continue
                        @endif

                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ route($route, array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </li>

            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a href="{{ route($route, array_merge(request()->query(), ['page' => $page + 1])) }}"
                    class="page-link">Next</a>
            </li>
        </ul>
    </div>
@endif