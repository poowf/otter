@section("header")
    <div class="header py-4">
        <div class="container">
            <div class="d-flex">
                <a class="header-brand" href="/{{ config('otter.path', 'otter') }}">
                    <img src="{{ asset('vendor/otter/assets/img/logo.svg') }}" class="header-brand-img d-md-none" alt="tabler logo">
                </a>
                <div class="d-flex order-lg-2 ml-auto">
                    <div class="dropdown">
                        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                            <span class="avatar" style="background-image: url({{ \Poowf\Otter\Otter::getGravatarLink(auth()->user()->{config('otter.user.email', 'email')} ?? '') }})"></span>
                            <span class="ml-2 d-none d-lg-block">
                      <span>{{ auth()->user()->{config('otter.user.name', 'name')} ?? '' }}</span>
                      <small class="text-muted d-block mt-1">Administrator</small>
                    </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-dark-alternate dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="/">
                                <i class="dropdown-icon fe fe-corner-down-left"></i> Return to Application
                            </a>
                        </div>
                    </div>
                </div>
                <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                    <span class="header-toggler-icon"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="header collapse p-0" id="headerMenuCollapse">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg order-lg-first">
                    <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                        <li class="nav-item p-0">
                            <a href="/{{ config('otter.path', 'otter') }}" class="nav-link pl-4"><i class="fe fe-home"></i> Home</a>
                        </li>
                        <header-component
                                :all-resource-names="{{ $allResourceNames }}"
                                current-route="{{ Route::currentRouteName() }}"
                                path-prefix="{{ config('otter.path', 'otter') }}"
                        ></header-component>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@show