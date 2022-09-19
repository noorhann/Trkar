<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="user-img" title="Mat Helme"
                class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-toggle="dropdown">Geneva Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings mr-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock mr-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <a href="{{ route('admin.logout') }}" class="nav-link">
                            <i data-feather="log-out"></i>
                            <span>{{ __('Log Out') }}</span>
                        </a>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                {{-- <li class="menu-title">Navigation</li>
                            @endif --}}
                <li>

                    <a href="{{ url('/admin') }}">
                        <i data-feather="airplay"></i>
                        <span> {{ __('Dashboard') }} </span>
                    </a>
                </li>
                <li>


                    <a href="#sidebarProductManagement" data-toggle="collapse">
                        <i data-feather="airplay"></i>
                        {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                        <span>{{ __('Product Management') }} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarProductManagement">

                        <ul class="nav-second-level">

                            @if (App\Helpers\Helper::adminCan('admin.category.index'))
                                <li>
                                    <a href="{{ route('admin.category.index') }}">{{ __('Categories') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.car-made.index'))
                                <li>
                                    <a href="{{ route('admin.car-made.index') }}">{{ __('Mades') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.car-model.index'))
                                <li>
                                    <a href="{{ route('admin.car-model.index') }}">{{ __('Models') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.car-engine.index'))
                                <li>
                                    <a href="{{ route('admin.car-engine.index') }}">{{ __('Engines') }}</a>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.manufacturer.index'))
                                <li>
                                    <a href="{{ route('admin.manufacturer.index') }}">{{ __('Manufacturers') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.year.index'))
                                <li>
                                    <a href="{{ route('admin.year.index') }}">{{ __('Years') }}</a>
                                </li>
                            @endif

                            @if (App\Helpers\Helper::adminCan('admin.original-country.index'))
                                <li>
                                    <a
                                        href="{{ route('admin.original-country.index') }}">{{ __('Original Country') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.attribute.index'))
                                <li>
                                    <a href="{{ route('admin.attribute.index') }}">{{ __('Attributes') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.tyre-type.index'))
                                <li>
                                    <a href="{{ route('admin.tyre-type.index') }}">{{ __('Tyre Types') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.attribute-tyre.show'))
                                <li>
                                    <a href="{{ url('admin/attribute-tyre') }}/1">{{ __('Attributes Tyres') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.attribute-oil.show'))
                                <li>
                                    <a href="{{ url('admin/attribute-oil') }}">{{ __('Attributes Oils') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.product.index'))
                                <li>
                                    <a href="{{ route('admin.product.index') }}">{{ __('Products') }}</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                    {{-- </li>
                            @endif --}}
                <li>
                    <a href="#sidebarVendorManagement" data-toggle="collapse">
                        <i data-feather="airplay"></i>
                        {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                        <span> {{ __('Vendor Management') }} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarVendorManagement">
                        <ul class="nav-second-level">
                            @if (App\Helpers\Helper::adminCan('admin.store-type.index'))
                                <li>
                                    <a href="{{ route('admin.store-type.index') }}">{{ __('Store Types') }}</a>
                                </li>
                                @if (App\Helpers\Helper::adminCan('admin.store.index'))
                                @endif
                                <li>
                                    <a href="{{ route('admin.store.index') }}">{{ __('Stores') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.store-branch.index'))
                                <li>
                                    <a href="{{ route('admin.store-branch.index') }}">{{ __('Stores Branches') }}</a>
                                </li>
                            @endif
                            {{-- @if (App\Helpers\Helper::adminCan('admin.vendor-reject.index'))
                                <li>
                                    <a href="{{ route('admin.vendor-reject.index') }}">{{ __('Vendors Reject') }}</a>
                                </li>
                            @endif --}}

                            @if (App\Helpers\Helper::adminCan('admin.vendor-staff.index'))
                                <li>
                                    <a href="{{ route('admin.vendor-staff.index') }}">{{ __('Vendors Staff') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.store-vendor-staff.index'))
                                <li>
                                    <a
                                        href="{{ route('admin.store-vendor-staff.index') }}">{{ __('Store Vendors Staff') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.store-audit-log.index'))
                                <li>
                                    <a
                                        href="{{ route('admin.store-audit-log.index') }}">{{ __('Store Audit Logs') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.store-reject-status.index'))
                                <li>
                                    <a
                                        href="{{ route('admin.store-reject-status.index') }}">{{ __('Stores are awaiting approval') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarUserManagement" data-toggle="collapse">
                        <i data-feather="airplay"></i>
                        {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                        <span>{{ __('User Management') }} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUserManagement">
                        <ul class="nav-second-level">
                            @if (App\Helpers\Helper::adminCan('admin.admins.index'))
                                <li>
                                    <a href="{{ route('admin.admins.index') }}">{{ __('Admins') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.vendor.index'))
                                <li>
                                    <a href="{{ route('admin.vendor.index') }}">{{ __('Vendors') }}</a>
                                </li>
                            @endif
                            @if (App\Helpers\Helper::adminCan('admin.user.index'))
                                <li>
                                    <a href="{{ route('admin.user.index') }}">{{ __('Customers') }}</a>
                                </li>
                            @endif

                            @if (App\Helpers\Helper::adminCan('admin.permission-group.index'))
                                <li>
                                    <a
                                        href="{{ route('admin.permission-group.index') }}">{{ __('Permission Groups') }}</a>
                                </li>
                            @endif



                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarSales" data-toggle="collapse">
                        <i data-feather="airplay"></i>
                        {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                        <span> {{ __('Sales') }} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarSales">
                        <ul class="nav-second-level">
                            <li>
                                @if (App\Helpers\Helper::adminCan('admin.order.index'))
                            <li>
                                <a href="{{ route('admin.order.index') }}">{{ __('Orders') }}</a>
                            </li>
                            @endif

                            @if (App\Helpers\Helper::adminCan('admin.order-status.index'))
                                <li>
                                    <a href="{{ route('admin.order-status.index') }}">{{ __('Orders Statuses') }}</a>
                                </li>
                            @endif
                </li>
            </ul>
        </div>
        </li>
        @if (App\Helpers\Helper::adminCan('admin.payment-method.index'))
            <li>
                <a href="{{ route('admin.wholesale-order.index') }}">
                    <i data-feather="calendar"></i>
                    <span> {{ __('Wholesale Orders') }} </span>
                </a>
            </li>
        @endif

        @if (App\Helpers\Helper::adminCan('admin.payment-method.index'))
            <li>
                <a href="{{ route('admin.payment-method.index') }}">
                    <i data-feather="calendar"></i>
                    <span> {{ __('Payment Methods') }} </span>
                </a>
            </li>
        @endif
        @if (App\Helpers\Helper::adminCan('admin.shipping-company.index'))
            <li>
                <a href="{{ route('admin.shipping-company.index') }}">
                    <i data-feather="calendar"></i>
                    <span> {{ __('Shippings Methods') }} </span>
                </a>
            </li>
        @endif

        <li>
            <a href="#sidebarAdvertisements" data-toggle="collapse">
                <i data-feather="airplay"></i>
                {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                <span> Advertisements </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarAdvertisements">
                <ul class="nav-second-level">
                    <li>
                        <a href="#">test</a>

                    </li>

                </ul>
            </div>

        <li>
            <a href="#sidebarTickets" data-toggle="collapse">
                <i data-feather="airplay"></i>
                {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                <span> Tickets </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarTickets">
                <ul class="nav-second-level">
                    <li>
                        <a href="#">test</a>

                    </li>

                </ul>
            </div>
        </li>
        <li>
            <a href="#sidebarLocalisation" data-toggle="collapse">
                <i data-feather="airplay"></i>
                {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
                <span> {{ __('Localisation') }} </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarLocalisation">
                <ul class="nav-second-level">
                    <li>
                        @if (App\Helpers\Helper::adminCan('admin.country.index'))
                    <li>
                        <a href="{{ route('admin.country.index') }}">{{ __('Countries') }}</a>
                    </li>
                    @endif
                    @if (App\Helpers\Helper::adminCan('admin.city.index'))
                        <li>
                            <a href="{{ route('admin.city.index') }}">{{ __('Cities') }}</a>
                        </li>
                    @endif
                    @if (App\Helpers\Helper::adminCan('admin.area.index'))
                        <li>
                            <a href="{{ route('admin.area.index') }}">{{ __('Areas') }}</a>
                        </li>
                    @endif
        </li>
        </ul>
    </div>
    </li>
    <li>
        <a href="#sidebarPages" data-toggle="collapse">
            <i data-feather="airplay"></i>
            {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
            <span> Pages </span>
        </a>
        <div class="collapse" id="sidebarPages">
            <ul class="nav-second-level">
                <li>
                    <a href="#">Pages</a>

                </li>

            </ul>
        </div>
    </li>
    <li>
        <a href="#sidebarLanguages" data-toggle="collapse">
            <i data-feather="airplay"></i>
            {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
            <span> Languages </span>
        </a>
        <div class="collapse" id="sidebarLanguages">
            <ul class="nav-second-level">
                <li>
                    <a href="#">Languages</a>

                </li>

            </ul>
        </div>
    </li>
    @if (App\Helpers\Helper::adminCan('admin.activity-log.index'))
        <li>
            <a href="{{ route('admin.activity-log.index') }}">
                <i data-feather="calendar"></i>
                <span> {{ __('Activity Logs') }} </span>
            </a>
        </li>
    @endif

    <li>
        <a href="#sidebarSettings" data-toggle="collapse">
            <i data-feather="airplay"></i>
            {{-- <span class="badge badge-success badge-pill float-right">4</span> --}}
            <span> Settings </span>
        </a>
        <div class="collapse" id="sidebarSettings">
            <ul class="nav-second-level">
                <li>
                    <a href="#">Settings</a>

                </li>

            </ul>
        </div>
    </li>



    </ul>

</div>
<!-- End Sidebar -->

<div class="clearfix"></div>

</div>
<!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
