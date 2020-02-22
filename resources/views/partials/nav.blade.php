@section("sidebar")
<ul class="nav nav-tabs border-0 flex-md-column flex-nowrap justify-content-center">
    <li class="nav-item">
        <a href="/{{ config('otter.path', 'otter') }}" class="nav-link pl-4">
            <i class="fe fe-home"></i> Home
        </a>
    </li>
    @foreach ($allResourceNames as $name)
    <li class="nav-item">
        <a href="/{{ config('otter.path', 'otter') }}/{{ $name }}" class="nav-link pl-4">
            {{ $name }}
        </a>
    </li>
    @endforeach
</ul>
@show
