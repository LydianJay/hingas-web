
@props(['search_route', 'placeholder'])


<form action="{{ route($search_route, ['search']) }}" method="get">
    <div class="input-group my-1">
        <input type="text" class="form-control" name="search" id="search_bar" placeholder="{{$placeholder}}"
            value="{{ request()->input('search') }}">
        <span class="input-group-text">
            <button class="btn-sm btn p-0 border-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </span>
    </div>
</form>