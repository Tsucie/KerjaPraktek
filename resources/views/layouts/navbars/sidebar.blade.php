<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('assets') }}/img/brand/Sidebar-pkslogo.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        {{-- <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg"> --}}
                        <i class="fas fa-user"></i>
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets') }}/img/brand/Sidebar-pkslogo.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-chart-bar-32 text-primary" title="{{ __('Dashboard') }}"></i>
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-user" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-user" onclick="openSidebar()">
                        <i class="fas fa-users" style="color: #f4645f;" title="{{ __('User Management') }}"></i>
                        <span class="nav-link-text">{{ __('User Management') }}</span>
                    </a>

                    <div class="collapse" id="navbar-user">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users') }}">
                                    <i class="fas fa-user-cog" style="color: #f4645f;" title="{{ __('User Admin') }}"></i>
                                    <span class="nav-link-text">{{ __('User Admin') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customers') }}">
                                    <i class="fas fa-user-tag" style="color: #f4645f;" title="{{ __('User Customer') }}"></i>
                                    <span class="nav-link-text">{{ __('User Customer') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-services" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-services" onclick="openSidebar()">
                        <i class="fas fa-building" style="color: green;" title="{{ __('Layanan') }}"></i>
                        <span class="nav-link-text">{{ __('Layanan') }}</span>
                    </a>

                    <div class="collapse" id="navbar-services">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('venues') }}">
                                    <i class="fas fa-building" style="color: green;" title="{{ __('Gedung') }}"></i>
                                    <span class="nav-link-text">{{ __('Gedung') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products') }}">
                                    <i class="fas fa-gift" style="color: green;" title="{{ __('Produk') }}"></i>
                                    <span class="nav-link-text">{{ __('Produk') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/Promo') }}">
                                    <i class="fas fa-percentage" style="color: green;" title="{{ __('Promo') }}"></i>
                                    <span class="nav-link-text">{{ __('Promo') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#navbar-order" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-order" onclick="openSidebar()">
                        <i class="fas fa-receipt text-primary" title="{{ __('Order & Feedback') }}"></i>
                        <span class="nav-link-text">{{ __('Order & Feedback') }}</span>
                    </a>

                    <div class="collapse" id="navbar-order">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/OrderVenue') }}">
                                    <i class="fas fa-receipt text-primary" title="{{ __('Order Gedung/Ruangan') }}"></i>
                                    <span class="nav-link-text">{{ __('Order Gedung/Ruangan') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/OrderProduct') }}">
                                    <i class="fas fa-cart-arrow-down text-primary" title="{{ __('Order Produk') }}"></i>
                                    <span class="nav-link-text">{{ __('Order Produk') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/Feedback') }}">
                                    <i class="fas fa-comment-alt text-primary" title="{{ __('Order') }}"></i>
                                    <span class="nav-link-text">{{ __('Feedback') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
        </div>
    </div>
</nav>
