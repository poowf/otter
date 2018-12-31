@section("sidebar")
    <div class="col-md-2 px-0 sidebar fixed">
        <div class="header py-4">
            <div class="container">
                <div class="d-flex">
                    <a class="header-sidebar-brand" href="/otter">
                        <img src="{{ asset('vendor/otter/assets/img/logo.svg') }}" class="header-brand-img" alt="tabler logo">
                    </a>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs border-0 flex-md-column flex-nowrap justify-content-center">
            <li class="nav-item">
                <a href="/otter" class="nav-link pl-4"><i class="fe fe-home"></i> Home</a>
            </li>
            <sidebar-component
                    :all-resource-names="{{ $allResourceNames }}"
            ></sidebar-component>
        </ul>
    </div>
@show