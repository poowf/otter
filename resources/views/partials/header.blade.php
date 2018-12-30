@section("header")
    <div class="header py-4">
        <div class="container">
            <div class="d-flex">
                <a class="header-brand" href="/otter">
                    <img src="{{ asset('vendor/otter/assets/img/logo.png') }}" class="header-brand-img" alt="tabler logo">
                </a>
                <div class="d-flex order-lg-2 ml-auto">
                    <div class="dropdown">
                        <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                            <span class="avatar" style="background-image: url({{ \Poowf\Otter\Otter::getGravatarLink(auth()->user()->{config('otter.user.email', 'email')}) }})"></span>
                            <span class="ml-2 d-none d-lg-block">
                      <span class="text-default">{{ auth()->user()->{config('otter.user.name', 'name')} }}</span>
                      <small class="text-muted d-block mt-1">Administrator</small>
                    </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
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
                <div class="col-lg-3 ml-auto">
                    <form class="input-icon my-3 my-lg-0">
                        <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                        <div class="input-icon-addon">
                            <i class="fe fe-search"></i>
                        </div>
                    </form>
                </div>
                <div class="col-lg order-lg-first">
                    <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                        <li class="nav-item">
                            <a href="/otter" class="nav-link active"><i class="fe fe-home"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="mobile-menu" class="nav-link" data-toggle="dropdown"><i class="fe fe-box"></i> Interface</a>
                            <div id="mobile-menu" class="dropdown-menu dropdown-menu-arrow">
                                <a href="./cards.html" class="dropdown-item ">Cards design</a>
                                <a href="./charts.html" class="dropdown-item ">Charts</a>
                                <a href="./pricing-cards.html" class="dropdown-item ">Pricing cards</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@show